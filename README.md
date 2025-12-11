<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Api desenvolvida para registro e relatorio de glicemias mensais

## Instalação

Clone the repo locally:

```sh
git clone https://github.com/ricardo-alv/gerenciamento-eventos-incricoes.git](https://github.com/ricardo-alv/glicemia-api.git)
cd glicemia-api
```

Instalar PHP dependencias:

```sh
composer install
```
Copiar e configurar o arquivo .env:
Informações de banco de dados e envio de email.

```sh
cp .env.example .env
```
```sh
php artisan key:generate
```
Adicionar as informações do banco: 

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
Informações do email para o envio de notificações:
 
```sh
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```
Iniciar o servidor:
```sh
php artisan serve
```

