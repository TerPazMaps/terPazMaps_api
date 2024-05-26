## Instalação de dependências do ambiente.

### `Xampp v3.3` - [Download⬇️](https://www.apachefriends.org/download.html)     

1. **A instalação no windows é simples, basta ir clicando next:**
   
        Ao final basta definir o php como Variável de ambiente
        - pesquise por "Editar as variáveis de ambiente do sistema"
        - Na aba "Avançado" > botão "Variáveis de ambiente..."
        - Abaixo de "Variáveis do sistema" clique na variável "Path"
        - adicione um novo com o local onde vc instalou o xampp (exemp: C:\xampp\php)
        - Acessando o terminal, o comando `php -v` deve retornar a versão 


### `Composer v2.5` - [Download⬇️](https://getcomposer.org/download/) 

1. Apos ter o xampp instalado e ter definido o php como variável de ambiente, basta executar o composer e dar next 
    
        - Acessando o terminal, o comando `composer -v` deve retornar a versão 

### `Redis 3.0.504` - [Download⬇️](https://github.com/microsoftarchive/redis/releases/tag/win-3.0.504) 

1. Basta executar o arquivo .exe e dar next. 
     
2. Adicionar redis como variável de ambiente, da mesma forma como o php
   
        - Adicione o local onde foi instalado o redis(exemplo: C:\Program Files\Redis)
        - Acessando o terminal, use `redis-cli` depois `ping`, e o retorno deve ser `PONG` 

### `PostgreSQL 16.3` - [Download⬇️](https://www.postgresql.org/download/) 

1. Execute o .exe

        - mantenha todos os componentes marcados como stack builder e pgAdmin4
        - Defina uma senha para o super usuário(postgres), ela sera usada posteriormente 
        - mantenha a porta default(5432)
        - agora basta continuar dando next. Ao final marque para abrir o `Stack builder`
        - ou execute ele através do menu iniciar.

2. Execução do `Stack builder`
   
        - Selecione a versão do postgreeSQL que vc instalou
        - apos isso selecione o postGis dentro de `Spatial Extensions`
        - o stack builder vai baixar e instalar a dependência
   
    > [!WARNING]
    > Casso o stack builder trave, aguarde ate ele iniciar o download   
        
        - Ele vai porsguir com a instalação, caso mantenha marcado a criação de uma base de dados teste,
        forneça a senha e usuário corretos para que não gere loop nessa instalação


[Voltar a pagina principal](/docs/api/instrucoes_de_execucao.md)
