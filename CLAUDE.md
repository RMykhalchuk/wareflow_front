# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Overview

Wareflow (wmspro) — enterprise Warehouse Management System for inventory, logistics, and warehouse operations across multiple companies.

## Tech Stack

- **Backend:** PHP 8.4, Laravel 12, PostgreSQL 13, Redis
- **Frontend:** Vue.js 3, Bootstrap 5, Inertia.js, jQuery (legacy), SCSS
- **Build:** Laravel Mix (Webpack)
- **Auth:** Laravel Sanctum, JWT (tymon/jwt-auth), Spatie Permissions
- **Real-time:** Pusher, Laravel Echo
- **DevOps:** Docker (Laravel Sail), Bitbucket Pipelines (CI/CD to AWS)

## Commands

```bash
# Docker (Laravel Sail)
npm run sail                   # Start containers (sudo)
npm run sail-up-user           # Start containers (no sudo)
npm run sail-stop              # Stop containers

# Queue worker
npm run work                   # Start queue worker (sudo)
npm run sail-work-user         # Start queue worker (no sudo)

# Assets
npm run dev                    # Dev build
npm run watch                  # Watch mode
npm run hot                    # HMR
npm run prod                   # Production build

# Formatting
npm run format:all             # Format all (blade, scss, js)
npm run format:all:check       # Check formatting

# Linting
npm run lint                   # ESLint check
npm run lint:fix               # ESLint fix

# PHP quality
./vendor/bin/phpcs             # PHP CodeSniffer
./vendor/bin/psalm             # Static analysis

# Testing
php artisan test               # Run all tests
./vendor/bin/phpunit           # Run all tests via PHPUnit
php artisan test --filter=TestClassName            # Run single test class
php artisan test --filter=TestClassName::testMethod # Run single test method
```

Note: Husky + lint-staged are configured as pre-commit hooks. On commit, ESLint auto-fixes JS files in `public/assets/js/`, and Prettier formats Blade templates and SCSS.

## Docker Services

- **laravel.test** — PHP 8.4 app (port 80)
- **pgsql** — PostgreSQL 13 (port 5432)
- **redis** — Redis (port 6379)
- **pgadmin** — PgAdmin UI (port 8000, login: admin@admin.com / admin)

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Api/                   # API controllers
│   ├── Terminal/              # Mobile/terminal controllers
│   └── Web/                   # Web controllers
├── Models/
│   ├── Dictionaries/          # Lookup/reference models (33)
│   └── Entities/              # Main business models (21 subdirectories)
├── Services/                  # Business logic (Api/, Terminal/, Web/, Integrations/)
├── Data/                      # DTOs (Spatie Data)
├── Enums/                     # PHP enums (13 subdirectories)
├── Tables/                    # Table data formatting (40+)
├── Traits/                    # Reusable traits (27+)
├── Observers/                 # Model lifecycle observers
├── Scopes/                    # Query scopes (Company, User filtering)
├── Http/Requests/             # Form request validation (Api/, Terminal/, Web/)
└── Http/Resources/            # API resources (Api/, Terminal/, Web/)

resources/
├── views/                     # Blade templates (30+ directories)
├── scss/                      # SCSS sources (base/, themes/)
└── js/                        # JavaScript sources (core/, scripts/)

routes/
├── web/                       # Web route modules (document.php, onboarding.php)
├── api/                       # API route modules (terminal.php, public.php)
├── web.php                    # Main web routes
└── api.php                    # Main API routes

database/migrations/           # 140+ migrations
lang/                          # en/, uk/ translations
```

## Conventions

### PHP
- PSR-12 (Laravel preset), 4 spaces indent, 120 char line max
- Models: PascalCase (`ContainerRegister`)
- Controllers: `{Name}Controller`
- Services: `{Name}Service`
- DB tables: snake_case plural (`goods_barcodes`), FK: `{table}_id`

### JavaScript / SCSS
- 4 spaces indent, single quotes, semicolons required
- Print width: 100 chars, trailing comma: ES5

## Architecture

- **Service-oriented:** Controllers delegate business logic to Services. Controllers/Services/Requests/Resources all mirror the same Api/, Terminal/, Web/ subdirectory structure.
- **Three auth guards:** `web` (session), `api` (JWT), `terminal` (JWT for mobile devices)
- **Multi-tenancy:** Company-scoped data isolation via `CompanySeparation` trait and query scopes
- **Models:** Split into Dictionaries (lookup/reference data) and Entities (business domain objects)
- **Hierarchical data:** Nested sets via kalnoy/nestedset
- **Multilingual fields:** Spatie Translatable for model fields
- **Observer pattern:** Model lifecycle hooks (Container, ContainerRegister, Document, InventoryItem, InventoryLeftover, Leftover, WarehouseZone)
- **Localization:** English (en) + Ukrainian (uk)
- **Routes:** Web uses kebab-case (`/container-register`), API prefixed `/api`, `/api/terminal`, `/api/public`