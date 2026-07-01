#!/bin/sh
set -e

APP_DIR="/var/www/html"

echo "▶ Starting PHP entrypoint..."

###############################################
# 1) Permissions Symfony
###############################################
if [ -d "$APP_DIR/var" ]; then
    echo "▶ Fixing var/ permissions"
    chown -R www-data:www-data "$APP_DIR/var"
fi

###############################################
# 2) Dossiers uploads
###############################################
echo "▶ Ensuring uploads directories exist"
mkdir -p "$APP_DIR/public/uploads"
chown -R www-data:www-data "$APP_DIR/public/uploads"
chmod -R 775 "$APP_DIR/public/uploads"

###############################################
# 3) Composer install si vendor/ absent
###############################################
if [ ! -f "$APP_DIR/vendor/autoload.php" ]; then
    echo "▶ vendor/ missing → running composer install"
    cd "$APP_DIR"
    composer install --no-interaction --optimize-autoloader
else
    echo "✔ vendor/ already present"
fi

###############################################
# 4) Attente Postgres
###############################################
POSTGRES_HOST="${POSTGRES_HOST:-postgres}"
POSTGRES_USER="${POSTGRES_USER:-app}"
POSTGRES_DB="${POSTGRES_DB:-app}"
POSTGRES_PASSWORD="${POSTGRES_PASSWORD:-secret}"

echo "▶ Waiting for Postgres at $POSTGRES_HOST..."

export PGPASSWORD="$POSTGRES_PASSWORD"

until pg_isready -h "$POSTGRES_HOST" -p 5432 -U "$POSTGRES_USER" >/dev/null 2>&1; do
    sleep 1
done

echo "✔ Postgres is ready"

###############################################
# 5) Création base de test si nécessaire
###############################################
if [ "$APP_ENV" = "dev" ] || [ "$APP_ENV" = "test" ]; then
    echo "▶ Ensuring test database exists"

    psql -h "$POSTGRES_HOST" -U "$POSTGRES_USER" -d postgres -v ON_ERROR_STOP=1 <<EOF
SELECT 'CREATE DATABASE ${POSTGRES_DB}_test'
WHERE NOT EXISTS (
    SELECT FROM pg_database WHERE datname = '${POSTGRES_DB}_test'
)\gexec
EOF

    echo "✔ Test database ready"
fi

###############################################
# 6) Lancer la commande finale (php-fpm)
###############################################
echo "▶ Starting PHP-FPM..."
exec "$@"
