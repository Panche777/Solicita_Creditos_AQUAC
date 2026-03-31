# Imagen base con PHP y Apache
FROM php:8.2-apache

# 🔧 Instalar dependencias necesarias (AQUÍ está el fix)
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_sqlite

# Habilitar mod_rewrite
RUN a2enmod rewrite

# Copiar proyecto al contenedor
COPY . /var/www/html/

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Crear archivo SQLite si no existe
RUN touch /var/www/html/database.sqlite \
    && chown www-data:www-data /var/www/html/database.sqlite

# Configurar Apache para usar /public como raíz
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Exponer puerto
EXPOSE 80