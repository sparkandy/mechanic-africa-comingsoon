# Use nginx with PHP support
FROM webdevops/php-nginx:8.2-alpine

# Copy the website files
COPY . /app/

# Copy custom nginx configuration
COPY custom-vhost.conf /opt/docker/etc/nginx/vhost.conf

# Set working directory
WORKDIR /app

# Expose port 80
EXPOSE 80

# Set environment variables
ENV WEB_DOCUMENT_ROOT="/app"