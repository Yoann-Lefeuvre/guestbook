# Utilisez l'image PHP 8.2 FPM comme image de base
FROM php:8.2-fpm

# Installez les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \              
    && docker-php-ext-install pdo pdo_pgsql pgsql
    
 # Nettoyez les fichiers temporaires pour réduire la taille de l'image
# RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Décommenter les extensions dans php.ini
# RUN sed -i 's/;extension=pgsql/extension=pgsql/' /usr/local/etc/php/php.ini \
#    && sed -i 's/;extension=pdo_pgsql/extension=pdo_pgsql/' /usr/local/etc/php/php.ini

# Copiez votre fichier php.ini personnalisé dans le conteneur
COPY ./php.ini /usr/local/etc/php/php.ini

# Assurez-vous que PHP est installé
RUN php -v