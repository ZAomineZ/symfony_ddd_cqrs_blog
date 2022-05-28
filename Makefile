build:
	$(MAKE) prepare-test
	$(MAKE) analyse
	$(MAKE) tests

it:
	$(MAKE) prepare-dev
	$(MAKE) analyse

.PHONY: translations
translations:
	php bin/console translation:extract --force fr

.PHONY: tests
tests:
	./vendor/bin/simple-

cache:
	php bin/console cache:clear --env=dev
	php bin/console cache:clear --env=test

analyse:
	npm audit
	composer valid
	php bin/console doctrine:schema:valid

phpcs:
	./vendor/bin/phpcbf ./src
	./vendor/bin/phpcs --config-set report_width 120
	./vendor/bin/phpcs --config-set show_warnings 0
	./vendor/bin/phpcs --extensions=php --standard=PSR12 ./src

prepare-dev:
	composer install --prefer-dist
	php bin/console cache:clear --env=dev
	php bin/console doctrine:database:drop --if-exists -f --env=dev
	php bin/console doctrine:database:create --env=dev
	php bin/console doctrine:schema:update -f --env=dev
	php bin/console doctrine:fixtures:load -n --env=dev

prepare-test:
	composer install --prefer-dist
	php bin/console cache:clear --env=test
	php bin/console doctrine:database:drop --if-exists -f --env=test
	php bin/console doctrine:database:create --env=test
	php bin/console doctrine:schema:update -f --env=test
	# php bin/console doctrine:fixtures:load -n --env=test
