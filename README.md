# APIBaseLaravel
API genérica, base para projetos em Laravel. Conta com recursos como login, recuperação de senha, confirmação de cadastro por e-mail, controle de permissões, etc. A API para a todas governar.

## Documentação
* A documentação do projeto se encontra em construção e pode ser acessada a partir [desse endereço](https://documenter.getpostman.com/view/6527666/SW7gTjUs?version=latest).

## Dependências
Ao Laravel foram adicionadas as seguintes dependências:
* [Laravel/Passport](https://laravel.com/docs/master/passport)
* [Spatie/Laravel-Permission](https://docs.spatie.be/laravel-permission/v3/installation-laravel/)

## Executando em servidor local
1. Faça o download ou clone o projeto:
```
$ git clone https://github.com/Danie1Net0/APIBaseLaravel.git
```

2. Instale as depedências via Composer: 
```
$ composer install
```

3. Crie o arquivo .env: 
```
$ cp .env.example .env
```

4. Gere a chave da aplicação:
```
$ php artisan key:generate
```

5. Crie um banco de dados e altere o seguinte trecho do arquivo .env com seus parâmetros:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE="nome do seu banco criado"
DB_USERNAME="nome de usuario do seu banco"
DB_PASSWORD="senha do seu banco"
```

6. Ainda no arquivo .env altere o seguinte trecho com os parâmetros do seu servidor de e-mail (utilizado para enviar as notificações):
```
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME="seu endereço de e-mail"
MAIL_PASSWORD="senha do seu e-mail"
MAIL_ENCRYPTION=tls
```

7. Execute as migrações: 
```
$ php artisan migrate --seed
```

8. Prepare o Passport para o uso:
```
$ php artisan passport:install
```

9. Inicie a aplicação:
```
$ php artisan serve
```

> ## Considerações importantes
> 1. As definições de *Roles* e *Permissions* padrões estão no arquivo ***database/seeds/RolesAndPermissionsTablesSeeder.php*** e devem ser alteradas conforme a necessidade, podendo ser configuradas também a partir do Artisan.
> 
> 2. Ao executar as *seeds*, um super administrador é criado com os seguintes dados: 
> * ***E-mail:*** email@dominio.com
> * ***Senha:*** asdfasdf  <br><br>
> A partir desse usuário, outros administradores e super administradores pode ser criados.
>
> 3. A propiedade ***APP_URL_FRONT=http://localhost:4200*** encontrada no arquivo de configuração ***.env*** corresponde à URL da aplicação de front-end e deve ser alterada conforme a necessidade.
