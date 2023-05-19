FROM php:8.2.4-apache

# Копируем файлы приложения в контейнер
COPY . /var/www/html

# Устанавливаем зависимости приложения, если необходимо
# RUN composer install

# Опционально, можно добавить дополнительные настройки Apache
# COPY apache-config.conf /etc/apache2/sites-available/000-default.conf

# Указываем рабочую директорию
WORKDIR /var/www/html

# Открываем порт для доступа к приложению
EXPOSE 80

# Команда, которая будет запускаться при старте контейнера
CMD ["apache2-foreground"]