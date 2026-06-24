# Asset Management Relational & Multi-Tenant Design Plan

This plan details the current state of the database and relationship structures in the Asset Management project, highlights several structural errors and anomalies in the existing codebase, and provides a comprehensive relational database schema and role management design.

---

## 1. Critical Errors & Design Issues in Current Codebase

During analysis, several crucial inconsistencies and functional errors were identified in the existing schema and models:

### ⚠️ Broken `User -> Asset` Relationship
In [User.php](file:///c:/laragon/www/asset-managment/app/Models/User.php#L64-L67), the model declares a `HasMany` relationship with the `Asset` model:
```php
public function assets(): HasMany
{
    return $this->hasMany(Asset::class);
}
```
However, looking at the assets migration [2026_05_17_220032_create_assets_table.php](file:///c:/laragon/www/asset-managment/database/migrations/2026_05_17_220032_create_assets_table.php), **there is no `user_id` foreign key column**. Calling `$user->assets` will crash with a SQL exception (`Unknown column 'assets.user_id'`).

### ⚠️ Unused `accounts` Table vs. `User` Accounts
- A migration exists for an [accounts table](file:///c:/laragon/www/asset-managment/database/migrations/2026_05_29_182407_create_accounts_table.php) containing only `id` and `timestamps`.
- The [Account model](file:///c:/laragon/www/asset-managment/app/Models/Account.php) is empty and unused.
- Instead, [AccountController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/AccountController.php#L18) queries the `users` table for admins:
  ```php
  $query = User::whereIn('role', ['admin', 'super_admin']);
  ```
  This creates naming conflicts and leaves a dummy `accounts` table in the database that has no relationship to the user accounts being managed.

### ⚠️ Orphaned `assign_to` Column
In the `assets` table, the column `assign_to` is defined as a `string` (varchar) instead of a foreign key referencing `employee` or `users`. Furthermore, it is not present in [AssetRequest.php](file:///c:/laragon/www/asset-managment/app/Http/Requests/AssetRequest.php) or any form views, making asset assignment non-functional.

### ⚠️ No Scope Separation / Lack of Multi-Tenancy
Admins are supposed to manage "their own employees" and "their own assets". Currently:
- `Asset::query()` and `EmployeeModel::query()` in [AssetController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/Admin/AssetController.php#L15) and [EmployeeController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/Employee/EmployeeController.php#L17) load **all records in the system** regardless of which admin is logged in.
- There is no database field linking an admin to their employees or assets.

### ⚠️ Data Redundancy & Drift
The `employee` table duplicates the `name` and `email` columns of the parent `users` record. If a user updates their profile name or email, the employee record will drift and become inconsistent.

---

## 2. Proposed Relational Schema Designs

To resolve these errors and support the business rules where:
1. **Super Admins** manage all organization/administrator accounts.
2. **Admins** manage only the employees and assets under their scope/tenant.
3. **Employees** manage (or are assigned) specific assets.

We propose two approaches. **Option B (Multi-Tenant Organization Accounts)** is highly recommended for scalable corporate asset management.

### Option A: Admin-Based Ownership (Direct Relational)
Admins own employees and assets directly through a parent-child relationship.

```mermaid
erDiagram
    USERS ||--o| EMPLOYEE : "has profile (if employee role)"
    USERS ||--oN EMPLOYEE : "manages (Admin to Employees)"
    USERS ||--oN ASSETS : "manages (Admin to Assets)"
    EMPLOYEE ||--oN ASSETS : "is assigned"
```

### Option B: Tenant Organization Accounts (Recommended)
This approach leverages the currently unused `accounts` table to represent **tenant organizations (companies)**. 
- A **Super Admin** sits at the system level and manages the tenants (`accounts`) and their primary `admin` users.
- An **Admin** is associated with an `account_id` and manages employees/assets scoped *only* to that tenant account.
- **Employees** and **Assets** are scoped to the `account_id` to guarantee tenant isolation.

```mermaid
erDiagram
    ACCOUNTS ||--oN USERS : "belongs to"
    ACCOUNTS ||--oN ASSETS : "belongs to"
    USERS ||--o| EMPLOYEE : "has profile"
    EMPLOYEE ||--oN ASSETS : "is assigned"
```

---

## 3. Database Migration Blueprint (Option B)

Here is the structured SQL schema represented in Laravel Migrations to achieve the tenant structure:

### 1. `accounts` Table (Tenant Companies)
Defines the client organizations managed by the Super Admin.
```php
Schema::create('accounts', function (Blueprint $table) {
    $table->id();
    $table->string('company_name');
    $table->string('company_email')->unique()->nullable();
    $table->string('subscription_plan')->default('basic');
    $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active');
    $table->timestamps();
});
```

### 2. `users` Table (System Users)
Connects all roles (`super_admin`, `admin`, `employee`) to their account scopes.
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete(); // Nullable for Super Admin
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['super_admin', 'admin', 'employee'])->default('employee');
    $table->rememberToken();
    $table->timestamps();
});
```

### 3. `employees` Table (Profiles linked to Users)
Maintains employee metadata. `name` and `email` are removed from this table to avoid duplication, fetching them directly from the `users` relationship.
```php
Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete(); // Enforces tenant boundary
    $table->string('employee_id')->unique(); // Business identifier (e.g. EMP-001)
    $table->string('position');
    $table->string('department');
    $table->string('phone');
    $table->string('profile_photo')->nullable();
    $table->date('join_date');
    $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
    $table->timestamps();
});
```

### 4. `assets` Table (Assets linked to Accounts & Employees)
Contains assets scoped to the tenant, with dynamic assignment to an employee.
```php
Schema::create('assets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete(); // Enforces tenant boundary
    $table->string('asset_code')->unique();
    $table->string('name');
    $table->string('serial_number')->nullable();
    $table->decimal('purchased_price', 10, 2)->nullable();
    $table->date('purchased_date')->nullable();
    $table->decimal('current_value', 10, 2)->nullable();
    $table->string('condition')->default('Good');
    
    // Assignment relationship:
    $table->foreignId('assigned_employee_id')->nullable()->constrained('employees')->nullOnDelete();
    $table->timestamp('assigned_at')->nullable();
    
    $table->date('warranty_expiry')->nullable();
    $table->date('maintenance_date')->nullable();
    $table->text('notes')->nullable();
    
    // Optional details
    $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
    $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
    $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
    
    $table->timestamps();
});
```

---

## 4. How Roles Manage Their Data

### 👑 Super Admin
- **Scope**: Global (not bound to any single company `account_id`).
- **Capabilities**:
  - Full CRUD operations on the `accounts` table (Organizations/Tenants).
  - Creation/management of `admin` user accounts and assigning them to their respective `accounts`.
  - Global system settings, audit logs, and status checks.
- **SQL Data Access Constraint**:
  ```php
  // Super Admin lists all organizations and their owner accounts
  $companies = Account::with('users')->get();
  ```

### 👔 Admin
- **Scope**: Single Tenant (limited strictly to their session `auth()->user()->account_id`).
- **Capabilities**:
  - View, create, update, and terminate profiles in the `employees` table under their `account_id`.
  - Create and manage the company `assets`.
  - Assign assets to company employees by updating `assigned_employee_id` in the `assets` table.
- **SQL Data Access Constraint (Scope Isolation)**:
  ```php
  // Automatically filter employees and assets by the logged-in admin's account
  $employees = Employee::where('account_id', auth()->user()->account_id)->get();
  $assets = Asset::where('account_id', auth()->user()->account_id)->get();
  ```

### 💼 Employee
- **Scope**: Self-owned / individual.
- **Capabilities**:
  - Log in to see their employee dashboard.
  - View assets currently assigned to them.
  - Submit maintenance requests or reports for their assigned assets.
- **SQL Data Access Constraint**:
  ```php
  // An employee can only fetch assets assigned directly to their profile
  $myAssets = Asset::where('assigned_employee_id', auth()->user()->employee->id)->get();
  ```

---

## 5. Summary of Recommended Code Changes

To transition the current project to this robust architecture, the following updates are proposed:

1. **Database Schema Refactoring**: Run a migration schema update that implements `account_id` on the `users`, `employees`, and `assets` tables, and replaces the string `assign_to` with the foreign key `assigned_employee_id`.
2. **Model Relational Definitions**:
   - Add `belongsTo(Account::class)` to `User`, `Employee`, and `Asset`.
   - Update `User -> hasOne(Employee::class)`.
   - Update `Asset -> belongsTo(Employee::class, 'assigned_employee_id')`.
   - Update `Employee -> hasMany(Asset::class, 'assigned_employee_id')`.
3. **Data Scoping in Controllers**:
   - Implement a **Global Query Scope** (e.g., `TenantScope`) or explicitly add `->where('account_id', auth()->user()->account_id)` within the `AssetController` and `EmployeeController` lists to ensure absolute isolation.
4. **Clean up routing code smell**: Remove trailing space in `role:admin, employee ` in `web.php`.
5. **Adjust Seeders & Test Coverage**: Update the `UserFactory` and `AssetCrudTest` to generate `accounts` first and associate users/assets with the respective tenants to keep the test environment green.

---

## 6. Verification Plan

### Automated Test Adjustments
- Re-run `php artisan test` after updating the factories to verify registration, login, and simple retrieval workflows.
- Create new test cases verifying that an Admin of `Account A` cannot view, edit, or delete assets or employees belonging to `Account B`.

### Manual Testing Flow
1. Login as Super Admin and create a new Organization Account (`Apex Corp`).
2. Create an Admin account (`apex_admin@apex.com`) associated with `Apex Corp`.
3. Log in as `apex_admin@apex.com`, verify that no assets/employees are visible initially.
4. Create an employee profile and an asset, verify they save successfully with `account_id` populated automatically.
5. Log in as a different Admin/user and ensure `Apex Corp` assets/employees are inaccessible.
