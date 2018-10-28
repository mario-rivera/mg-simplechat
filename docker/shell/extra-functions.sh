#!/bin/bash

DB_IMAGE=mariadb:5
DB_CONTAINER_NAME=mariadb

composer() {

    command="composer install"
    if [ -d "$SCRIPT_WORKDIR/vendor" ]; then
        command="composer update"
    fi

    docker run -it --rm -w /www \
    -v $SCRIPT_WORKDIR:/www \
    --user $(id -u):$(id -g) \
    $PHP_IMAGE bash -c "$command"
}

phpunit(){

    docker run -it --rm -w /www \
    -v $SCRIPT_WORKDIR:/www \
    $PHP_IMAGE bash -c "vendor/bin/phpunit tests"
}

db_up(){

    docker run -d --network=$NETNAME \
    --name $DB_CONTAINER_NAME \
    -e MYSQL_ROOT_PASSWORD=root \
    $DB_IMAGE

    # wait_for_connections
}

wait_for_connections(){
    
    docker run --rm --network=$NETNAME \
    -e "TELNET_HOST=$DB_CONTAINER_NAME" \
    -e "TELNET_PORT=3306" \
	-v $SCRIPT_WORKDIR/docker/shell:/app \
	busybox sh -c "chmod +x /app/telnet-wait.sh; /app/telnet-wait.sh"
}
create_data_dir(){

    docker run --rm \
    -v $SCRIPT_WORKDIR:/www \
    $PHP_IMAGE /bin/bash -c 'mkdir -p /www/data && chmod 777 /www/data'
}