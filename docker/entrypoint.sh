#!/bin/sh
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is not set. Generate one with: php artisan key:generate --show" >&2
  exit 1
fi

# أوامر artisan تُشغَّل بمستخدم www-data حتى لا تصبح ملفات السجلات/الكاش مملوكة لـ root
as_app() { runuser -u www-data -- "$@"; }

# المجلدات المطلوبة (قد تكون فارغة إذا رُبط volume على storage)
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
         storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# رابط الصور العامة: public/storage -> storage/app/public
[ -e public/storage ] || as_app php artisan storage:link --quiet || true

as_app php artisan config:cache
as_app php artisan route:cache
as_app php artisan view:cache

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  # قاعدة البيانات قد لا تكون جاهزة لحظة الإقلاع؛ نعيد المحاولة 30 مرة
  i=0
  until as_app php artisan migrate --force; do
    i=$((i + 1))
    if [ "$i" -ge 30 ]; then
      echo "ERROR: migrations failed after 30 attempts (check DB_* variables)." >&2
      exit 1
    fi
    echo "Database not ready, retrying in 2s ($i/30)..." >&2
    sleep 2
  done
fi

exec "$@"
