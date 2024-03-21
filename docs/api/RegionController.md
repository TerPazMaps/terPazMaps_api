<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Regiões


![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/region` 

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
`/api/v5/geojson/region/{id}` 

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
`/api/v5/geojson/region/{id}/streets` 

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


# Ícones por Região

[![GET](https://img.shields.io/badge/HTTP-GET-0080FF)](/api/v5/geojson/Region/{id}/icons)  
`/api/v5/geojson/region/{id}/icons` 

## Parâmetros

| Nome         | Descrição                                                                                                  |
|--------------|------------------------------------------------------------------------------------------------------------|
| id*          | int, obrigatório. O ID da região.                                                                         |
| class_id     | array, opcional. IDs de classe separados por vírgula. Apenas as atividades correspondentes às classes fornecidas serão retornadas. |

Exemplo: `/api/v5/geojson/region/1/icons?class_id=2,3`    

## Retorno status:200 - ícones de região específica

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [
          -48.3385533347598,
          -1.34779343816126
        ]
      },
      "properties": {
        "id": 1831,
        "name": "Assembleia de Deus Congregação Betel",
        "subclass": {
          "id": 7,
          "class_id": 3,
          "name": "Igreja evangélica",
          "icon": {
            "id": 338,
            "disk_name": "614cc60cc25c2845391616.png",
            "file_name": "igreja_evangelica.png",
            "file_size": 1482,
            "content_type": "image/png",
            "is_public": true,
            "sort_order": 338,
            "img_url": "http://127.0.0.1:8000/storage/614/cc6/0cc/614cc60cc25c2845391616.png"
          }
        }
      }
    },
  ]
}
```

- `type`: Tipo de geometria. [ string ]
- `coordinates`: Coordenadas da geometria. [ array de números ]
- `id`: ID da atividade. [ int ]
- `name`: Nome da atividade. [ string ]
- `subclass`: Objeto de subclasse relacionado. [ objeto ]
- `id`: ID da subclasse. [ int ]
- `class_i`d: ID da classe à qual a subclasse pertence. [ int ]
- `name`: Nome da subclasse. [ string ]
- `icon`: Objeto de ícone relacionado. [ objeto ]
- `id`: ID do ícone. [ int ]
- `disk_name`: Nome do arquivo do ícone no disco. [ string ]
- `file_name`: Nome do arquivo do ícone. [ string ]
- `file_size`: Tamanho do arquivo do ícone. [ int ]
- `content_type`: Tipo de conteúdo do ícone. [ string ]
- `is_public`: Indicação de se o ícone é público ou não. [ boolean ]
- `sort_order`: Ordem de classificação do ícone. [ int ]
- `img_url`: URL da imagem do ícone. [ string ]

[Voltar a pagina principal](/README.md) 
