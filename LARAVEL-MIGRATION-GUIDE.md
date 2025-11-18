# Mechanic Africa - Laravel Migration Guide

## Overview
This guide will help you convert the existing PHP project to a Laravel application with Docker setup.

## Prerequisites
- Docker and Docker Compose installed
- Composer installed on your local machine
- Basic understanding of Laravel

## Docker Setup

### New Architecture
- **Nginx**: Web server (Port 9000)
- **PHP 8.2-FPM**: PHP processor
- **MySQL 8.0**: Database (Port 3307)

### Files Created
1. `docker-compose.yml` - Multi-container orchestration
2. `docker/php/Dockerfile` - PHP-FPM container
3. `docker/nginx/default.conf` - Nginx configuration

## Installation Steps

### Step 1: Run the Setup Script
```bash
./setup-laravel.sh
```

This script will:
- Backup existing files (images, database)
- Create a fresh Laravel 10 installation
- Configure Docker environment
- Build and start containers
- Set up database connection

### Step 2: Verify Docker Containers
```bash
docker compose ps
```

You should see 3 running containers:
- mechanic-africa-nginx
- mechanic-africa-php
- mechanic-africa-mysql

### Step 3: Access the Application
- **Frontend**: http://localhost:9000
- **Database**: localhost:3307

## Laravel Application Structure

### Migrations to Create

Create these migrations for the database:

```bash
# Inside PHP container
docker compose exec php bash

# Create migrations
php artisan make:migration create_contacts_table
php artisan make:migration create_partners_table
php artisan make:migration create_technicians_table
php artisan make:migration create_admin_users_table
```

### Models to Create

```bash
php artisan make:model Contact -m
php artisan make:model Partner -m
php artisan make:model Technician -m
php artisan make:model AdminUser -m
```

### Controllers to Create

```bash
# Admin Controllers
php artisan make:controller Admin/DashboardController
php artisan make:controller Admin/ContactController --resource
php artisan make:controller Admin/PartnerController --resource
php artisan make:controller Admin/TechnicianController --resource
php artisan make:controller Admin/AuthController

# Frontend Controllers
php artisan make:controller Frontend/HomeController
php artisan make:controller Frontend/ContactController
php artisan make:controller Frontend/PartnerController
php artisan make:controller Frontend/TechnicianController
```

## Database Migration

### Step 1: Export Existing SQLite Data

```bash
# Export contacts
sqlite3 contacts.db "SELECT * FROM contacts;" > contacts_export.csv

# Export partners
sqlite3 contacts.db "SELECT * FROM partners;" > partners_export.csv

# Export technicians
sqlite3 contacts.db "SELECT * FROM technicians;" > technicians_export.csv

# Export admin_users
sqlite3 contacts.db "SELECT * FROM admin_users;" > admin_users_export.csv
```

### Step 2: Import to MySQL

Create a seeder to import the data or manually import via phpMyAdmin/MySQL Workbench.

## Admin Panel Structure

### Authentication
- Use Laravel Breeze or Laravel Jetstream
- Custom guard for admin users
- Middleware for role-based access

### Admin Routes
```php
Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::resource('contacts', ContactController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('technicians', TechnicianController::class);
    Route::resource('users', UserController::class);
});
```

### Admin Features
- Dashboard with statistics
- View all form submissions
- Filter and search functionality
- Update status (pending, contacted, approved, rejected)
- Add notes to submissions
- Export to CSV/Excel
- User management

## Frontend Integration

### Blade Templates
- Convert existing HTML to Blade templates
- Create layouts (app.blade.php, admin.blade.php)
- Reuse existing CSS and JavaScript

### API Endpoints
```php
Route::post('/contact', [ContactController::class, 'store']);
Route::post('/partner/apply', [PartnerController::class, 'store']);
Route::post('/technician/apply', [TechnicianController::class, 'store']);
```

## Security Features to Implement

1. **CSRF Protection** - Built into Laravel
2. **Rate Limiting** - Use Laravel's throttle middleware
3. **Input Validation** - Form Request classes
4. **SQL Injection Prevention** - Eloquent ORM
5. **XSS Protection** - Blade templating auto-escapes
6. **Password Hashing** - Laravel's Hash facade

## Environment Variables

Key settings in `.env`:

```env
APP_NAME="Mechanic Africa"
APP_URL=http://localhost:9000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mechanic_africa
DB_USERNAME=mechanic_user
DB_PASSWORD=mechanic_secure_pass_2025
```

## Testing

### Feature Tests
```bash
php artisan make:test ContactSubmissionTest
php artisan make:test PartnerApplicationTest
php artisan make:test TechnicianApplicationTest
php artisan make:test AdminAuthenticationTest
```

### Run Tests
```bash
docker compose exec php php artisan test
```

## Deployment Considerations

1. **Production Docker Setup**
   - Use separate docker-compose.prod.yml
   - Add Redis for caching
   - Configure SSL certificates

2. **Environment**
   - Set APP_ENV=production
   - Set APP_DEBUG=false
   - Generate strong APP_KEY

3. **Database Backups**
   - Implement automated MySQL backups
   - Use volumes for persistent storage

4. **Monitoring**
   - Laravel Telescope for debugging
   - Laravel Horizon for queue management
   - Application logging

## Useful Docker Commands

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Rebuild containers
docker compose up -d --build

# Access PHP container
docker compose exec php bash

# Run artisan commands
docker compose exec php php artisan migrate
docker compose exec php php artisan db:seed
docker compose exec php php artisan cache:clear

# View logs
docker compose logs -f
docker compose logs -f nginx
docker compose logs -f php
docker compose logs -f mysql

# Access MySQL
docker compose exec mysql mysql -u mechanic_user -p mechanic_africa
```

## Package Recommendations

1. **Laravel Breeze** - Authentication scaffolding
2. **Laravel Excel** - Export functionality
3. **Spatie Laravel Permission** - Role & permission management
4. **Laravel Debugbar** - Development debugging
5. **Laravel Telescope** - Application insights

## Migration Checklist

- [ ] Docker containers running successfully
- [ ] Laravel installed and configured
- [ ] Database migrations created and run
- [ ] Models and relationships defined
- [ ] Controllers implemented
- [ ] Admin authentication setup
- [ ] Frontend views converted to Blade
- [ ] API endpoints tested
- [ ] Security features implemented
- [ ] Old data migrated to MySQL
- [ ] Image assets moved to public folder
- [ ] Email configuration
- [ ] reCAPTCHA integration
- [ ] Testing completed
- [ ] Documentation updated

## Support

For issues or questions:
1. Check Docker logs: `docker compose logs -f`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify database connection
4. Ensure proper file permissions

## Next Steps

After running the setup script, you'll need to:

1. Create database migrations
2. Set up models and relationships
3. Build admin panel controllers and views
4. Convert frontend to Blade templates
5. Implement API endpoints for form submissions
6. Test all functionality
7. Deploy to production

---

**Note**: The setup script creates a fresh Laravel installation. Your existing PHP files are backed up in `laravel-migration-backup/` directory.
