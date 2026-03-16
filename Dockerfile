FROM php:8.2-fpm

# 必要なツール（Node.js含む）をインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# PHP拡張機能を入れる
RUN docker-php-ext-install pdo_mysql gd

# 作業ディレクトリ設定
WORKDIR /var/www

# ファイルをコピー
COPY . .

# Composerとnpmで中身を組み立てる
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev
RUN npm install
RUN npm run build

# 権限の設定
# ...中略...
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 🌟 ポート80を開放
EXPOSE 80

# 🌟 サーバー起動コマンドをこれに書き換え
CMD php artisan migrate --force && php -S 0.0.0.0:80 -t public