# Snowtricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/5ed4fc13a41840c0a08ae73643d8903f)](https://www.codacy.com/manual/JeanD34/p6?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JeanD34/p6&amp;utm_campaign=Badge_Grade)

Project 6 of [PHP/Symfony](https://jeandescorps.fr/symfony.pdf#page=17) course for [OpenClassrooms](https://openclassrooms.com/)

[Snowtricks](http://snowtricks.jeandescorps.fr/) is a website where users can add and comment tricks, the admin can manage everyone tricks and comments.

## Build with
    
- Symfony 5
- Bootstrap 4
    
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