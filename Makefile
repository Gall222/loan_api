# путь к папке Docker'а
DOCKER_FOLDER_PATH=docker
# путь к .env
ENV=--env-file ${DOCKER_FOLDER_PATH}/.env
# путь к docker compose.yml
COMPOSE=--file ${DOCKER_FOLDER_PATH}/docker-compose.yml
# аргументы переданные вместе с вызовом инструкции
ARGS=$(filter-out $@, $(MAKECMDGOALS))
# чтобы аргументы не воспринимались как make команды
%::
	@true

init: prepare-env build up composer-install migrate

prepare-db: dump-db restore-db

set-default-config: backup-main-config copy-default-config	

prepare-env:
	cp ${DOCKER_FOLDER_PATH}/.env.example ./docker/.env

build:
	docker compose $(COMPOSE) $(ENV) build

up:
	docker compose $(COMPOSE) $(ENV) up -d

down:
	docker compose $(COMPOSE) $(ENV) down

ps:
	docker compose $(COMPOSE) $(ENV) ps

shell-php:
	docker compose $(COMPOSE) $(ENV) exec php bash

shell-nginx:
	docker compose $(COMPOSE) $(ENV) exec nginx bash

shell-db:
	docker compose $(COMPOSE) $(ENV) exec db bash

migrate:
	docker compose $(COMPOSE) $(ENV) exec php bash -c "php yii migrate --interactive=0"

migrate-down:
	docker compose $(COMPOSE) $(ENV) exec php bash -c "php yii migrate/down $(ARGS)"

migrate-create:
	docker compose $(COMPOSE) $(ENV) exec php bash -c "php yii migrate/create $(ARGS)"

composer-install:
	docker compose $(COMPOSE) $(ENV) exec php bash -c "composer install --prefer-dist"
