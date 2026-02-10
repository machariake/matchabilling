# Matcha Hotspot Billing System

A comprehensive Laravel and Vue.js application for managing hotspot billing systems with Mikrotik integration.

## Features

- Voucher management system
- Billing plan configuration
- Mikrotik RouterOS API integration
- User authentication and authorization
- Real-time connection monitoring
- Payment processing integration
- Detailed reporting and analytics

## Requirements

- PHP >= 8.0
- Composer
- Node.js >= 14
- NPM or Yarn
- MySQL/MariaDB

## Installation

1. Clone the repository
2. Install PHP dependencies: `composer install`
3. Install Node.js dependencies: `npm install` or `yarn install`
4. Copy `.env.example` to `.env` and configure your settings
5. Generate application key: `php artisan key:generate`
6. Run database migrations: `php artisan migrate`
7. Build frontend assets: `npm run build` or `yarn build`

## Usage

1. Start the development server: `php artisan serve`
2. Build frontend assets: `npm run dev` or `yarn dev`

## License

This project is open-source software licensed under the MIT license.