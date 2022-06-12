Запустить контейнер 
1) docker-compose build
2) docker network create telecom-networks
3) docker-compose up

Установить Laravel с зависимостями командой в терминале контейнера php:

"composer update"

Заполнить типы оборудования командой в терминале контейнера php:

"php artisan db:seed"


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

Вывод типов оборудования с поиском и пагинацией

	GET: localhost:8000/api/equipments_type?paginate=10&search=запрос поиска

Вывод оборудования с поиском и пагинацией

	GET: localhost:8000/api/equipments?paginate=10&search=запрос поиска

Authorization	Bearer Token

		Token	<token>
	
Request Headers

	Accept	application/json

Request Params

	paginate 10

	page 1

	search запрос поиска

Создание/обновление оборудования

		POST localhost:8000/api/equipments
		PUT localhost:8000/api/equipments

Authorization Bearer Token

		Token <token>

Request Headers
	
	Accept application/json

Request Params

	Bodyraw (json)
	
	json
		{	
			"equipment_id": 1,
			"serial_number": "XXAAAAAXAA",
			"description": "Оборудование"
		}


Создание оборудования списком:

	json
		{
			"equipment_id": 2,
			"serial_number": [
				"1XXAAX-Xaa",
				"2VVBBD_Fss"
				],
			"description": "Роутер"
		}

Удаление оборудовантя


	DELETE localhost:8000/api/equipments/2

Authorization Bearer Token

	Token <token>

Request Headers

	Accept application/json

Показать экземпляр оборудования

	GET localhost:8000/api/equipments/2

Authorization Bearer Token

	Token <token>

Request Headers

	Accept application/json