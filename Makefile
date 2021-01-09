# Default values
env = dev

ifeq ($(ci),true)
    dryrun = --dry-run
endif

cs:
	docker run --rm -v $(CURDIR):/project -w /project jakzal/phpqa:1.42-php7.4-alpine php-cs-fixer fix ${dryrun}
	docker run --rm -v $(CURDIR):/project -w /project jakzal/phpqa:1.42-php7.4-alpine phpstan analyse

	docker-compose exec -T php php bin/console lint:twig templates/
	docker-compose exec -T php php bin/console lint:yaml --parse-tags config/
	docker-compose exec -T php php bin/console doctrine:schema:validate --env=${env}
	docker-compose exec -T php php bin/console doctrine:mapping:info --env=${env}

admin:
	docker-compose exec php php bin/console app:permissions:make-admin

db:
	docker-compose exec -T php php bin/console doctrine:database:drop --if-exists --force --env=${env}
	docker-compose exec -T php php bin/console doctrine:database:create --if-not-exists --env=${env}
	docker-compose exec -T php php bin/console doctrine:migration:migrate --no-interaction --env=${env}

setup: db
	docker-compose exec -T php php bin/console app:import:modlists

test:
	make db env=test
	make test

test-ci:
	docker-compose exec -T php php bin/console doctrine:fixtures:load --no-interaction --env=test

	docker-compose exec -T php php bin/phpunit --testdox
