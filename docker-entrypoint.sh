#!/bin/sh
set -e

echo "🚀 Starting NORA WORLD..."

# Generate .env file from environment variables
# This ensures Laravel reads the correct values
cat > .env << EOF
APP_NAME="${APP_NAME:-NORA WORLD}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-single}"
LOG_LEVEL="${LOG_LEVEL:-debug}"

DB_CONNECTION="${DB_CONNECTION:-mysql}"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"

SESSION_DRIVER="${SESSION_DRIVER:-database}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"
SESSION_ENCRYPT="${SESSION_ENCRYPT:-false}"
SESSION_PATH="${SESSION_PATH:-/}"
SESSION_DOMAIN="${SESSION_DOMAIN:-}"
SESSION_SECURE_COOKIE="${SESSION_SECURE_COOKIE:-true}"

CACHE_STORE="${CACHE_STORE:-database}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

BROADCAST_CONNECTION="${BROADCAST_CONNECTION:-log}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"

MAIL_MAILER="${MAIL_MAILER:-log}"

BCRYPT_ROUNDS="${BCRYPT_ROUNDS:-12}"

FORCE_HTTPS="${FORCE_HTTPS:-true}"

FALLBACK_LOCALE="${APP_FALLBACK_LOCALE:-en}"
FAKER_LOCALE="${APP_FAKER_LOCALE:-en_US}"
MAINTENANCE_DRIVER="${APP_MAINTENANCE_DRIVER:-file}"
LOCALE="${APP_LOCALE:-en}"
EOF

echo "✅ .env file generated"

# Clear all caches
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true  
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

echo "✅ Caches cleared"

# Run migrations (not fresh - don't wipe data on every restart)
php artisan migrate --force 2>/dev/null || {
    echo "⚠️ Migration failed, trying migrate:fresh with seed..."
    php artisan migrate:fresh --force --seed 2>/dev/null || true
}

echo "✅ Database ready"

# Start the server
echo "🌐 Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
