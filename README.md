# Project 6 of "PHP/Symfony" course for OpenClassrooms

[Snowtricks](http://snowtricks.jeandescorps.fr/) is a website where users can add and comment tricks, the admin can manage everyone tricks and comments.

## Installation

__1 - Git clone the project__

```
git clone https://github.com/JeanD34/p6.git
``` 

__2 - Install [Docker](https://docs.docker.com/compose/install/) and [Docker compose](https://docs.docker.com/compose/gettingstarted/).__

In the project directory run : 

```
docker-compose build
docker-compose up -d
```

__3 - Create DB volume__

In docker directory (your_directory/docker/) create data and db directory : /docker/data/db/

__4 - Create snowtricks DB and import datas.sql__

Go to [http://localhost:8080/](http://localhost:8080/)

```
User : root
Password: example
```

Create DB named snowtricks and import datas.sql in it.

__5 - Composer install__

Run this command, to know your php container name : 

```
docker ps
```

Go to your php container :

```
docker exec -ti you_php_container_name bash
```

Run : 

```
composer install
```

__6 - Go to [http://localhost/](http://localhost/), all is ready !__

## Usage

User account :

```
Pseudo : User
Password : User34!
```

Admin account :

```
Pseudo : Admin
Password : Admin34!
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to pass all checks.