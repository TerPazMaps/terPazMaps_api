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
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/](/docs/api/RegionController.md)
  <!-- - `POST /api/v5/geojson/region` -->
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/{id}](/docs/api/RegionController.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/region/{id}/streets](/docs/api/RegionController.md)
 
  <!-- - `PUT /api/v5/geojson/region/{id}`
  - `DELETE /api/v5/geojson/region/{id}` -->

### Street condition
- **Descrição**: Recurso para gerenciar informações sobre as condições das ruas.
- **Endpoints**:

  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/street_condition/](/docs/api/StreetConditionController.md)
  
  <!-- - `GET /api/v5/geojson/street_condition`
  - `POST /api/v5/geojson/street_condition`
  - `GET /api/v5/geojson/street_condition/{id}`
  - `PUT /api/v5/geojson/street_condition/{id}`
  - `DELETE /api/v5/geojson/street_condition/{id}` -->

### Subclasse
- **Descrição**: Recurso para gerenciar informações sobre subclasses de objetos geográficos.
- **Endpoints**:
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/subclasse/](/docs/api/SubclasseController.md)
  
  <!-- - `POST /api/v5/geojson/subclasse`
  - `GET /api/v5/geojson/subclasse/{id}`
  - `PUT /api/v5/geojson/subclasse/{id}`
  - `DELETE /api/v5/geojson/subclasse/{id}` -->

### Activitie
- **Descrição**: Recurso para gerenciar informações sobre atividades geográficas.
- **Endpoints**:
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[ /api/v5/geojson/activitie/](/docs/api/ActivitieController.md)
  
  <!-- - `GET /api/v5/geojson/activitie`
  - `POST /api/v5/geojson/activitie`
  - `GET /api/v5/geojson/activitie/{id}`
  - `PUT /api/v5/geojson/activitie/{id}`
  - `DELETE /api/v5/geojson/activitie/{id}` -->

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

## Autenticação

Esta API requer autenticação para acessar os endpoints. Por favor, consulte a documentação para obter detalhes sobre como autenticar suas solicitações: [link para a documentação](/docs/api/instrucoes_de_autenticacao.md).

## Formato de Resposta

Todos os endpoints desta API retornam dados no formato JSON.
