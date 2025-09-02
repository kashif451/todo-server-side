<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Features


## Sanctum Authentication

- User registration and login
- Protected API endpoints
- Token-based authentication


## Task Management API

- Create, read, update, and delete tasks
- Mark tasks as complete/incomplete
- User-specific task management


## Getting Started

## ✅ Prerequisites

Before running this project, make sure you have the following installed:

- **PHP 8.1 or higher**  
  _(Your current version: PHP 8.3.17 ✅)_
- **Composer** – [Download Composer](https://getcomposer.org/download/)
- **MySQL Database** – For storing application data
- **TablePlus** (or any DB client) – For database management




## Installation

## Clone the repository

```bash
git clonehttps://github.com/kashif451/todo-server-side.git
cd todo-backend
```

## Install dependencies

```bash
composer install
```

## Install and configure Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

## Environment Setup

Copy the environment file and configure your database:


## Configure Database Connection for TablePlus

Update your .env file with your MySQL credentials:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_app
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

## Run database migrations
```bash
php artisan migrate
```

## Start the development server

```bash
php artisan serve
```
The API will be available at http://localhost:8000

## Authentication Endpoints

- POST /api/auth/register
- POST /api/auth/login
- GET api/auth/me

## Task Endpoints

- POST /api/tasks
- GET /api/tasks
- PUT /api/tasks/{id}
- DELETE /api/tasks/{id}
