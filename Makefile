include .env

install:
	git clone https://${GIT_REPOSITORY_TOKEN}@github.com/Lavreek/Fluidline.ModX-53.git ./project/


cli:
	docker exec -it fluidline-php8.1-cli bash


app:
	docker exec -it ${PROJECT}-running-app bash


db:
	docker exec -it ${PROJECT}-running-database bash
migrate:
	docker exec -t ${PROJECT}-running-database sh /usr/local/docker/sql/src/migrate.sh


editable:
	chmod 777 -R project/
readable:
	chmod 775 -R project/

build:
	docker compose build
build-no-cache:
	docker compose build --no-cache --pull
up:
	docker compose up -d
stop:
	docker stop ${PROJECT}-running-database && docker stop ${PROJECT}-running-app
rm:
	docker rm ${PROJECT}-running-database && docker rm ${PROJECT}-running-app
