## Chat Application setup with Docker
- Install [Docker](https://docs.docker.com/engine/installation)
- Make the install script executable "chmod +x ./dshell.sh"
- Run the installation script "bash ./dshell.sh install"
- This will build the docker image and start the container

## Chat Application
- Visit "http://localhost:8080"

## Authentication and Admin usage
- There is no authentication, you can enter any username in the login form
- To act as an admin simply enter the string 'admin' in the login form

## To Post an Admin Message to every room
- curl -X POST \
  http://localhost:8080/admin/message \
  -H 'Content-Type: application/json' \
  -H 'Cookie: PHPSESSID={enter your admin php session id}' \
  -H 'cache-control: no-cache' \
  -d '{"message": "This is an admin message"}'

## To Create a new Chat Room
- Go to 'http://localhost:8080/room/{room_name}'