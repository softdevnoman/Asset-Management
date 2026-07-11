# Database Schema & Data Dictionary

This document details the database tables, schemas, field types, relationships, model configurations, and key design constraints of the Asset Management system.

---

## 1. Table Schemas

### `users` Table
Stores authentication and high-level role information for all participants.
* **Model**: [User.php](file:///c:/laragon/www/asset-managment/app/Models/User.php)
* **Migration**: [0001_01_01_000000_create_users_table.php](file:///c:/laragon/www/asset-managment/database/migrations/0001_01_01_000000_create_users_table.php) and role additions.

| Column | Type | Nullable | Key | Default | Description |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | No | Primary | *auto_increment* | Unique identifier. |
| `name` | varchar(255) | No | | | Full name of the user. |
| `email` | varchar(255) | No | Unique | | Email address (login credential). |
| `email_verified_at` | timestamp | Yes | | `NULL` | Verification timestamp. |
| `password` | varchar(255) | No | | | Hashed password. |
| `role` | enum('admin', 'super_admin', 'employee') | No | | `'employee'` | Access tier of the user. |
| `remember_token` | varchar(100) | Yes | | `NULL` | Remember me session token. |
| `created_at` | timestamp | Yes | | `NULL` | Record creation timestamp. |
| `updated_at` | timestamp | Yes | | `NULL` | Record last-updated timestamp. |

---

### `accounts` Table
Represents tenant organizations/companies.
* **Model**: [Account.php](file:///c:/laragon/www/asset-managment/app/Models/Account.php)
* **Migration**: [2026_05_29_182407_create_accounts_table.php](file:///c:/laragon/www/asset-managment/database/migrations/2026_05_29_182407_create_accounts_table.php)

| Column | Type | Nullable | Key | Default | Description |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | No | Primary | *auto_increment* | Unique identifier. |
| `company_name` | varchar(255) | No | | | Name of the tenant organization. |
| `company_email` | varchar(255) | Yes | Unique | `NULL` | Contact email of the organization. |
| `subscription_plan` | varchar(255) | No | | `'basic'` | Billing subscription status. |
| `status` | enum('active', 'suspended', 'cancelled') | No | | `'active'` | Account operational status. |
| `created_at` | timestamp | Yes | | `NULL` | Record creation timestamp. |
| `updated_at` | timestamp | Yes | | `NULL` | Record last-updated timestamp. |

---

### `employee` Table
Stores extended profiles for users associated with the `employee` role.
* **Model**: [Employee.php](file:///c:/laragon/www/asset-managment/app/Models/Employee.php)
* **Migration**: [2026_05_24_063253_create_employee_table.php](file:///c:/laragon/www/asset-managment/database/migrations/2026_05_24_063253_create_employee_table.php) and email/name additions.

| Column | Type | Nullable | Key | Default | Description |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | No | Primary | *auto_increment* | Unique identifier. |
| `user_id` | bigint unsigned | Yes | Foreign | `NULL` | Links to `users.id` (cascades on delete). |
| `name` | varchar(255) | No | | | Display name of the employee profile. |
| `email` | varchar(255) | Yes | Unique | `NULL` | Email address. |
| `employee_id` | varchar(255) | No | Unique | | Reference code (e.g. EMP-001). |
| `position` | varchar(255) | No | | | Corporate job title. |
| `department` | varchar(255) | No | | | Organizational department. |
| `phone` | varchar(255) | No | | | Contact phone number. |
| `profile_photo` | varchar(255) | Yes | | `NULL` | Path to storage directory image file. |
| `join_date` | date | No | | | Date of joining. |
| `status` | enum('active', 'inactive', 'on_leave') | No | | `'active'` | Profile status. |
| `created_at` | timestamp | Yes | | `NULL` | Record creation timestamp. |
| `updated_at` | timestamp | Yes | | `NULL` | Record last-updated timestamp. |

---

### `assets` Table
Holds individual asset records belonging to a tenant organization.
* **Model**: [Asset.php](file:///c:/laragon/www/asset-managment/app/Models/Asset.php)
* **Migration**: [2026_05_17_220032_create_assets_table.php](file:///c:/laragon/www/asset-managment/database/migrations/2026_05_17_220032_create_assets_table.php)

| Column | Type | Nullable | Key | Default | Description |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | No | Primary | *auto_increment* | Unique identifier. |
| `account_id` | bigint unsigned | No | Foreign | | Tenant reference `accounts.id` (cascades). |
| `asset_code` | varchar(255) | No | Unique | | Serial code identifier (e.g., AST-002). |
| `name` | varchar(255) | No | | | Asset name/label. |
| `category_id` | bigint unsigned | Yes | | `NULL` | Category tag identifier. |
| `serial_number` | varchar(255) | Yes | Unique | `NULL` | Manufacturer serial number. |
| `purchased_date` | date | Yes | | `NULL` | Acquisition date. |
| `purchased_price` | decimal(8,2) | Yes | | `NULL` | Original purchase cost. |
| `current_value` | decimal(8,2) | Yes | | `NULL` | Depreciated asset valuation. |
| `condition` | varchar(255) | No | | `'Good'` | Physical wear status. |
| `location_id` | bigint unsigned | Yes | | `NULL` | Location index. |
| `assign_to` | varchar(255) | Yes | | `NULL` | Name/string description of assigned staff. |
| `warranty_expiry` | date | Yes | | `NULL` | End of warranty coverage. |
| `supplier_id` | bigint unsigned | Yes | | `NULL` | Supplier reference. |
| `maintenance_date` | date | Yes | | `NULL` | Next scheduled inspection date. |
| `notes` | text | Yes | | `NULL` | Technical remarks. |
| `created_at` | timestamp | Yes | | `NULL` | Record creation timestamp. |
| `updated_at` | timestamp | Yes | | `NULL` | Record last-updated timestamp. |

---

## 2. Enums, Casts & Attributes

### Enums
* **AssetCondition**: Modeled as a backed string enum in [app/Enums/AssetCondition.php](file:///c:/laragon/www/asset-managment/app/Enums/AssetCondition.php).
  * `EXCELLENT = 'Excellent'`
  * `GOOD = 'Good'`
  * `FAIR = 'Fair'`
  * `POOR = 'Poor'`
  * `UNDER_REPAIR = 'Under Repair'`

### Casts
* **User**: `password` is cast to `hashed`, `email_verified_at` to `datetime`.
* **Employee**: `join_date` is cast to `date`.
* **Asset**: `warranty_expiry` to `date`, `maintenance_date` to `date`, and `purchased_price` to `decimal:2`.

### Appended Attributes (Asset Model)
The `Asset` model appends `purchase_price` and `purchase_date` dynamically via accessors to map database keys to the forms validation payload names:
* `getPurchasePriceAttribute()` returns `$this->purchased_price`.
* `getPurchaseDateAttribute()` handles Carbon parsing of `purchased_date` and formats it as `Y-m-d`.

---

## 3. Crucial Relational Discrepancies & Gotchas

During code reviews, several crucial inconsistencies and functional errors in the schema and model definitions were identified:

### ⚠️ Broken `User -> Asset` Relationship
* **Symptom**: In [User.php](file:///c:/laragon/www/asset-managment/app/Models/User.php#L64-L67), the model declares a `HasMany` relationship with the `Asset` model:
  ```php
  public function assets(): HasMany { return $this->hasMany(Asset::class); }
  ```
* **Discrepancy**: The database `assets` table does not contain a `user_id` column. Calling `$user->assets` will trigger a SQL exception (`Unknown column 'assets.user_id'`).

### ⚠️ Unused `accounts` Table vs. `User` Accounts
* **Symptom**: A migration exists for the `accounts` table, but the model [Account.php](file:///c:/laragon/www/asset-managment/app/Models/Account.php) is empty.
* **Discrepancy**: In [AccountController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/AccountController.php#L18), the system queries the `users` table for accounts:
  ```php
  $query = User::whereIn('role', ['admin', 'super_admin']);
  ```
  This creates a naming collision: the controller named `AccountController` actually manages administrators, leaving the physical `accounts` database table unused for tenant scopes.

### ⚠️ Orphaned `assign_to` Column
* **Symptom**: In the `assets` table, the column `assign_to` is defined as a `string` (varchar) instead of a foreign key referencing `employee.id` or `users.id`.
* **Discrepancy**: There is no direct relational link between an asset and the employee to whom it is assigned, complicating integrity checks and reporting queries.

### ⚠️ Data Redundancy & Drift
* **Symptom**: The `employee` table duplicates the `name` and `email` columns of the parent `users` record.
* **Discrepancy**: If a user updates their profile name or email, the employee record will drift and become inconsistent unless explicitly synchronized.
