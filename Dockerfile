FROM wordpress:php8.3-apache

# Configurações de PHP recomendadas para ambiente de desenvolvimento local
RUN { \
        echo 'upload_max_filesize = 64M'; \
        echo 'post_max_size = 64M'; \
        echo 'memory_limit = 256M'; \
        echo 'max_execution_time = 300'; \
        echo 'display_errors = On'; \
        echo 'display_startup_errors = On'; \
        echo 'error_reporting = E_ALL'; \
    } > /usr/local/etc/php/conf.d/dev-php.ini
