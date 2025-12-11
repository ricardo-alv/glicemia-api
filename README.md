<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## SOBRE GERENCIA EVENTOS & INCRIÇÕES

O sistema de Gerenciamento de Eventos e Inscrições permitirá que usuários autenticados criem e gerenciem eventos categorizados, incluindo funcionalidades para criação, edição e exclusão de eventos e categorias. Participantes poderão se inscrever em eventos, respeitando limites de capacidade e garantindo um controle eficiente das inscrições..


## Instalação

Clone the repo locally:

```sh
git clone https://github.com/ricardo-alv/gerenciamento-eventos-incricoes.git
cd gerenciamento-eventos-incricoes
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
Caso nao tenha a migrate jobs rodar o comando

```sh
php artisan queue:table
```
Em seguida rode as migrations
```sh
php artisan migrate
```
Após executar as migrations rode o comando de seed para criar o Super Admin e as Roles
```sh
php artisan db:seed
```
Iniciar o servidor:
```sh
php artisan serve
```
Executar o comando para executar as queues (envio de email):
```sh
php artisan queue:work
```
Login e senha do Super Admin:
```sh
 email:super.admin@com.br
 password: 12345678
```
Para realizar os testes criar um banco de dados exclusivo para teste se necessário:

Modificar a variável do env ou criar .env.testing:
```sh
APP_ENV=testing
```
Para executar todos os test:
```sh
php artisan test
```
Para executar um test especifico por nome da classe ou função:
```sh
 php artisan test --filter test_name
```
Executar o comando se necessário antes de rodar os tests:
```sh
composer dumpautoload
```

