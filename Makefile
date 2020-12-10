setup:
	docker-compose exec php php php bin/console doctrine:database:drop --if-exists --force
	docker-compose exec php php php bin/console doctrine:database:create --if-not-exists

	docker-compose exec php php php bin/console doctrine:migration:migrate --no-interaction

	docker-compose exec php php php bin/console app:import:modlists

admin:
	docker-compose exec php php php bin/console app:permissions:make-admin

cs:
	docker-compose exec php php bin/console lint:twig templates/
	docker-compose exec php php bin/console lint:yaml --parse-tags config/
	docker-compose exec php php bin/console doctrine:schema:validate
	docker-compose exec php php bin/console doctrine:mapping:info

	docker run -it --rm -v $$PWD:/app -w /app oskarstark/php-cs-fixer-ga:latest
	docker run -it --rm -v $$PWD:/app -w /app phpstan/phpstan:latest analyse
