<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Regiões


![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/Region` 

## Parâmetros
 Este método não aceita nenhum parâmetro.

<!-- | Name    | Description                                                                                                                                 |
|---------|----------------------------------------------------------------------------------------------------------------------------------------------|
| orderBy |  use o parâmetro orderBy com o valor `name`. | -->

## Retorno status:200 - um array de regioes
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
              -48.4025469110289,
              -1.30916980179879
            ],[...]
          ]
        ]
      },
      "properties": {
        "ID": 3,
        "Nome": "Jurunas",
        "Centro": {
          "type": "Point",
          "coordinates": [
            -48.49343776702881,
            -1.4688742217081574
          ]
        }
      }
    },

```
- `type`: Tipo do objeto. [ string ]  
- `features`: Array de objetos de recursos. [ array de objetos ]  
- `geometry`: Objeto de geometria do recurso. [ objeto ]  
- `type`: Tipo de geometria. [ string ]  
- `coordinates`: Coordenadas da geometria. [ array de arrays de arrays de números ]  
- `ID`: ID da região. [ int ]  
- `Nome`: Nome da região. [ string ]  
- `Centro`: Informações do centro da região. [ objeto ]  
- `type`: Tipo do centro. [ string ]  
- `coordinates`: Coordenadas do centro. [ array de números ]  

.  

# Região por identificador


![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/Region/{id}` 

## Parâmetros

<!-- Este método não aceita nenhum parâmetro. -->

| Name    | Description                                                                                                                                 |
|---------|----------------------------------------------------------------------------------------------------------------------------------------------|
| id* |  int, required  |



## Retorno status:200 - uma região especifica
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
              -48.4025469110289,
              -1.30916980179879
            ],[...]
          ]
        ]
      },
      "properties": {
        "ID": 3,
        "Nome": "Jurunas",
        "Centro": {
          "type": "Point",
          "coordinates": [
            -48.49343776702881,
            -1.4688742217081574
          ]
        }
      }
    },

```

- `type`: Tipo do objeto. [ string ]  
- `features`: Array de objetos de recursos. [ array de objetos ]  
- `geometry`: Objeto de geometria do recurso. [ objeto ]  
- `type`: Tipo de geometria. [ string ]  
- `coordinates`: Coordenadas da geometria. [ array de arrays de arrays de números ]  
- `ID`: ID da região. [ int ]  
- `Nome`: Nome da região. [ string ]  
- `Centro`: Informações do centro da região. [ objeto ]  
- `type`: Tipo do centro. [ string ]  
- `coordinates`: Coordenadas do centro. [ array de números ]  


# Ruas por Região

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/Region/{id}/streets` 

## Parâmetros


| Nome         | Descrição                                                                                                  |
|--------------|------------------------------------------------------------------------------------------------------------|
| id*          | int, obrigatório. O ID da região.                                                                         |
| condition_id | array de inteiros. Opcional. Os IDs de condição para filtrar as ruas. Apenas as ruas correspondentes aos IDs de condição fornecidos serão retornadas. |

Exemplo: `/api/v5/geojson/region/1/streets?condition_id=2,3`    

## Retorno status:200 - ruas de região específica seja polygon, linestring, multilinestring
```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "LineString",
        "coordinates": [
          [
            -48.4022481,
            -1.3430973
          ],
          [
            -48.401970824822,
            -1.34345044005687
          ]
        ]
      },
      "properties": {
        "id": 1582,
        "region_id": 1,
        "condition": "Trecho com alagamento ou inundação",
        "condition_id": 3,
        "color": "#0300da",
        "with": null,
        "continuous": 1,
        "line_cap": "",
        "line_dash_pattern": "",
        "name": null
      }
    },
```
- `type`: Tipo do objeto. [ string ]
- `features`: Array de objetos de recursos. [ array de objetos ]
- `geometry`: Objeto de geometria do recurso. [ objeto ]
- `type`: Tipo de geometria. [ string ]
- `coordinates`: Coordenadas da geometria. [ array de arrays de arrays de números ]
- `id`: ID da rua. [ int ]
- `region_id`: ID da região. [ int ]
- `condition`: Condição da rua. [ string ]
- `condition_id`: ID da condição da rua. [ int ]
- `color`: Cor relacionada. [ string ]
- `with`: Descrição relacionada. [ null ou string ]
- `continuous`: Descrição relacionada. [ int ]
- `line_cap`: Descrição relacionada. [ string ]
- `line_dash_pattern`: Descrição relacionada. [ string ]
- `stroke`: Cor da linha da rua. [ string ]
- `stroke-opacity`: Opacidade da linha da rua. [ número ]
- `fill-opacity`: Opacidade de preenchimento da rua. [ número ]
- `NOME_RUA`: Nome da rua. [ string ]



[Voltar a pagina principal](/README.md) 
