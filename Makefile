include .env

# Инициализация проекта
init:
	# Очистить данные если они есть
	@make clear-data
	# Создать файлы конфигурации SSL
	# @make generate-keys
	# Создает файл .env, если он отсутствует, создает ссылку
	@make env
	# Строит и запускает контейнеры в фоновом режиме
	docker compose up -d
	# Устанавливает зависимости проекта
	docker compose exec laravel composer install
	# Устанавливает Filament
	docker compose exec laravel php artisan filament:install --scaffold --tables --forms
	# Очищает базу данных и запускает миграции
	@make fresh
	# Создает администратора
	@make seed-admin
	# Заполнение бд тестовыми данными
	@make seed
	# Запускает воркеров в фоновом режиме
	docker compose --profile workers up -d
	sleep 5s
	# Приостанавливает тестировочный супервайзер
	@make stop-test-horizon
	# Генерирует ключ приложения
	docker compose exec laravel php artisan key:generate
	# Создает символические ссылки для директории storage
	docker compose exec laravel php artisan storage:link
	# Устанавливает права доступа для директории storage
	docker compose exec laravel chmod -R 777 storage bootstrap/cache

# Запуск контейнеров
up:
	# Запускает все сервисы в фоновом режиме
	docker compose up -d
	docker compose --profile workers up -d
	sleep 5s
	# Приостанавливает тестировочный супервайзер
	@make stop-test-horizon

# Остановка контейнеров
stop:
	docker compose stop

# Остановка и удаление контейнеров
down:
	docker compose --profile "*" down --remove-orphans

# Остановка и удаление контейнеров с томами
down-v:
	docker compose down --remove-orphans --volumes

# Удаление проекта
destroy:
	# Останавливает и удаляет контейнеры, образы, тома и орфаны
	docker compose --profile "*" down --rmi all --volumes --remove-orphans

# Перезапуск контейнеров
restart:
	@make down
	@make up

# Пересоздание проекта
remake:
	@make destroy
	@make init

# Запуск миграций
migrate:
	docker compose exec laravel php artisan migrate

# Заполнение базы данных тестовыми данными
seed:
	docker compose exec laravel php artisan db:seed

# Заполнение базы данных тестовыми данными, включая администратора
seed-admin:
	docker compose exec laravel php artisan db:seed --class=AdminSeeder

# Очистка базы данных и запуск миграций
fresh:
	docker compose exec laravel php artisan migrate:fresh

# Очистка базы данных, запуск миграции, запуск главных сидов и вида админа
refresh:
	@make fresh
	@make seed
	@make seed-admin

# Запуск тестов
test:
	docker compose exec laravel php artisan test

# Вывод логов для всех контейнеров
logs-all:
	docker compose logs

# Вывод логов для всех контейнеров с отслеживанием вывода
watch-all:
	docker compose logs --follow

# Вывод статуса Horizon
horizon-status:
	docker compose exec horizon php artisan horizon:status

# Приостановка Horizon
horizon-pause:
	docker compose exec horizon php artisan horizon:pause

# Возобновление работы Horizon
horizon-continue:
	docker compose exec horizon php artisan horizon:continue

# Приостанавливает тестировочный супервайзер
stop-test-horizon:
	docker compose exec horizon php artisan horizon:pause-supervisor supervisor-test

# Создание файла .env, если он отсутствует
env:
	# Проверяет, существует ли файл .env
	@if [ ! -f .env ]; then \
	  # Копирует файл .env.example в .env
	  cp .env.example .env; \
	fi

# Открыть консоль MySQL
mysql:
	docker compose exec db mysql -u root

# Сделать дамп базы данных MySQL
mysqldump:
	docker compose exec db mysqldump -u root ${DB_DATABASE} > ${DB_DATABASE}.sql

# Открыть консоль PostgreSQL
psql:
	 sudo docker compose exec db psql -h ${DB_HOST} -p ${DB_PORT} -d ${DB_DATABASE} -U ${DB_USERNAME}

# Сделать дамп базы данных PostgreSQL
pgdump:
	sudo docker compose exec db pg_dump -h ${DB_HOST} -p ${DB_PORT} -d ${DB_DATABASE} -U ${DB_USERNAME} > backups/${DB_DATABASE}_$(shell date +"%d-%m-%Y-%H:%M:%S").dump

# Сгенерировать секретный ключ JWT
jwt:
	docker compose exec laravel php artisan jwt:secret

# Показать запущенные контейнеры
ps:
	docker compose ps

# Откатить базу данных к начальному состоянию
rollback-test:
	docker compose exec laravel php artisan migrate:fresh
	docker compose exec laravel php artisan migrate:refresh

# Подготовить приложение к продакшену
prepare:
	@make optimize
	@make cache

# Очистить кэш и оптимизированные файлы
clear:
	@make optimize-clear
	@make cache-clear

# Оптимизировать приложение
optimize:
	docker compose exec laravel php artisan optimize
# Очистить оптимизированные файлы
optimize-clear:
	docker compose exec laravel php artisan optimize:clear
# Закешировать приложение
cache:
	docker compose exec laravel composer dump-autoload -o
	@make optimize
	docker compose exec laravel php artisan event:cache
	docker compose exec laravel php artisan view:cache
# Очистить кэш
cache-clear:
	docker compose exec laravel composer clear-cache
	@make optimize-clear
	docker compose exec laravel php artisan event:clear
# Сгенерировать автозагрузчик Composer
dump-autoload:
	docker compose exec laravel composer dump-autoload

# Связать файл .env с контейнерами Laravel
env:
	rm -rf ./laravel/.env
	ln .env ./laravel

# Открыть консоль Redis
redis:
	docker compose exec redis redis-cli

# Проверить, работает ли приложение
check:
	curl -s -o /dev/null -w "%{http_code}\n" http://localhost

# Создать резервную копию базы данных и файлов приложения
backup:
	tar -czvf backups/$(shell date +"%d-%m-%Y-%H:%M:%S").tar.gz \
	--exclude=backups/* \
	--exclude=laravel/vendor/* \
	--exclude=docker/data/* \
	* \
	&& \
	make pg_dump


# Удалить данные из /docker
clear-data:
	rm -rf $(shell pwd)/docker/dada
	rm -rf $(shell pwd)/docker/redis
	rm -rf $(shell pwd)/docker/rabbitmq

# Настроить брандмауэр UFW
ufw:
	apt install ufw
	ufw allow ssh
	ufw allow http
	ufw allow https
	ufw enable

# Создать файлы конфигурации SSL
generate-nginx-keys:
	openssl genrsa > docker/nginx/etc-letsencrypt/live/${DOMAIN}/privkey.pem
	openssl req -new -x509 -key docker/nginx/etc-letsencrypt/live/${DOMAIN}/privkey.pem > docker/nginx/etc-letsencrypt/live/${DOMAIN}/fullchain.pem

#Запуск certbot
certbot:
	docker compose up certbot

#Установить docker на хост-машину
install-docker:
	apt-get update
	apt-get install ca-certificates curl
	install -m 0755 -d /etc/apt/keyrings
	curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
	chmod a+r /etc/apt/keyrings/docker.asc

	echo \
  	"deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  	$(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  	tee /etc/apt/sources.list.d/docker.list > /dev/null

	apt-get update


#Установить nodejs + npm на хост машину
install-node:
	apt update
	apt install -y nodejs npm
	npm cache clean -f
	npm install -g n
	n stable
	source ~/.bashrc

git-drop:
	git stash push --include-untracked
	git stash drop

#Вход в tinker
tinker:
	docker compose exec laravel php artisan tinker


#Загрузка алиасов в bash
alias:
	chmod +x ./scripts/aliases.sh
	./scripts/aliases.sh
	source ~/.bashrc
	@make git-drop