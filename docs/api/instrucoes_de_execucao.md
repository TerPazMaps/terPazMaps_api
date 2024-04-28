## Instruções de execução

Pré-requisitos:
1. Xampp v3.3
2. Composer v2.5
3. Redis 3.0.504 (windows) || Redis 6 (Linux)

Após clonar ou baixar o .zip do projeto, execute os passos:

-iniciar os serviços dos xamp e criar uma BASE DE DADOS com nome de sua preferência e codificação utf8mb4_general_ci.
-Em caso de problemas para importar como:#2006 - MySQL server has gone away. Faça os seguintes passos: 
    
    -altere em php.ini(C:\xampp\php\php.ini)

    max_execution_time = 600
    max_input_time = 600
    memory_limit = 1024M
    post_max_size = 1024M


    -altere em my.ini(C:\xampp\mysql\bin\my.ini)

    max_allowed_packet = 1024M
    

-Dentro da pasta do projeto criar um arquivo chamado ".env" o seu conteúdo deve ser copiado inteiramente do arquivo .env-example, depois configure apenas as variáveis:

    DB_DATABASE=NomeDoSeuBanco // criado na etapa anterior
    DB_USERNAME=root    // o padrão do xamp é "root".    
    DB_PASSWORD=        // o padrão do xamp é uma senha vazia.


Comandos no terminal (dentro do diretório do projeto):

-Composer install

-php artisan key:generate

-php artisan jwt:secret

-php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

-php artisan vendor:publish

selecione a opção laravel-mail(deve ser a 14, então digite no terminal e de enter) 
-14

-php artisan storage:link

-php artisan serve

Após isso basta clicar no link que aparecera no terminal ou acessar em seu navegador a url: http://127.0.0.1:8000
