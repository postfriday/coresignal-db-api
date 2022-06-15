#!make
.PHONY: help
PHP_TAG=muscobytes/php-8.1-cli
DOCKER=docker run -ti --volume "$(shell pwd):/var/www/html" --env PHP_IDE_CONFIG="serverName=coresignaldbapi" ${PHP_TAG}

help:      ## Shows this help message
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
build:
	curl --silent "https://gist.githubusercontent.com/postfriday/e405590799994018c8bef7705436eb4f/raw/ed8ad5db7df398131c7cfdb031be31926fdbc123/Dockerfile%2520(php:8.1-cli)" | docker build --progress plain --tag ${PHP_TAG} --file - .
sh:
	${DOCKER} sh
test:
	${DOCKER} ./vendor/bin/phpunit tests
install:
	${DOCKER} composer install
