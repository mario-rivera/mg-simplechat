#!/bin/bash
PWD=$(pwd)
SCRIPT_WORKDIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
BASENAME_LOWER=$(basename $SCRIPT_WORKDIR | tr '[:upper:]' '[:lower:]')

NETNAME=dev

NGINX_IMAGE=nginx:1.15
NGINX_CONTAINER_NAME=nginx-chat
PHP_IMAGE=mrivera-php-fpm
PHP_CONTAINER_NAME=php-chat

source "$SCRIPT_WORKDIR/docker/shell/extra-functions.sh"

create_network_ifnotexists(){
    # -z is if output is empty
    if [ -z "$(docker network ls | grep $1)" ]; then
        docker network create $1
        echo "Network $1 created"
    fi
}

install(){
    create_network_ifnotexists $NETNAME
    build_php

    composer
    # phpunit
    
    create_data_dir
    php_up
    server_up
}

build_php(){

    # docker build -t $PHP_IMAGE $SCRIPT_WORKDIR/docker/php
    docker build -t $PHP_IMAGE ./docker/php
}

php_up(){

    docker run -d --network=$NETNAME \
    --name $PHP_CONTAINER_NAME \
    -v $SCRIPT_WORKDIR:/www \
    $PHP_IMAGE
}

server_up(){

    docker run -d --network=$NETNAME \
    --name $NGINX_CONTAINER_NAME \
    -p 8080:80 \
    -v $SCRIPT_WORKDIR/docker/nginx/default.conf:/etc/nginx/conf.d/default.conf \
    -v $SCRIPT_WORKDIR:/www \
    $NGINX_IMAGE
}

down(){

    docker stop $NGINX_CONTAINER_NAME && docker rm $NGINX_CONTAINER_NAME
    docker stop $PHP_CONTAINER_NAME && docker rm $PHP_CONTAINER_NAME
}

destroy(){

    down
    docker network rm $NETNAME
    # docker rmi $PHP_IMAGE
}

$1
