run: build up cache-clear

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

bash:
	docker exec -it ak_invoice_php /bin/bash
	
db-bash:
	docker exec -it ak_invoice_mysql /bin/bash

cache-clear:
	docker exec -it ak_invoice_php bin/console cache:clear

test: phpcs unit-tests integration-tests

unit-tests:
	docker exec -it ak_invoice_php vendor/bin/phpunit -c phpunit.xml --testdox --testsuite unit

integration-tests:
	docker exec -it ak_invoice_php vendor/bin/phpunit -c phpunit.xml --testdox --testsuite integration

phpstan-run:
	docker exec -it ak_invoice_php vendor/bin/phpstan analyse -c phpstan.neon

phpcs:
	docker exec -it ak_invoice_php vendor/bin/phpcs

phpcs-fix:
	docker exec -it ak_invoice_php vendor/bin/phpcbf