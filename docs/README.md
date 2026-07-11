# Asset Management Project Documentation

Welcome to the **Asset Management System** documentation. This suite of documents details the system's architecture, database design, API endpoints, and configuration setup.

## Table of Contents

1. [System Architecture](file:///c:/laragon/www/asset-managment/docs/architecture.md)
   - Role-Based Access Control (RBAC) details.
   - Multi-tenant design details and implementation boundary.
   - Conceptual entity and data scope relationships.
2. [Database Schema](file:///c:/laragon/www/asset-managment/docs/database.md)
   - Schema mapping for all tables.
   - Castings, enums (`AssetCondition`), and data types.
   - Caveats, discrepancies, and known areas of database improvements.
3. [API Routes & Endpoints](file:///c:/laragon/www/asset-managment/docs/api-endpoints.md)
   - Full list of system routes and endpoint signatures.
   - Request payloads, parameters, and form validation classes.
   - Responses and AJAX endpoints.
4. [Local Project Setup](file:///c:/laragon/www/asset-managment/docs/setup.md)
   - Setup guidelines and local dependencies.
   - Environment variables configuration.
   - Seeding default records and database migrations.
   - Testing instructions with PHPUnit.

---

## Project Overview

The **Asset Management System** is a Laravel-based web application designed to track and manage hardware, software, and office asset inventories. The application handles registration, profile configuration, and multi-layered management access scopes for administrators and organizational employees.

### Key Capabilities

* **Tenant Scoped Separation**: Isolation of inventory, employees, and settings under distinct client accounts.
* **Role-Based Security**: Complete route-level middleware gates protecting Super Admin, Admin, and Employee actions.
* **Asset Allocation**: Capabilities for managing, searching, sorting, and assigning assets to active employee profiles.
* **AJAX-Powered Tables**: Dynamic dashboard search and pagination built with Laravel Blade and Axios/Vanilla JS requests.
