# Usando a imagem oficial do PHP com o Apache
FROM php:8.2.11-apache

# Instalar extensões necessárias e dependências
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install zip pdo pdo_mysql

# Instalar o Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Habilitar o módulo de reescrita do Apache
RUN a2enmod rewrite

# Copiar o código da aplicação para o contêiner
COPY . /var/www/html

# Defina o diretório onde os arquivos serão armazenados
WORKDIR /var/www/html

# Definir variável de ambiente para permitir plugins
ENV COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependências do Symfony
RUN composer install --no-interaction

# Configurar permissões adequadas
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Adicionar configuração do Apache
COPY ./apache-config.conf /etc/apache2/sites-available/000-default.conf

# Expor a porta 80
EXPOSE 80
