# Route Map & API Endpoints

This document maps all web routes, middleware groups, controller actions, request validation payloads, and expected JSON structures.

---

## 1. Authentication Endpoints (Public/Guest)

These routes handle the basic session authentication lifecycle.

### Registration
* **`GET /register`**
  * **Controller**: `AuthController@showRegisterForm`
  * **Middleware**: `guest`
  * **Returns**: Registration View
* **`POST /register`**
  * **Controller**: `AuthController@register`
  * **Middleware**: `guest`
  * **Payload Validation**:
    * `name` (required, string, max 255)
    * `email` (required, string, email, unique:users)
    * `password` (required, string, min 8, confirmed)
    * `terms` (accepted)
  * **Action**: Hashes password, registers user, logs them in, and redirects to `/dashboard`.

### Login & Logout
* **`GET /login`**
  * **Controller**: `AuthController@LoginForm`
  * **Middleware**: `guest`
  * **Returns**: Login View
* **`POST /login`**
  * **Controller**: `AuthController@login`
  * **Middleware**: `guest`
  * **Payload Validation**:
    * `email` (required, email)
    * `password` (required)
    * `remember` (optional, boolean)
  * **Action**: Attempts authentication. On success, regenerates session and redirects to `/dashboard`. On failure, returns with errors.
* **`POST /logout`**
  * **Controller**: `AuthController@logout`
  * **Middleware**: `auth`
  * **Action**: Flushes user session, regenerates CSRF token, and redirects to `/login`.

---

## 2. Dashboard Router

* **`GET /dashboard`**
  * **Middleware**: `auth`
  * **Behavior**: Checks the user role.
    * If `role` is `super_admin`, redirects to `/accounts`.
    * Otherwise, returns the `dashboard` view.

---

## 3. Employee Directory Endpoints (Admin Only)

Used by tenant Administrators to manage active organization employees.
* **Route Group Middleware**: `['auth', 'role:admin']`
* **Controller**: [EmployeeController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/Employee/EmployeeController.php)
* **Form Request**: [EmployeeRequest.php](file:///c:/laragon/www/asset-managment/app/Http/Requests/EmployeeRequest.php)

### Endpoints

#### `GET /employees`
* **Query Parameters**:
  * `search` (string, optional) - Filters list by employee `name`, `email`, `department`, or `position`.
  * `page` (int, optional) - Pagination index (10 records per page).
* **Behavior**: If requested via AJAX (header `X-Requested-With: XMLHttpRequest`), returns partial view `admin.employees.table`. Otherwise, returns full view `admin.employees.index`.

#### `POST /employees`
* **Headers**: `Content-Type: multipart/form-data`
* **Payload Validation**:
  * `user_id` (nullable, integer, exists:users,id, unique:employee)
  * `name` (required, string, max 255)
  * `email` (required, email, max 255, unique:employee)
  * `employee_id` (required, string, max 50, unique:employee)
  * `position` (required, string, max 100)
  * `department` (required, string, max 100)
  * `status` (required, in:active,inactive,on_leave)
  * `phone` (required, string, max 20)
  * `profile_photo` (nullable, image, mimes:jpeg,png,jpg,gif, max 2MB)
  * `join_date` (required, date)
* **Returns**: JSON object with `message` and `employee` resource (Status 201).

#### `GET /employees/{employee}`
* **Returns**: JSON representation of the Employee profile loaded with their associated `user` object.

#### `PUT /employees/{employee}`
* **Payload Validation**: Same fields as `POST`, but unique checks ignore the current employee's ID.
* **Returns**: JSON object with success message and updated `employee` resource.

#### `DELETE /employees/{employee}`
* **Returns**: JSON confirmation (Status 200).

---

## 4. Asset Management Endpoints (Admin & Employee)

Enables tracking, searching, and updates on physical inventory assets.
* **Route Group Middleware**: `['auth', 'role:admin, employee ']` *(Note: trailing space exists in the source code definition)*
* **Controller**: [AssetController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/Admin/AssetController.php)
* **Form Request**: [AssetRequest.php](file:///c:/laragon/www/asset-managment/app/Http/Requests/AssetRequest.php)

### Endpoints

#### `GET /manage-assets`
* **Query Parameters**:
  * `search` (string, optional) - Searches `asset_code`, `name`, `serial_number`, `condition`, or `notes`.
  * `sort_by` (string, optional) - Column sorting. Allowed: `asset_code`, `name`, `serial_number`, `purchased_price`, `purchased_date`, `condition`, `warranty_expiry`, `maintenance_date`, `created_at` (default).
  * `sort_dir` (string, optional) - Direction: `asc` or `desc` (default).
* **Behavior**:
  * If requested with JSON expectations (`wantsJson()`), returns the unpaginated array of matching assets.
  * If requested via AJAX, returns partial HTML view `admin.assets.table`.
  * Otherwise, returns the base dashboard listing view `admin.assets.index` (paginated to 10).

#### `POST /manage-assets`
* **Payload Validation**:
  * `asset_code` (required, string, max 50, unique:assets)
  * `name` (required, string, max 255)
  * `serial_number` (required, string, max 100, unique:assets)
  * `purchase_price` (nullable, numeric, min 0)
  * `purchase_date` (nullable, date)
  * `condition` (nullable, backed enum value: `Excellent`, `Good`, `Fair`, `Poor`, `Under Repair`)
  * `warranty_expiry` (nullable, date)
  * `maintenance_date` (nullable, date)
  * `notes` (nullable, string, max 1000)
* **Returns**: JSON object containing success message and created `asset` (Status 201).

#### `GET /manage-assets/{asset}`
* **Returns**: JSON representation of the selected Asset.

#### `PUT /manage-assets/{asset}`
* **Payload Validation**: Same fields as `POST`, ignoring current record uniqueness limits.
* **Returns**: JSON object with success message and updated `asset` payload.

#### `DELETE /manage-assets/{asset}`
* **Returns**: JSON confirmation (Status 200).

---

## 5. Account Management Endpoints (Super Admin Only)

Provides controls for managing system administrators.
* **Route Group Middleware**: `['auth', 'role:super_admin']`
* **Controller**: [AccountController.php](file:///c:/laragon/www/asset-managment/app/Http/Controllers/AccountController.php)
* **Form Request**: [AccountRequest.php](file:///c:/laragon/www/asset-managment/app/Http/Requests/AccountRequest.php)

### Endpoints

#### `GET /accounts`
* **Query Parameters**:
  * `search` (string, optional) - Filters administrators by `name` or `email`.
* **Behavior**: If AJAX, returns partial view `admin.accounts.table`. Otherwise, returns full view `admin.accounts.index`.

#### `POST /accounts`
* **Payload Validation**:
  * `name` (required, string, max 255)
  * `email` (required, email, max 255, unique:users)
  * `role` (required, in:admin,super_admin)
  * `password` (required, string, min 8, confirmed)
* **Returns**: JSON success confirmation and the created admin `User` record (Status 201).

#### `GET /accounts/{user}`
* **Returns**: JSON metadata of the administrator. Triggers a 404 response if the user is not an admin/super-admin.

#### `PUT /accounts/{user}`
* **Payload Validation**: Same fields as `POST`, but password is `nullable` (retains existing if left blank) and unique checks ignore the current user ID.
* **Returns**: JSON success confirmation and updated `User` profile.

#### `DELETE /accounts/{user}`
* **Behavior**: Deletes the specified administrator. Returns a 400 error if trying to self-delete.
* **Returns**: JSON confirmation.
