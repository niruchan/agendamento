FROM php:8.2-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl

# PHP拡張のインストール
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www
COPY . .

# --- ここから「上書き」の重要設定 ---
# 1. もともと入っているNginxのデフォルト設定を削除
RUN rm /etc/nginx/sites-enabled/default

# 2. 自分の作った nginx.conf を「正解」として配置（上書き）
COPY nginx.conf /etc/nginx/sites-enabled/default
# --- ここまで ---

# ライブラリのインストール
RUN composer install --no-dev --optimize-autoloader

# 権限の設定
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 公開ポートの設定
EXPOSE 80

# サーバー起動設定
CMD service nginx start && php-fpm -D && tail -f /dev/null