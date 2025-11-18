# Laravel Implementation Plan - Mechanic Africa

## Phase 1: Initial Setup ✓

### Completed:
- [x] Docker Compose configuration (Nginx + PHP 8.2 + MySQL)
- [x] PHP Dockerfile with all required extensions
- [x] Nginx configuration for Laravel
- [x] Setup script for automated installation
- [x] Migration guide documentation

## Phase 2: Laravel Installation & Configuration

### Tasks:
1. Run setup script: `./setup-laravel.sh`
2. Verify containers are running
3. Generate application key
4. Configure .env file

```bash
./setup-laravel.sh
docker compose ps
docker compose exec php php artisan key:generate
```

## Phase 3: Database Design & Migrations

### Migration Files to Create:

#### 1. Admin Users Table
```bash
docker compose exec php php artisan make:migration create_admin_users_table
```

```php
// database/migrations/xxxx_create_admin_users_table.php
Schema::create('admin_users', function (Blueprint $table) {
    $table->id();
    $table->string('username')->unique();
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['super_admin', 'admin', 'viewer'])->default('admin');
    $table->boolean('is_active')->default(true);
    $table->timestamp('last_login')->nullable();
    $table->foreignId('created_by')->nullable()->constrained('admin_users');
    $table->rememberToken();
    $table->timestamps();
});
```

#### 2. Contacts Table
```bash
docker compose exec php php artisan make:migration create_contacts_table
```

```php
Schema::create('contacts', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->string('selected_package')->nullable();
    $table->text('car_information');
    $table->text('message')->nullable();
    $table->ipAddress('ip_address')->nullable();
    $table->enum('status', ['new', 'contacted', 'processed'])->default('new');
    $table->text('notes')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('admin_users');
    $table->timestamps();
});
```

#### 3. Partners Table
```bash
docker compose exec php php artisan make:migration create_partners_table
```

```php
Schema::create('partners', function (Blueprint $table) {
    $table->id();
    $table->string('company_name');
    $table->string('registration_number');
    $table->string('phone_number');
    $table->string('email');
    $table->integer('technicians_count');
    $table->integer('years_in_operation');
    $table->text('workshop_address');
    $table->string('state_city');
    $table->text('services_offered');
    $table->string('mobile_mechanic_service');
    $table->ipAddress('ip_address')->nullable();
    $table->enum('status', ['pending', 'contacted', 'approved', 'rejected'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('admin_users');
    $table->timestamps();
});
```

#### 4. Technicians Table
```bash
docker compose exec php php artisan make:migration create_technicians_table
```

```php
Schema::create('technicians', function (Blueprint $table) {
    $table->id();
    $table->string('full_name');
    $table->string('phone_number');
    $table->string('email');
    $table->string('state_city');
    $table->string('area_of_specialization');
    $table->integer('years_in_operation');
    $table->string('work_type');
    $table->text('certification_training');
    $table->ipAddress('ip_address')->nullable();
    $table->enum('status', ['pending', 'contacted', 'approved', 'rejected'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamp('reviewed_at')->nullable();
    $table->foreignId('reviewed_by')->nullable()->constrained('admin_users');
    $table->timestamps();
});
```

### Run Migrations:
```bash
docker compose exec php php artisan migrate
```

## Phase 4: Models & Relationships

### Create Models:
```bash
docker compose exec php php artisan make:model AdminUser
docker compose exec php php artisan make:model Contact
docker compose exec php php artisan make:model Partner
docker compose exec php php artisan make:model Technician
```

### Model Relationships:

#### AdminUser Model
```php
// app/Models/AdminUser.php
class AdminUser extends Authenticatable
{
    protected $fillable = [
        'username', 'email', 'password', 'role', 'is_active', 'created_by', 'last_login'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(AdminUser::class, 'created_by');
    }

    public function reviewedContacts()
    {
        return $this->hasMany(Contact::class, 'reviewed_by');
    }

    public function reviewedPartners()
    {
        return $this->hasMany(Partner::class, 'reviewed_by');
    }

    public function reviewedTechnicians()
    {
        return $this->hasMany(Technician::class, 'reviewed_by');
    }
}
```

#### Contact Model
```php
// app/Models/Contact.php
class Contact extends Model
{
    protected $fillable = [
        'name', 'email', 'selected_package', 'car_information',
        'message', 'ip_address', 'status', 'notes', 'reviewed_at', 'reviewed_by'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(AdminUser::class, 'reviewed_by');
    }
}
```

## Phase 5: Authentication Setup

### Install Laravel Breeze:
```bash
docker compose exec php composer require laravel/breeze --dev
docker compose exec php php artisan breeze:install
docker compose exec php npm install
docker compose exec php npm run build
```

### Create Custom Admin Guard:

#### config/auth.php
```php
'guards' => [
    'web' => [...],
    'admin' => [
        'driver' => 'session',
        'provider' => 'admin_users',
    ],
],

'providers' => [
    'users' => [...],
    'admin_users' => [
        'driver' => 'eloquent',
        'model' => App\Models\AdminUser::class,
    ],
],
```

## Phase 6: Controllers

### Admin Controllers:

```bash
# Dashboard
docker compose exec php php artisan make:controller Admin/DashboardController

# Authentication
docker compose exec php php artisan make:controller Admin/Auth/LoginController
docker compose exec php php artisan make:controller Admin/Auth/LogoutController

# Resources
docker compose exec php php artisan make:controller Admin/ContactController --resource
docker compose exec php php artisan make:controller Admin/PartnerController --resource
docker compose exec php php artisan make:controller Admin/TechnicianController --resource
docker compose exec php php artisan make:controller Admin/UserController --resource
```

### Frontend Controllers:

```bash
docker compose exec php php artisan make:controller Frontend/HomeController
docker compose exec php php artisan make:controller Frontend/ContactController
docker compose exec php php artisan make:controller Frontend/PartnerController
docker compose exec php php artisan make:controller Frontend/TechnicianController
```

## Phase 7: Routes

### routes/web.php:

```php
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\PartnerController;
use App\Http\Controllers\Frontend\TechnicianController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/partner/apply', [PartnerController::class, 'store'])->name('partner.apply');
Route::post('/technician/apply', [TechnicianController::class, 'store'])->name('technician.apply');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [Admin\Auth\LoginController::class, 'login']);
    });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [Admin\Auth\LogoutController::class, 'logout'])->name('logout');
        
        Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('contacts', Admin\ContactController::class);
        Route::resource('partners', Admin\PartnerController::class);
        Route::resource('technicians', Admin\TechnicianController::class);
        Route::resource('users', Admin\UserController::class);
    });
});
```

## Phase 8: Form Requests (Validation)

```bash
docker compose exec php php artisan make:request StoreContactRequest
docker compose exec php php artisan make:request StorePartnerRequest
docker compose exec php php artisan make:request StoreTechnicianRequest
docker compose exec php php artisan make:request AdminLoginRequest
```

### Example: StoreContactRequest
```php
// app/Http/Requests/StoreContactRequest.php
class StoreContactRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'selected_package' => 'nullable|in:4-cylinders,7-cylinders,8-cylinders',
            'car_information' => 'required|string|max:200',
            'message' => 'nullable|string|max:1000',
            'g-recaptcha-response' => 'required', // if using reCAPTCHA
        ];
    }
}
```

## Phase 9: Middleware

### Create Rate Limiting Middleware:
```bash
docker compose exec php php artisan make:middleware RateLimitSubmissions
```

### Apply to routes:
```php
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,60'); // 10 requests per 60 minutes
```

## Phase 10: Views (Blade Templates)

### Directory Structure:
```
resources/views/
├── layouts/
│   ├── app.blade.php          # Frontend layout
│   └── admin.blade.php        # Admin layout
├── frontend/
│   ├── home.blade.php
│   └── partials/
│       ├── header.blade.php
│       ├── footer.blade.php
│       └── modals/
│           ├── partner.blade.php
│           └── technician.blade.php
└── admin/
    ├── dashboard.blade.php
    ├── auth/
    │   └── login.blade.php
    ├── contacts/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── partners/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── technicians/
    │   ├── index.blade.php
    │   └── show.blade.php
    └── users/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php
```

## Phase 11: Seeders

### Create Seeders:
```bash
docker compose exec php php artisan make:seeder AdminUserSeeder
docker compose exec php php artisan make:seeder DatabaseSeeder
```

### AdminUserSeeder:
```php
// database/seeders/AdminUserSeeder.php
public function run()
{
    AdminUser::create([
        'username' => 'admin',
        'email' => 'admin@mechanicafrica.com',
        'password' => Hash::make('MechAdmin2025!'),
        'role' => 'super_admin',
        'is_active' => true,
    ]);
}
```

### Run Seeders:
```bash
docker compose exec php php artisan db:seed
```

## Phase 12: Assets & Frontend

### Move Assets:
```bash
# Copy images to public directory
cp -r images public/

# Copy CSS/JS
cp styles.css public/css/
cp script.js public/js/
```

### Compile Assets:
```bash
docker compose exec php npm install
docker compose exec php npm run dev
```

## Phase 13: Testing

### Create Tests:
```bash
docker compose exec php php artisan make:test ContactSubmissionTest
docker compose exec php php artisan make:test AdminAuthenticationTest
docker compose exec php php artisan make:test PartnerApplicationTest
```

### Run Tests:
```bash
docker compose exec php php artisan test
```

## Phase 14: Package Installation

```bash
# Excel Export
docker compose exec php composer require maatwebsite/excel

# Permissions Management
docker compose exec php composer require spatie/laravel-permission

# Development Tools
docker compose exec php composer require --dev laravel/telescope
docker compose exec php composer require --dev barryvdh/laravel-debugbar
```

## Phase 15: Data Migration from SQLite

### Export Data:
```bash
# Create CSV exports from SQLite
sqlite3 contacts.db <<EOF
.headers on
.mode csv
.output contacts.csv
SELECT * FROM contacts;
.output partners.csv
SELECT * FROM partners;
.output technicians.csv
SELECT * FROM technicians;
.output admin_users.csv
SELECT * FROM admin_users;
.quit
EOF
```

### Import to MySQL:
Create importers or seeders to load the CSV data into MySQL.

## Quick Start Commands

```bash
# 1. Initial Setup
./setup-laravel.sh

# 2. Create Migrations
docker compose exec php php artisan make:migration create_contacts_table
docker compose exec php php artisan make:migration create_partners_table
docker compose exec php php artisan make:migration create_technicians_table
docker compose exec php php artisan make:migration create_admin_users_table

# 3. Run Migrations
docker compose exec php php artisan migrate

# 4. Create Models
docker compose exec php php artisan make:model Contact
docker compose exec php php artisan make:model Partner
docker compose exec php php artisan make:model Technician
docker compose exec php php artisan make:model AdminUser

# 5. Seed Database
docker compose exec php php artisan db:seed

# 6. Access Application
open http://localhost:9000
```

## Troubleshooting

### Container Issues:
```bash
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Permission Issues:
```bash
docker compose exec php chmod -R 775 storage bootstrap/cache
docker compose exec php chown -R www-data:www-data storage bootstrap/cache
```

### Database Issues:
```bash
docker compose exec mysql mysql -u mechanic_user -p
# Password: mechanic_secure_pass_2025
SHOW DATABASES;
USE mechanic_africa;
SHOW TABLES;
```

## Timeline Estimate

- Phase 1-2: Initial Setup (30 minutes) ✓
- Phase 3-4: Database & Models (1 hour)
- Phase 5: Authentication (1 hour)
- Phase 6-7: Controllers & Routes (2 hours)
- Phase 8-9: Validation & Middleware (1 hour)
- Phase 10: Blade Templates (3 hours)
- Phase 11-12: Seeders & Assets (1 hour)
- Phase 13-14: Testing & Packages (1 hour)
- Phase 15: Data Migration (1 hour)

**Total Estimated Time: 10-12 hours**

## Completion Checklist

- [ ] Docker environment running
- [ ] Laravel installed
- [ ] All migrations created and run
- [ ] All models defined with relationships
- [ ] Admin authentication working
- [ ] All controllers implemented
- [ ] Routes configured
- [ ] Form validation implemented
- [ ] Blade templates created
- [ ] Assets migrated
- [ ] Data imported from SQLite
- [ ] Testing completed
- [ ] Production ready

---

**Ready to start?** Run `./setup-laravel.sh` to begin the Laravel migration!
