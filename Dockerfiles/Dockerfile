# Dockerfile
FROM nginx:alpine

# Copie du fichier nginx.conf dans le conteneur
COPY nginx.conf /etc/nginx/nginx.conf

# Copie du code source de ton application dans le dossier du conteneur
COPY ../guestbook /var/www/symfony

# Exposer le port 80 pour Nginx
EXPOSE 80