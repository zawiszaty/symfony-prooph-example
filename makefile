.PHONY: start
start: erase up

.PHONY: stop
stop: ## stop environment
		docker-compose stop

.PHONY: down
down: ## stop environment
		docker-compose down

.PHONY: erase
erase: stop down

.PHONY: up
up: ## spin up environment
		docker-compose up -d

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
		docker-compose exec php ./vendor/bin/phpunit --coverage-clover build/logs/clover.xml

.PHONY: test
test: cs layer style phpunit