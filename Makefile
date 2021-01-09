
build:
	docker build --pull -t lpeabody/drupal-security:latest .

push:
	docker push lpeabody/drupal-security:latest
