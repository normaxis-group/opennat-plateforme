# Plateforme OpenNAT (IRICE) : même socle que les autres sites du groupe (serversideup, Coolify sur SERV2).
# L'image ne lance ni npm (public/build est versionné), ni les migrations, ni la mise en cache.
# Le moteur de calcul tourne à part (application Coolify « opennat », mode public, non exposée) ;
# la plateforme le joint par OPENNAT_MOTEUR_URL sur le réseau interne.
ARG PHP_VERSION=8.4
FROM serversideup/php:${PHP_VERSION}-fpm-nginx
USER root
RUN install-php-extensions intl bcmath
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
# Import d'un RSEE de plusieurs mégaoctets par l'outil : le moteur accepte 25 Mo.
ENV PHP_POST_MAX_SIZE=30M PHP_UPLOAD_MAX_FILE_SIZE=30M PHP_MEMORY_LIMIT=256M
USER www-data
