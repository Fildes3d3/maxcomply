reset-test-db:
	@docker-compose up -d maxcomply-fpm-php  || true
	docker exec maxcomply-fpm-php  bin/console doctrine:database:drop --force --if-exists --env=test
	docker exec maxcomply-fpm-php  bin/console doctrine:database:create --env=test
	docker exec maxcomply-fpm-php  bin/console doctrine:migrations:migrate --no-interaction --env=test

qa:
	@docker-compose up -d maxcomply-fpm-php  || true
	docker exec maxcomply-fpm-php  php bin/phpunit

fix:
	@docker-compose up -d maxcomply-fpm-php || true
	docker exec -it maxcomply-fpm-php sh -c "tools/php-cs-fixer/vendor/bin/php-cs-fixer fix --stop-on-violation --using-cache=no src & vendor/bin/phpstan analyse -l 1 -c phpstan.neon src & wait"
