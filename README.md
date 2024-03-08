## Instruções de execução

Pré-requisitos:
1. Xampp v3.3
2. Composer v2.5

Após clonar ou baixar o .zip do projeto, execute os passos:

-iniciar os serviços dos xamp e criar uma BASE DE DADOS com nome de sua preferência e codificação utf8mb4_general_ci.

-Dentro da pasta do projeto criar um arquivo chamado ".env" o seu conteúdo deve ser copiado inteiramente do arquivo .env-example, depois configure apenas as variáveis:

    DB_DATABASE=NomeDoSeuBanco // criado na etapa anterior
    DB_USERNAME=root    // o padrão do xamp é "root".    
    DB_PASSWORD=        // o padrão do xamp é uma senha vazia.


Comandos no terminal (dentro do diretório do projeto):

-Composer install

-php artisan key:generate

-php artisan serve

Após isso basta clicar no link que aparecera no terminal ou acessar em seu navegador a url: http://127.0.0.1:8000
