run:
	docker-compose -f deployments/docker-compose.yml --env-file deployments/.env up -d --build --remove-orphans

down:
	docker-compose -f deployments/docker-compose.yml --env-file deployments/.env down

migrations-diff:
	docker exec -it advertising_php ./vendor/bin/doctrine-migrations diff

migrations:
	docker exec -it advertising_php ./vendor/bin/doctrine-migrations migrate

unit-tests:
	docker exec -it advertising_php ./vendor/bin/phpunit tests

stan:
	docker exec -it advertising_php ./vendor/bin/phpstan analyse src

swagger:
	docker exec -it advertising_php ./vendor/bin/openapi ./src ./api > api/api.yml
