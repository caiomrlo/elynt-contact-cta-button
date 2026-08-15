FROM wordpress:php8.3-apache

# Instalação de dependências para o Composer (git, unzip)
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Instalação do Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuração de ambiente para ferramentas globais do Composer
ENV COMPOSER_HOME=/root/.composer
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

# Instalação global do PHP_CodeSniffer e padrões WordPress (WPCS + PHPCompatibility)
RUN composer global config --no-plugins allow-plugins.dealerdirect/phpcodesniffer-composer-installer true \
    && composer global require --no-interaction --prefer-dist \
    squizlabs/php_codesniffer:"^3.10" \
    wp-coding-standards/wpcs:"^3.1" \
    dealerdirect/phpcodesniffer-composer-installer:"^1.0" \
    phpcompatibility/phpcompatibility-wp:"^2.1"

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
