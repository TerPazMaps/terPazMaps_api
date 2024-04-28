# API de Dados Geoespaciais para Aplicativo de Mapas Sociais.

Bem-vindo à API de Dados Geoespaciais para nosso aplicativo de mapas sociais! Esta API fornece acesso a diversos recursos relacionados a informações geográficas, permitindo que você desenvolva poderosos recursos de visualização de mapas em seu aplicativo. Abaixo estão os principais recursos disponíveis:

## Recursos Principais

### Classe
- **Descrição**: Recurso para gerenciar informações sobre classes de objetos geográficos.
- **Endpoints**:
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/classe/](/docs/api/ClasseController.md)
  <!-- - `POST /api/v5/geojson/classe` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/classe/{id}](/docs/api/ClasseController.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/classe/{id}/subclasses](/docs/api/ClasseController.md)
 
  <!-- - `PUT /api/v5/geojson/classe/{id}`
  - `DELETE /api/v5/geojson/classe/{id}` -->

### Region
- **Descrição**: Recurso para gerenciar informações sobre regiões geográficas.
- **Endpoints**:
  <!-- - `POST /api/v5/geojson/region` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/](/docs/api/RegionController.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/{id}](/docs/api/RegionController.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/{id}/streets](/docs/api/RegionController.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/{id}/icons](/docs/api/RegionController.md)
 
  <!-- - `PUT /api/v5/geojson/region/{id}`
  - `DELETE /api/v5/geojson/region/{id}` -->

### Street condition
- **Descrição**: Recurso para gerenciar informações sobre as condições das ruas.
- **Endpoints**:

  
  <!-- - `GET /api/v5/geojson/street_condition`
  - `POST /api/v5/geojson/street_condition`
  - `GET /api/v5/geojson/street_condition/{id}`
  - `PUT /api/v5/geojson/street_condition/{id}`
  - `DELETE /api/v5/geojson/street_condition/{id}` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/street_condition/](/docs/api/StreetConditionController.md)

### Subclasse
- **Descrição**: Recurso para gerenciar informações sobre subclasses de objetos geográficos.
- **Endpoints**:
  
  <!-- - `POST /api/v5/geojson/subclasse`
  - `GET /api/v5/geojson/subclasse/{id}`
  - `PUT /api/v5/geojson/subclasse/{id}`
  - `DELETE /api/v5/geojson/subclasse/{id}` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/subclasse/](/docs/api/SubclasseController.md)

### Activitie
- **Descrição**: Recurso para gerenciar informações sobre atividades geográficas.
- **Endpoints**:
  
  <!-- - `GET /api/v5/geojson/activitie`
  - `POST /api/v5/geojson/activitie`
  - `GET /api/v5/geojson/activitie/{id}`
  - `PUT /api/v5/geojson/activitie/{id}`
  - `DELETE /api/v5/geojson/activitie/{id}` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/activitie/](/docs/api/ActivitieController.md)

### Street
- **Descrição**: Recurso para gerenciar informações sobre ruas geográficas.
- **Endpoints**:
  <!-- - `GET /api/v5/geojson/street`
  - `POST /api/v5/geojson/street`
  - `GET /api/v5/geojson/street/{id}`
  - `PUT /api/v5/geojson/street/{id}`
  - `DELETE /api/v5/geojson/street/{id}` -->

## Instruções de execução


Clique aqui para saber mais: [link para a documentação](/docs/api/instrucoes_de_execucao.md).

## Condiguração do envio de e-mails

Clique aqui para saber mais: [link para a documentação](/docs/api/instrucoes_envio_de_email.md).

## Autenticação

Esta API requer autenticação com JSON Web Token para acessar determinados endpoints. Por favor, consulte a documentação para obter detalhes sobre como autenticar suas solicitações: [link para a documentação](/docs/api/instrucoes_de_autenticacao.md).

- **Descrição**: Recurso de registro e login de usuário.
- **Endpoints**:
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/register](/docs/api/instrucoes_de_autenticacao.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/login](/docs/api/instrucoes_de_autenticacao.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/refresh](/docs/api/instrucoes_de_autenticacao.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/logout](/docs/api/instrucoes_de_autenticacao.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/me](/docs/api/instrucoes_de_autenticacao.md)
  
## Atualização de senha.

- **Descrição**: Recurso para recuperação de contas com envio de link único por e-mail .
- **Endpoints**:
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/send-password-reset-notification](/docs/api/atualizacao_de_senha.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  [ /api/v5/reset-password](/docs/api/atualizacao_de_senha.md)


## Formato de Resposta

Todos os endpoints desta API retornam dados no formato JSON.
