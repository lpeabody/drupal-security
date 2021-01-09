FROM composer:2

ENV PATH=$COMPOSER_HOME/vendor/bin:$PATH

RUN composer global require drush/drush:^10.0

COPY drush /app/drush

CMD ["drush", "pm:security"]
