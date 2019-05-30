.PHONY: start
start: erase composer up dbReset

.PHONY: stop
stop: ## stop environment
		docker-compose stop

.PHONY: dbReset
dbReset: ## stop environment
		docker-compose exec php php bin/console d:d:d --if-exists --force
		docker-compose exec php php bin/console d:d:c
		docker-compose exec php php bin/console d:s:c
		docker-compose exec php php bin/console p:c:d
		docker-compose exec php php bin/console e:e:c


.PHONY: down
down: ## stop environment
		docker-compose down

.PHONY: erase
erase: stop

.PHONY: up
up: ## spin up environment
		docker-compose up -d

.PHONY: composer
composer: ## spin up environment
		docker-compose run composer composer install --ignore-platform-reqs

.PHONY: php
php: ## spin up environment
		docker-compose exec php /bin/bash

.PHONY: style
style: ## executes php analizers
		docker-compose exec php ./vendor/bin/phpstan analyse -l 7 -c phpstan.neon src

.PHONY: cs
cs: ## executes php analizers
		docker-compose exec php ./vendor/bin/php-cs-fixer fix --allow-risky=yes

.PHONY: layer
layer: ## layer
		docker-compose exec php ./vendor/bin/deptrac

.PHONY: phpunit
phpunit: ## layer
		docker-compose exec php ./vendor/bin/phpunit

.PHONY: test
test: cs layer style phpunit