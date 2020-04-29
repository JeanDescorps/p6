# Project 6 of "PHP/Symfony" course for OpenClassrooms

[Snowtricks](http://snowtricks.jeandescorps.fr/) is a website where users can add and comment tricks, the admin can manage everyone tricks and comments.

## Installation

1 - Git clone the project

```
git clone https://github.com/JeanD34/p6.git
``` 

2 - Install [Docker](https://docs.docker.com/compose/install/) and [Docker compose](https://docs.docker.com/compose/gettingstarted/).

In the project directory run : 

```
docker-compose build
docker-compose up -d
```

3 - Create DB volume

In docker directory (your_directory/docker/) create data and db directory : /docker/data/db/

4 - Create snowtricks DB and import datas.sql

Go to [http://localhost:8080/](http://localhost:8080/)

```
User : root
Password: example
```

Create DB named snowtricks and import datas.sql in it.

5 - Composer install

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

6 - Go to [http://localhost/](http://localhost/), all is ready !

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