# Project Setup & Testing Guide

This guide describes how to configure your local development environment, install dependencies, run migrations/seeders, start the server, and execute tests.

---

## 1. Prerequisites

Ensure your system meets the following version requirements:
* **PHP**: `>= 8.2` (with standard extensions like `pdo_mysql`, `openssl`, `mbstring`, `xml`, `curl`)
* **Composer**: `>= 2.0`
* **Node.js**: `>= 18.0` (with `npm`)
* **Database**: MySQL or MariaDB

---

## 2. Installation Steps

### Step 1: Install Dependencies
Run Composer and NPM installs to download PHP and JavaScript vendor packages:
```bash
# Install PHP dependencies
composer install

# Install Frontend dependencies
npm install
```

### Step 2: Configure Environment
Copy the example environment file and modify it to match your local database settings:
```bash
cp .env.example .env
```
Open `.env` in your editor and configure the database connection details:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=asset_management
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Generate Application Key
Generate the secure Laravel encryption key:
```bash
php artisan key:generate
```

### Step 4: Run Migrations and Seeders
Create the database tables and populate them with the default user profiles:
```bash
php artisan migrate --seed
```

#### Seeded Accounts
The seed command creates the following default administration accounts for testing purposes:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Super Admin** | `superadmin@gmail.com` | `0130@superadmin` |
| **Admin** | `nmn8122@gmail.com` | `0130@nmn` |

### Step 5: Link Storage
Enable access to uploaded employee profile photos by symlinking the public storage folder:
```bash
php artisan storage:link
```

### Step 6: Start Servers
Run the development servers in parallel:
```bash
# Start Laravel development server (runs on http://127.0.0.1:8000 by default)
php artisan serve

# In another terminal window, start Vite compilation server
npm run dev
```

---

## 3. Running Automated Tests

The project includes an automated test suite covering CRUD operations, profile settings, and authentication:

```bash
# Execute unit and feature tests
php artisan test
```

### Key Test Suites
* **Asset CRUD Tests** (`tests/Feature/AssetCrudTest.php`): Validates list filtering, sorting capabilities, search queries, AJAX partial renders, creation requests, showing details, updates, and deletion of assets.
* **Profile Tests** (`tests/Feature/ProfileTest.php`): Validates profile viewing and edits.

> [!TIP]
> Tests utilize the `RefreshDatabase` trait which isolates unit tests by wiping and rebuilding the database schema before running each test case. Ensure your `phpunit.xml` config file is pointing to a separate testing database (e.g. `sqlite` in memory) to prevent clearing your active local development database:
> ```xml
> <env name="DB_CONNECTION" value="sqlite"/>
> <env name="DB_DATABASE" value=":memory:"/>
> ```
