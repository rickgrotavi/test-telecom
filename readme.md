Запустить контейнер 
1) docker-compose build
2) docker network create telecom-networks
3) docker-compose up


Зарегистрировать пользователя через POST запрос

Пример
	localhost:8000/api/register

	Request Headers

	Accept
	application/json

	Bodyraw (json)
	json

	{
	  "name": "Test User",
	  "email": "test2@example.com",
	  "password": "password2"
	}

JWT Routes

    Register: localhost:8000/api/register. Method: POST
    Login: localhost:8000/api/login. Method: POST
    Logout: localhost:8000/api/logout. Method: POST
    Refresh: localhost:8000/api/refresh. Method:POST
    Me: localhost:8000/api/me. Method: GET