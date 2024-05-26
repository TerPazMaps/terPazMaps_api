## Instalação e configuração de dependências/variáveis de ambiente do sistema.

### `Xampp v3.3` - [Download⬇️](https://www.apachefriends.org/download.html)     

1. **A instalação no windows é simples, basta ir clicando next:**
   
        Ao final basta definir o php como Variável de ambiente.
        - pesquise por "Editar as variáveis de ambiente do sistema".
        - Na aba "Avançado" > botão "Variáveis de ambiente...".
        - Abaixo de "Variáveis do sistema" clique na variável "Path".
        - adicione um novo com o local onde vc instalou o xampp (exemp: C:\xampp\php).
        - Acessando o terminal, o comando `php -v` deve retornar a versão .

        - Edite o arquivo `php.ini` (localizado em `C:\xampp\php\php.ini`) .
        - e descomente as seguintes linhas removendo o `;`
    ```ini
    extension=pdo_pgsql
    extension=pgsql
    extension=zip 
    ```

### `Composer v2.5` - [Download⬇️](https://getcomposer.org/download/) 

1. Apos ter o xampp instalado e ter definido o php como variável de ambiente, basta executar o composer e dar next 
    
        - Acessando o terminal, o comando `composer -v` deve retornar a versão.

### `Redis 3.0.504` - [Download⬇️](https://github.com/microsoftarchive/redis/releases/tag/win-3.0.504) 

1. Basta executar o arquivo .exe e dar next. 
     
2. Adicionar redis como variável de ambiente, da mesma forma como o php
   
        - Adicione o local onde foi instalado o redis(exemplo: C:\Program Files\Redis).
        - Acessando o terminal, use `redis-cli` depois `ping`, e o retorno deve ser `PONG` .

### `PostgreSQL 16.3` - [Download⬇️](https://www.postgresql.org/download/) 

1. Execute o .exe

        - mantenha todos os componentes marcados como stack builder e pgAdmin4.
        - Defina uma senha para o super usuário(postgres), ela sera usada posteriormente .
        - mantenha a porta default(5432).
        - agora basta continuar dando next. Ao final marque para abrir o `Stack builder`.
        - ou execute ele através do menu iniciar.

2. Execução do `Stack builder`
   
        - Selecione a versão do postgreeSQL que vc instalou.
        - apos isso selecione o postGis dentro de `Spatial Extensions`.
        - o stack builder vai baixar e instalar a dependência.
   
    > [!WARNING]
    > Casso o stack builder trave, aguarde ate ele iniciar o download   
        
        - Ele vai porsguir com a instalação, caso mantenha marcado a criação de uma base de dados teste,
        forneça a senha e usuário corretos para que não gere loop nessa instalação.

3. importar base de dados   

        - Abra o pgAdmin4
        - Use o super admin padrão `postgres` e a senha que vc definiu na instalação.
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

### `.env` 

        - Crie um arquivo `.env` na pasta raiz do projeto e cole tudo de `.env.example`  
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
JWT_SECRET=sua_chave

```
        - Usar a chave que esta no keeWeb e adicionar o JWT_SECRET 
        - ou usar o comando 'php artisan jwt:secret' para gerar uma chave nova.    

        - verifique se as configurações de email foram definidas corretamente como em:
    
   - [configuração de email](/docs/api/instrucoes_envio_de_email.md)




[Voltar a pagina principal](/docs/api/instrucoes_de_execucao.md)
