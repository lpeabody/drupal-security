FROM composer:2

ENV PATH=$COMPOSER_HOME/vendor/bin:$PATH

RUN composer global require drush/drush:^10.0

CMD ["drush", "pm:security"]
