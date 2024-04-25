FROM node:20 as builder-frontend

# Set working directory
WORKDIR /app


# Copy project folder
COPY frontend /app

# Build Angular project
RUN npm i && npm run build

FROM node:20

RUN curl -sSL https://packages.sury.org/php/README.txt | sudo bash -x \
    && apt-get update \
    && apt-get install -y supervisor nginx php8.1 php8.1-fpm php8.1-slim
RUN mkdir -p /var/log/supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy projects
COPY --from=builder-frontend /app/dist/tp02_ay_derya/browser/ /var/www/html
COPY backend /var/www/html/api

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /etc/php/8.1/fpm/php-fpm.conf

EXPOSE 80

CMD ["/usr/bin/supervisord"]