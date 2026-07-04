FROM php:8.2-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./


COPY . /var/www/html
RUN touch .env && echo "APP_ENV=prod" > .env

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV PORT=8080

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --classmap-authoritative --no-scripts
EXPOSE 8080
CMD ["sh", "-lc", "php -S 0.0.0.0:${PORT:-8080} -t public"]
