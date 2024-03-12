# API de Dados Geoespaciais para Aplicativo de Mapas

Bem-vindo à API de Dados Geoespaciais para nosso aplicativo de mapas! Esta API fornece acesso a diversos recursos relacionados a informações geográficas, permitindo que você desenvolva poderosos recursos de visualização de mapas em seu aplicativo. Abaixo estão os principais recursos disponíveis:

## Recursos Principais

### Classe
- **Descrição**: Recurso para gerenciar informações sobre classes de objetos geográficos.
- **Endpoints**:
  - [GET /api/v5/geojson/classe](/docs/api/ClasseController.md)
  - `POST /api/v5/geojson/classe`
  - `GET /api/v5/geojson/classe/{id}`
  - `PUT /api/v5/geojson/classe/{id}`
  - `DELETE /api/v5/geojson/classe/{id}`

### Região
- **Descrição**: Recurso para gerenciar informações sobre regiões geográficas.
- **Endpoints**:
  - `GET /api/v5/geojson/region`
  - `POST /api/v5/geojson/region`
  - `GET /api/v5/geojson/region/{id}`
  - `PUT /api/v5/geojson/region/{id}`
  - `DELETE /api/v5/geojson/region/{id}`

### Condição da Rua
- **Descrição**: Recurso para gerenciar informações sobre as condições das ruas.
- **Endpoints**:
  - `GET /api/v5/geojson/street_condition`
  - `POST /api/v5/geojson/street_condition`
  - `GET /api/v5/geojson/street_condition/{id}`
  - `PUT /api/v5/geojson/street_condition/{id}`
  - `DELETE /api/v5/geojson/street_condition/{id}`

### Subclasse
- **Descrição**: Recurso para gerenciar informações sobre subclasses de objetos geográficos.
- **Endpoints**:
  - `GET /api/v5/geojson/subclasse`
  - `POST /api/v5/geojson/subclasse`
  - `GET /api/v5/geojson/subclasse/{id}`
  - `PUT /api/v5/geojson/subclasse/{id}`
  - `DELETE /api/v5/geojson/subclasse/{id}`

### Atividade
- **Descrição**: Recurso para gerenciar informações sobre atividades geográficas.
- **Endpoints**:
  - `GET /api/v5/geojson/activitie`
  - `POST /api/v5/geojson/activitie`
  - `GET /api/v5/geojson/activitie/{id}`
  - `PUT /api/v5/geojson/activitie/{id}`
  - `DELETE /api/v5/geojson/activitie/{id}`

### Rua
- **Descrição**: Recurso para gerenciar informações sobre ruas geográficas.
- **Endpoints**:
  - `GET /api/v5/geojson/street`
  - `POST /api/v5/geojson/street`
  - `GET /api/v5/geojson/street/{id}`
  - `PUT /api/v5/geojson/street/{id}`
  - `DELETE /api/v5/geojson/street/{id}`

## Autenticação

Esta API requer autenticação para acessar os endpoints. Por favor, consulte a documentação para obter detalhes sobre como autenticar suas solicitações.

## Formato de Resposta

Todos os endpoints desta API retornam dados no formato JSON.

## Documentação Adicional

Para obter mais detalhes sobre como usar esta API, consulte a documentação completa em [link para a documentação](#RegionController.md).

Fique à vontade para explorar e integrar esses recursos em seu aplicativo de mapas para criar uma experiência rica e envolvente para seus usuários! Se precisar de assistência ou tiver alguma dúvida, não hesite em nos contatar.
