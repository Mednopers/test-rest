DOCKER_EXEC = docker-compose exec php-cli
DOCKER_RUN = docker-compose run --rm php-cli

.DEFAULT_GOAL := help
.PHONY : help
help : Makefile
	@sed -n 's/^##//p' $<

##
## Docker
##---------------------------------------------------------------------------
.PHONY: up down

## up			: Mount the dev containers and run dev server
up:
	docker-compose up -d

## down			: Stops, remove the containers
down:
	docker-compose down

##
## Tools
##---------------------------------------------------------------------------
.PHONY: init db-migrate server-run sh consume

## init			: Init environment, install dependencies, create database
init:
	@if [ -f .env ]; \
	then\
		echo '\033[1;41m/!\ The .env.dist file has changed. Please check your .env file (this message will not be displayed again).\033[0m';\
		touch .env;\
	else\
		echo cp .env.dist .env;\
		cp .env.dist .env;\
	fi
	cp docker-compose.override.yml.dist docker-compose.override.yml
	$(MAKE) up
	$(DOCKER_EXEC) composer install
	$(DOCKER_EXEC) bin/console --no-interaction doctrine:database:drop --if-exists --force
	$(DOCKER_EXEC) bin/console --no-interaction doctrine:database:create
	$(MAKE) db-migrate

## db-migrate		: Run db migrations
db-migrate:
	$(DOCKER_EXEC) bin/console doctrine:migrations:migrate --no-interaction

## server-run		: Run dev server
server-run:
	$(DOCKER_EXEC) php -S 0.0.0.0:80 -t public

## sh			: Access the php container via shell
sh:
	$(DOCKER_EXEC) sh