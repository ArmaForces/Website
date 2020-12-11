setup:
	docker-compose exec php php bin/console doctrine:database:drop --if-exists --force
	docker-compose exec php php bin/console doctrine:database:create --if-not-exists

	docker-compose exec php php bin/console doctrine:migration:migrate --no-interaction

	docker-compose exec php php bin/console app:import:modlists

admin:
	docker-compose exec php php bin/console app:permissions:make-admin

cs:
	docker-compose exec php php bin/console lint:twig templates/
	docker-compose exec php php bin/console lint:yaml --parse-tags config/
	docker-compose exec php php bin/console doctrine:schema:validate
	docker-compose exec php php bin/console doctrine:mapping:info

	docker run -it --rm -v $$PWD:/project -w /project jakzal/phpqa:1.42-php7.4-alpine php-cs-fixer fix
	docker run -it --rm -v $$PWD:/project -w /project jakzal/phpqa:1.42-php7.4-alpine phpstan analyse

test:
	docker-compose exec php php bin/phpunit --testdox
