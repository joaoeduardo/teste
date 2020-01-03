# Rest API

Esta API implementa a especificação [JSON:API](https://jsonapi.org/). Documentação dos endpoints se encontra no arquivo *postman_collection.json*.

## Requisitos Mínimos

PHP 7.2

Maria DB 10.1

## Download

```
git clone https://github.com/joaoeduardo/teste.git
```

## Instalação

```
composer install

cp .env.example .env

php artisan key:generate
```
Edite as informações referentes ao banco de dados presentes no arquivo .env:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
E depois execute as migrations:

```
php artisan migrate
```

## Subir servidor
```
php artisan serve
```

## Subir processo da fila
```
php artisan queue:work
```

## Testes
```
./vendor/bin/phpunit
```
