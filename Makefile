# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
SYM_CONT = $(DOCKER_COMP) exec symfony

# Executables
PHP      = $(SYM_CONT) php
COMPOSER = $(SYM_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
dc-install: dc-down dc-start vendor db-reset ## Install project

dc-build: ## Builds the Docker images
	@$(DOCKER_COMP) build

dc-up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up -d --remove-orphans

dc-start: dc-build dc-up ## Build and start the containers

dc-down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

dc-re: ## Restart the docker hub
	@$(DOCKER_COMP) down --remove-orphans
	@$(DOCKER_COMP) up -d --remove-orphans

dc-logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

dc-sh: ## Connect to the PHP FPM container
	@$(SYM_CONT) bash

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

db-reset: db-drop db-create db-update
db-reset-f: db-reset db-fixtures-append

db-create: ##  Create database
	@$(SYMFONY) doctrine:database:create --if-not-exists

db-drop: ##  Drop database
	@$(SYMFONY) doctrine:database:drop --if-exists --force

db-update: ## Update database
	@$(SYMFONY) doctrine:schema:update --force --dump-sql --complete

db-fixtures-append: ##  Append fixtures
	@$(SYMFONY) doctrine:fixture:load -q --append


db-fixtures: ##  Append fixtures
	@$(SYMFONY) doctrine:fixture:load -q

validate-schema: ## Valid doctrine mapping
	@$(SYMFONY) doctrine:schema:validate --skip-sync

## —— PHP 🐘 ——————————————————————————————————————————————————————————————————

test: ## Test phpunit
	@$(SYM_CONT) ./vendor/phpunit/phpunit/phpunit tests

analyse-php: ## Analyse php
	@$(SYM_CONT) ./vendor/bin/phpstan analyse -c phpstan.neon --memory-limit 1G

lint-php: ## Lint php
	@$(SYM_CONT) ./vendor/bin/php-cs-fixer fix --dry-run --diff

lint-twig:
	@$(SYM_CONT) ./vendor/bin/twig-cs-fixer lint --fix templates

fix-php: ## Fix php
	@$(SYM_CONT) ./vendor/bin/php-cs-fixer fix

check: fix-php analyse-php validate-schema lint-twig
check-t: fix-php analyse-php validate-schema lint-twig test

## —— App ———————————————————————————————————————————————————————————————————

sync-dev:
	@$(SYMFONY) app:sync -vvv

sync:
	@$(SYMFONY) app:sync -vv --no-debug --env=prod # --no-debug AND --env=prod is for performance