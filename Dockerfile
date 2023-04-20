FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony


EXPOSE ${PORT}

CMD ["sh", "-c", "symfony serve --no-tls --port=${APP_PORT} --allow-http"]
