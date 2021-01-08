FROM composer:2

ENV PATH=$COMPOSER_HOME/vendor/bin:$PATH
ENV DRUSH_VERSION_REQUIREMENT='^10.0'

RUN composer global require drush/drush:$DRUSH_VERSION_REQUIREMENT

COPY ./drush /app/drush

CMD ["drush", "pm:security"]
