COMPOSE       ?= docker compose
COMPOSE_DEV   ?= docker compose -f docker-compose.dev.yml
APP           ?= $(COMPOSE) exec app

.PHONY: up down sh logs build rebuild migrate fresh seed test pint \
        dev-up dev-down

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

sh:
	$(APP) sh

logs:
	$(COMPOSE) logs -f --tail=100

build:
	$(COMPOSE) build

rebuild:
	$(COMPOSE) build --no-cache

migrate:
	$(APP) php artisan migrate --force

fresh:
	$(APP) php artisan migrate:fresh --seed --force

seed:
	$(APP) php artisan db:seed --force

test:
	$(APP) php artisan test --compact

pint:
	$(APP) vendor/bin/pint --format agent

dev-up:
	$(COMPOSE_DEV) up -d

dev-down:
	$(COMPOSE_DEV) down
