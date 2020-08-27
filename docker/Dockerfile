FROM webdevops/php-nginx:5.6

ENV WEB_DOCUMENT_ROOT=/var/www/html

ARG opencart_version
ADD src/$opencart_version.zip /opencart.zip

RUN unzip /opencart.zip -d /tmp && \
    cp -r /tmp/upload/* /var/www/html && \
    cp /var/www/html/config-dist.php /var/www/html/config.php && \
    cp /var/www/html/admin/config-dist.php /var/www/html/admin/config.php && \
    rm -rf /tmp/* && \
    rm -rf /opencart.zip && \
    chown -Rf application.application /var/www

WORKDIR /var/www/html

VOLUME ["/var/www"]
