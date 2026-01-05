FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install dependencies
RUN apt-get update && apt-get install -y \
    bash git zip unzip openssl sudo libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pcntl

# Install xdebug
RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Install composer (только один раз)
COPY --from=composer:2.6.6 /usr/bin/composer /usr/local/bin/composer

# КОПИРУЕМ ТОЛЬКО composer файлы для установки зависимостей
WORKDIR /var/www
COPY composer.* ./
# vendor
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Копируем остальной код
COPY . .

# Создаём пользователя ПОСЛЕ composer install (чтобы не было правовых проблем)
RUN useradd -G www-data,root,sudo -u $uid -p $(openssl passwd -1 '123') -d /home/$user $user \
    && mkdir -p /home/$user/.composer \
    && chown -R $user:$user /home/$user /var/www

USER $user
WORKDIR /var/www

EXPOSE 9000
