#!/bin/bash

# Mechanic Africa - Laravel Setup Script
# This script will initialize a fresh Laravel installation

set -e

echo "🚀 Starting Mechanic Africa Laravel Setup..."
echo "================================================"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Backup existing files
echo "📦 Backing up existing files..."
mkdir -p laravel-migration-backup
cp -r images laravel-migration-backup/ 2>/dev/null || true
cp contacts.db laravel-migration-backup/ 2>/dev/null || true

# Stop existing Docker containers
echo "🛑 Stopping existing Docker containers..."
docker compose down 2>/dev/null || true

# Create Laravel project in a temporary directory
echo "📥 Creating Laravel project..."
if [ ! -d "laravel-app" ]; then
    composer create-project --prefer-dist laravel/laravel laravel-app "^10.0"
fi

# Move Laravel files to current directory
echo "📂 Setting up Laravel structure..."
# Move all Laravel files to root
cp -r laravel-app/* .
cp laravel-app/.* . 2>/dev/null || true

# Restore images
echo "🖼️ Restoring images..."
cp -r laravel-migration-backup/images public/ 2>/dev/null || true

# Clean up
rm -rf laravel-app

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 775 storage bootstrap/cache

# Create .env file if it doesn't exist
if [ ! -f ".env" ]; then
    echo "⚙️ Creating .env file..."
    cp .env.example .env
fi

# Update .env for Docker MySQL
echo "🔧 Configuring environment..."
cat > .env << EOF
APP_NAME="Mechanic Africa"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:9000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=mechanic_africa
DB_USERNAME=mechanic_user
DB_PASSWORD=mechanic_secure_pass_2025

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@mechanicafrica.com"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="\${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="\${PUSHER_HOST}"
VITE_PUSHER_PORT="\${PUSHER_PORT}"
VITE_PUSHER_SCHEME="\${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="\${PUSHER_APP_CLUSTER}"
EOF

echo "🔑 Generating application key..."
php artisan key:generate 2>/dev/null || echo "Will generate key after Docker is up"

echo "🐳 Building Docker containers..."
docker compose build

echo "🚀 Starting Docker containers..."
docker compose up -d

echo "⏳ Waiting for MySQL to be ready..."
sleep 10

echo "📊 Running migrations..."
docker compose exec -T php php artisan key:generate || true
docker compose exec -T php php artisan migrate:fresh || echo "Migrations will be created in next step"

echo ""
echo "✅ Laravel setup complete!"
echo "================================================"
echo "🌐 Application URL: http://localhost:9000"
echo "🗄️  MySQL Port: 3307"
echo "📝 Database: mechanic_africa"
echo "👤 DB User: mechanic_user"
echo "🔑 DB Password: mechanic_secure_pass_2025"
echo ""
echo "📋 Next steps:"
echo "1. Run: docker compose exec php php artisan migrate"
echo "2. Run: docker compose exec php php artisan db:seed"
echo "3. Visit: http://localhost:9000"
echo ""
echo "🛠️  Useful commands:"
echo "   docker compose exec php bash          # Access PHP container"
echo "   docker compose exec php php artisan   # Run artisan commands"
echo "   docker compose logs -f                # View logs"
echo "   docker compose down                   # Stop containers"
echo ""
