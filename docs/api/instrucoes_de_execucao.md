## Instruções de execução

Pré-requisitos, instale em ordem (windows):
1. Xampp v3.3 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md)
2. Composer v2.5 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
3. PostgreSQL 16.3 - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
4. Redis 3.0.504-windows - [Como instalar](/docs/api/instalacao_dependencias_execucao.md) 
   

Após baixar e instalar as dependências, execute os passos:

1. Iniciar o servidor apache do xampp.
   
2. Iniciar pgAdmin4:

        - Abra o pgAdmin4 no menu iniciar.
        - Use o super admin padrão `postgres` e a senha que vc definiu na instalação.
        - certifique se de que a base de dados esteja ativa.

3. **Comandos no terminal (dentro do diretório do projeto):**

   - Composer install

   - php artisan key:generate

   - php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

   - php artisan vendor:publish    
   selecione a opção laravel-mail(deve ser a 14, então digite no terminal e de enter) 

   - php artisan storage:link

   - php artisan serve

Após isso basta clicar no link que aparecera no terminal ou acessar em seu navegador a url: http://127.0.0.1:8000



[Voltar a pagina principal](/README.md)
