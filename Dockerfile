FROM php:8.2-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    sqlite3 \
    libsqlite3-dev

# PHP拡張のインストール（sqlite用を追加）
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_sqlite

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www
COPY . .

# Nginxの設定上書き
RUN rm -f /etc/nginx/sites-enabled/default
COPY nginx.conf /etc/nginx/sites-enabled/default

# ライブラリのインストール
RUN composer install --no-dev --optimize-autoloader

# 権限の設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 公開ポートの設定
EXPOSE 80

# --- ここを修正しました ---
# 起動時にデータベース作成とサーバー起動をセットで行う
CMD service nginx start && \
    mkdir -p database && \
    touch database/database.sqlite && \
    chmod -R 777 database && \
    php artisan migrate --force && \
    php-fpm