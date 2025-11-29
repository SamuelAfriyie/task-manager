# Task Manager – Laravel + Vue

A simple task management application built with **Laravel**, **Vue 3**, and **SQLite**.  
Users can create projects and assign tasks to each project individually.

## 🚀 Requirements
- PHP 8.2+
- Composer
- Node.js 18+
- NPM or PNPM
- SQLite (included with most systems)

---

## 📦 Installation

### 1. Clone the repository
```bash
git clone https://github.com/SamuelAfriyie/task-manager.git
cd task-manager
```

### 2. Install backend dependencies
```bash
composer install
```

### 3. Install frontend dependencies
```bash
npm install
```

## Environment Setup

### 1. Create your .env file
```bash
cp .env.example .env
```

### 2. Set the database to SQLite
```bash
DB_CONNECTION=sqlite
DB_DATABASE=./database/database.sqlite
```

### 3. Then create the SQLite file:
```bash
touch database/database.sqlite
```

## Database Migration & Seeding

### 1. Run migrations:
```bash
php artisan migrate
```
### 2. Seed initial demo data:
```bash
php artisan db:seed
```
Your seeder can automatically create sample projects + tasks.

## Running the Project

### 1. Start the Laravel backend:
```bash
php artisan serve
```

### 2. Start the Vue frontend:
```bash
npm run dev
```

### Your app will be available at:

*   **Frontend:** [http://localhost:5173](http://localhost:5173)
*   **Backend:** [http://localhost:8000](http://localhost:8000)


### Default Login:

*   **Email:** test@example.com
*   **Password:** password

### Features

*   **Create projects**
*   **Add tasks inside each project**
*   **SQLite database** (portable & no setup needed)
*   **Seeder for example data**
*   **Vue 3 frontend with API communication**

## Additional Commands

### 1. Clear app cache:
```bash
php artisan optimize:clear
```

### 2. Re-run migrations & seeds::
```bash
php artisan migrate:fresh --seed
```