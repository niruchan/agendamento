FROM php:8.2-fpm

# 必要なツール（Node.js 20系を指定）をインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP拡張機能（PostgreSQL対応）
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql gd

WORKDIR /var/www

# ファイルをコピー
COPY . .

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev

# 依存関係のインストールとViteのビルド
RUN npm install
RUN npm run build

# 権限の設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 80

# サーバー起動コマンド
# 1. キャッシュクリア 2. マイグレーション強制実行 3. サーバー起動
CMD php artisan config:clear && php artisan migrate --force && php -S 0.0.0.0:80 -t public