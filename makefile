run: build up cache-clear

test: bin/phpunit tests/Unit/

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