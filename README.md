# Matcha Hotspot Billing System

A comprehensive billing and management system for commercial ISPs and WiFi hotspots.

## Features

### Core Billing Features
- Voucher-based and account-based access
- Multiple plan types: time-based, data-based, speed-based, unlimited
- Automatic session tracking and enforcement
- Concurrent session control
- Wallet system with pay-as-you-go billing
- Detailed usage logging

### Campaign Management
- Free trials and referral rewards
- Time-based promotions
- Discount campaigns
- Analytics and tracking

### Administrative Dashboard
- Real-time session monitoring
- Voucher batch management
- User and device management
- Financial reporting
- Role-based access control

### User Interface
- Responsive captive portal
- Account dashboard
- Purchase and renewal flows
- Mobile-first design

## Technical Architecture

### Backend Stack
- **Framework**: Laravel 9.x+ with PHP 8.x
- **Database**: MySQL 8.x / MariaDB 10.x
- **Authentication**: RADIUS integration (CoovaChilli/FreeRADIUS)
- **Queue System**: Redis with Laravel Horizon
- **Payment Processing**: Stripe/PayPal API
- **File Generation**: PDF generation for vouchers

### Frontend Stack
- **Framework**: Vue.js 3 with Composition API
- **Styling**: Tailwind CSS with custom components
- **State Management**: Pinia
- **Routing**: Vue Router
- **HTTP Client**: Axios

### Infrastructure
- **Web Server**: Nginx with SSL termination
- **Load Balancer**: HAProxy or cloud load balancer
- **Monitoring**: Laravel Telescope, Logstash
- **Backup Strategy**: Automated database backups
