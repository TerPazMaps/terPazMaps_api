## Instruções de execução

Pré-requisitos, instale em ordem (windows):
1. Xampp v3.3 - [download](https://www.apachefriends.org/download.html)
2. Composer v2.5 - [download](https://getcomposer.org/download/) 
3. PostgreSQL 16.3 - [download](https://www.postgresql.org/download/) 
4. Redis 3.0.504-windows - [download](https://github.com/microsoftarchive/redis/releases/tag/win-3.0.504) 
   

Após clonar ou baixar o .zip do projeto, execute os passos:

-iniciar o servidor apache do xampp.

1. **Edite o arquivo `php.ini` (localizado em `C:\xampp\php\php.ini`) e descomente (ou adicione) as seguintes linhas:**

    ```ini
    extension=pdo_pgsql
    extension=pgsql
    extension=zip 
    ```

2. **Configurar o arquivo `.env` do Laravel:**

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nome_do_banco_de_dados
    DB_USERNAME=seu_usuario  //padrão do PosgreeSQL é "postgres".  
    DB_PASSWORD=sua_senha    //recomendo usar "root" na local
    ```

3. **Comandos no terminal (dentro do diretório do projeto):**

   - Composer install

   - php artisan key:generate

   - php artisan jwt:secret

   - php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

   - php artisan vendor:publish  
   selecione a opção laravel-mail(deve ser a 14, então digite no terminal e de enter) 

   - php artisan storage:link

   - php artisan serve

Após isso basta clicar no link que aparecera no terminal ou acessar em seu navegador a url: http://127.0.0.1:8000


# Outras dicas

1. Definir php como variáveis de ambiente no windowns
    - Pesquise no menu iniciar por "editar as variáveis de ambiente do sistema"
    - apos abrir, selecione a aba "Avançado"
    - selecione a opção "Variáveis de ambiente..."
    - Nas opções de "variáveis do sistema": selecione Path
    - Adicione um novo endereço: C:\xampp\php

2. Adicionar extensão PostGis ao sua base de dados no pgAdmin
    - No painel da esquerda, expanda o servidor conectado.
    - Expanda a pasta Databases e selecione o banco de dados onde você deseja adicionar a extensão PostGIS.
    - Vá em Tools > Query Tool ou simplesmente clique no ícone de Query Tool na barra de ferramentas.
    - Execute: 
        ```sql
            CREATE EXTENSION postgis;
        ```

    ou
    
    - expanda sua base de dados, e em Extensions clique na opção "Create>Extension"
    - pesquise o nome "Postgis"
    - adicione

[Voltar a pagina principal](/README.md)
