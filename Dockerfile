# syntax=docker/dockerfile:1

# ── المرحلة 1: تثبيت حزم Composer ───────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# ── المرحلة 2: صورة التشغيل (Apache + PHP 8.3 على المنفذ 8080) ──────
FROM php:8.3-apache

RUN apt-get update \
 && apt-get install -y --no-install-recommends \
      libfreetype6-dev libjpeg62-turbo-dev libpng-dev libwebp-dev libzip-dev libpq-dev \
 && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" gd pdo_mysql pdo_pgsql zip opcache \
 && a2enmod rewrite headers \
 && rm -rf /var/lib/apt/lists/*

# Apache: المنفذ 8080 وجذر الموقع public/ فقط (لا يُكشف .env ولا الكود)
RUN sed -ri 's/Listen 80$/Listen 8080/' /etc/apache2/ports.conf \
 && sed -ri 's#<VirtualHost \*:80>#<VirtualHost *:8080>#' /etc/apache2/sites-available/000-default.conf \
 && sed -ri 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
 && printf '%s\n' \
      'ServerName localhost' \
      'ServerTokens Prod' \
      'ServerSignature Off' \
      '<Directory /var/www/html/public>' \
      '    AllowOverride All' \
      '    Require all granted' \
      '</Directory>' \
      > /etc/apache2/conf-available/laravel.conf \
 && a2enconf laravel

# PHP: إعدادات الإنتاج + حدود الرفع (الصور حتى 4MB)
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php.ini "$PHP_INI_DIR/conf.d/zz-app.ini"

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-interaction \
 && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

# قيم افتراضية آمنة؛ منصة الاستضافة تتجاوزها عبر Environment variables
ENV APP_ENV=production \
    APP_DEBUG=false \
    APP_LOCALE=ar \
    LOG_CHANNEL=stderr \
    SESSION_DRIVER=database \
    CACHE_DRIVER=file

EXPOSE 8080
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
  CMD php -r "exit(@file_get_contents('http://127.0.0.1:8080/healthz') === 'ok' ? 0 : 1);"

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
