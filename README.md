# Drupal Security Check

This is a Drupal package security scanning tool. It uses Drush's `pm:security`
scanning tool to inspect your project's composer.json and composer.lock files
for flagged packages, using a list published by Drupal.org.

Usage:

```
docker run --rm \
    -v $PWD/composer.json:/app/composer.json \
    -v $PWD/composer.lock:/app/composer.lock \
    lpeabody/drupal-security
```

## Whitelisting packages

You may optionally whitelist packages by passing a comma-separated list of
Composer package names (e.g. drupal/group, drupal/lightning, etc.) using the
`--allowed` argument.

Usage:

```
docker run --rm \
    -v $PWD/composer.json:/app/composer.json \
    -v $PWD/composer.lock:/app/composer.lock \
    lpeabody/drupal-security drush pm:security --allowed=drupal/group:1.0.0-rc5
```
