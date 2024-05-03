# Serviços

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->
  
## Pesquisa de atividades por área

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/services/points-of-interest` 

## Parâmetros
api/v5/geojson/services/activities-nearby?region_id=7&subclass_id=7&raio=50&latitude=-1.465815&longitude=-48.459401

## Retorno caso de sucesso

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -48.4591456436184,
          -1.4662854690406
        ]
      },
      "properties": {
        "id": 10718,
        "region_id": 7,
        "subclass_id": 28,
        "name": "açai"
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Polygon",
        "coordinates": [
          [
            [
              -48.458901,
              -1.465815
            ],
            [
              -48.4589016315217,
              -1.4657898778409102
            ]
          ]
        ]
      }
    }
    ]
}
```
## Retorno caso de erro ou sem pontos proximos

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Polygon",
        "coordinates": [
          [
            [
              -48.458901,
              -1.465815
            ],
            [
              -48.4589016315217,
              -1.4657898778409102
            ]
          ]
        ]
      }
    }
    ]
}
```
