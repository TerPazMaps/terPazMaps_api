## Instruções de execução

Pré-requisitos, instale em ordem (windows):
1. Xampp v3.3 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md)
2. Composer v2.5 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
3. PostgreSQL 16.3 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
4. Redis 3.0.504-windows - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
   

Após baixar e instalar as dependências, execute os passos:

1. Configurar e iniciar o servidor apache do xampp.
   
        - Edite o arquivo `php.ini` (localizado em `C:\xampp\php\php.ini`) 
        - e descomente as seguintes linhas removendo o `;`:**

    ```ini
    extension=pdo_pgsql
    extension=pgsql
    extension=zip 
    ```
        - caso necessário reinicie o servidor apache do xampp para aplicar as modificações 

2. Criar e importar base de dados no pgAdmin4:

        - Abra o pgAdmin4
        - Use o super adimin padrão `postgres` e a senha que vc definiu na instalação
        - Crie uma base de dados.
        - Selecione o banco de dados onde você deseja adicionar a extensão PostGIS.
        - Vá em `Tools` > `Query Tool` ou simplesmente clique no ícone de `Query Tool` na barra de ferramentas.
        - Execute: 
    ```sql
        CREATE EXTENSION postgis;
    ```
        ou
        - Pesquise o nome `Postgis` e adicione ela.
        - Agora com botão direto em cima da base de dados selecione `restore`
        - Clicando em `filename` vc pode selecionar o banco para importação.



4. **Configurar o arquivo `.env` do Laravel:**

        - Crie um arquivo `.env` na pasta rais do projeto e cole tudo de `.env.example`  
        - A seguir configure as variáveis:

    ```env
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nome_do_banco_de_dados
    DB_USERNAME=seu_usuario  //padrão do PosgreeSQL é "postgres".  
    DB_PASSWORD=sua_senha   

    REDIS_HOST=127.0.0.1
    REDIS_PASSWORD=null
    REDIS_PORT=6379
    REDIS_CLIENT=predis

    JWT_TTL=120
    ```

        - verifique se as configurações de email foram definidas corretamente em:
    [configuração de email](/docs/api/instrucoes_envio_de_email.md)

5. **Comandos no terminal (dentro do diretório do projeto):**

   - Composer install

   - php artisan key:generate

   - php artisan jwt:secret   
   ou usar a chave que esta no keeWeb e adicionar assim: `JWT_SECRET=sua_chave`, no final do .env

   - php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

   - php artisan vendor:publish    
   selecione a opção laravel-mail(deve ser a 14, então digite no terminal e de enter) 

   - php artisan storage:link

   - php artisan serve

Após isso basta clicar no link que aparecera no terminal ou acessar em seu navegador a url: http://127.0.0.1:8000



[Voltar a pagina principal](/README.md)
