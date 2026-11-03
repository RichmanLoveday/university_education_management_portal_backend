#!/bin/sh
set -e

# Railway gives the port to listen on through $PORT
PORT="${PORT:-8080}"

# mod_php only works with prefork, so make sure no other MPM is enabled
rm -f /etc/apache2/mods-enabled/mpm_event.* /etc/apache2/mods-enabled/mpm_worker.*
[ -e /etc/apache2/mods-enabled/mpm_prefork.load ] || a2enmod mpm_prefork >/dev/null

sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force

    # Seed the demo data only once, when the database has no users yet
    if [ "${SEED_IF_EMPTY:-true}" = "true" ]; then
        USERS=$(php artisan tinker --execute="echo \App\Models\User::count();" | tail -n 1)
        if [ "$USERS" = "0" ]; then
            php artisan db:seed --force
        fi
    fi
fi

chown -R www-data:www-data storage bootstrap/cache

exec "$@"
