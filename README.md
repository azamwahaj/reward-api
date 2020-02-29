# Reward API with Lumen Micro Framework (PHP), MySql and JSON API
- Reward REST API with MySql database 
- Implements JSON API
- Lightweight and super-fast

## Docker Machine Installation for Mac

- `$ brew install docker docker-machine`
- `$ brew cask install virtualbox`
- `$ docker-machine create --driver virtualbox dev`
- `$ docker-machine env dev`
- `$ eval "$(docker-machine env dev)"`
- `$ docker run hello-world`
- `$ docker-machine stop default`

## How to setup project and server using Docker Machine
- `git clone https://github.com/azamwahaj/reward-api.git`
- `cd reward-api`
- `$ docker-machine start dev`
- `$ docker-machine env dev`
- `$ eval "$(docker-machine env dev)"`
- `$ docker-compose up` 
- `Get docker machine ip " $ docker-machine ip dev"` 
- `cp .env.example .env`
- `Edit .env and change your mysql host with your docker machine ip`
- `Edit .env and change "USER_ENDPOINT" with your user service endpoint`
- `change permission to 777 outside from docker container "chmod -R 777 storage"`
- `Shell into the PHP container, "$ docker-compose exec php-fpm bash"`
- `composer install`
- `php artisan migrate`
- `php artisan db:seed`

### Reward API Documentation

```
Get Rewards

Endpoint:
[GET] http://192.168.99.100:8082/api/v1/rewards

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Reward1",
            "amount": "10.00",
            "expiry_date": "2020-05-10",
            "created_at": "2020-02-26 22:47:32",
            "updated_at": "2020-02-26 22:47:32"
        },
        {
            "id": 2,
            "name": "Reward2",
            "amount": "20.00",
            "expiry_date": "2020-05-11",
            "created_at": "2020-02-26 22:47:57",
            "updated_at": "2020-02-26 22:47:57"
        },
        {
            "id": 3,
            "name": "Reward3",
            "amount": "30.00",
            "expiry_date": "2020-05-12",
            "created_at": "2020-02-26 22:48:11",
            "updated_at": "2020-02-26 22:48:11"
        }
    ]
}
```

```
Get Single Reward with Users assigned to it

Endpoint:
[GET] http://192.168.99.100:8082/api/v1/reward/1

Response:
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Reward1",
        "amount": "10.00",
        "expiry_date": "2020-05-10",
        "created_at": "2020-02-26 22:47:32",
        "updated_at": "2020-02-26 22:47:32",
        "users": [
            {
                "id": 1,
                "name": "User1",
                "email": "user1@email.com",
                "phone": "971121231234",
                "country": "UAE",
                "created_at": "2020-02-26 22:42:47",
                "updated_at": "2020-02-26 22:42:47"
            },
            {
                "id": 2,
                "name": "User2",
                "email": "user2@email.com",
                "phone": "971121231234",
                "country": "KSA",
                "created_at": "2020-02-26 22:42:47",
                "updated_at": "2020-02-26 22:42:47"
            },
            {
                "id": 3,
                "name": "User3",
                "email": "user3@email.com",
                "phone": "971121231234",
                "country": "BH",
                "created_at": "2020-02-26 22:42:47",
                "updated_at": "2020-02-26 22:42:47"
            }
        ]
    }
}
```

```
Create Reward

Endpoint:
[POST] http://192.168.99.100:8082/api/v1/reward

Request:
{
	"name": "Reward1",
	"amount": 10,
	"expiry_date": "2020-05-13"
}

Response:
{
    "success": false,
    "data": {
        "name": "Reward1",
        "amount": 10,
        "expiry_date": "2020-05-13",
        "updated_at": "2020-02-28 17:23:41",
        "created_at": "2020-02-28 17:23:41",
        "id": 1
    }
}
```

```
Update Reward

Endpoint:
[PUT] http://192.168.99.100:8082/api/v1/reward/1

Request:
{
	"name": "Reward1",
	"amount": 10,
	"expiry_date": "2020-05-13"
}

Response:
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Reward1",
        "amount": 10,
        "expiry_date": "2020-05-13",
        "created_at": "2020-02-26 22:47:32",
        "updated_at": "2020-02-28 17:26:35"
    }
}
```

```
Delete User

Endpoint:
[DELETE] http://192.168.99.100:8082/api/v1/reward/1

Response:
{
    "success": true,
    "message": "Reward Reward1 removed successfully"
}
```

```
Assign Reward to User

Endpoint:
[POST] http://192.168.99.100:8082/api/v1/assign-reward

Request:
{
	"userId": 1,
	"rewardId": 1
}

Response:
{
    "success": true,
    "message": "Reward1 assigned successfully"
}
```

```
List Rewards assigned to specific User

Endpoint:
[GET] http://192.168.99.100:8082/api/v1/reward/transactions/user/1

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "Reward1",
            "amount": "10.00",
            "expiry_date": "2020-05-13",
            "created_at": "2020-02-26 22:47:57",
            "updated_at": "2020-02-26 22:47:57"
        },
        {
            "id": 2,
            "name": "Reward2",
            "amount": "20.00",
            "expiry_date": "2020-05-14",
            "created_at": "2020-02-26 22:48:11",
            "updated_at": "2020-02-26 22:48:11"
        },
        {
            "id": 3,
            "name": "Reward3",
            "amount": "30.00",
            "expiry_date": "2020-03-28",
            "created_at": "2020-02-28 12:33:27",
            "updated_at": "2020-02-28 12:33:27"
        }
    ]
}
```

#### Unit Test
```
Shell into PHP container
$ docker-compose exec php-fpm bash

Run:
vendor/bin/phpunit
```

### References
- [Lumen micro-framework](https://lumen.laravel.com/)
- [JSON API](http://jsonapi.org/)
- [Guzzle, PHP HTTP client](http://docs.guzzlephp.org/en/stable/index.html)
