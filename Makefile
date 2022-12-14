start:
	php artisan serve --host 0.0.0.0

start-frontend:
	npm run build

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm install && npm run build

migrate:
	php artisan migrate

console:
	php artisan tinker

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

test-coverage:
	XDEBUG_MODE=coverage php artisan test --coverage-clover build/logs/clover.xml

deploy:
	git push heroku

lint:
	composer phpcs

lint-fix:
	composer phpcbf