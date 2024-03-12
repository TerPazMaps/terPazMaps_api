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
`type`: Tipo do objeto. [ string ]  
`features`: Array de objetos de recursos. [ array de objetos ]  
`geometry`: Objeto de geometria do recurso. [ objeto ]  
`type`: Tipo de geometria. [ string ]  
`coordinates`: Coordenadas da geometria. [ array de arrays de arrays de números ]  
`ID`: ID da região. [ int ]  
`Nome`: Nome da região. [ string ]  
`Centro`: Informações do centro da região. [ objeto ]  
`type`: Tipo do centro. [ string ]  
`coordinates`: Coordenadas do centro. [ array de números ]  

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

`type`: Tipo do objeto. [ string ]  
`features`: Array de objetos de recursos. [ array de objetos ]  
`geometry`: Objeto de geometria do recurso. [ objeto ]  
`type`: Tipo de geometria. [ string ]  
`coordinates`: Coordenadas da geometria. [ array de arrays de arrays de números ]  
`ID`: ID da região. [ int ]  
`Nome`: Nome da região. [ string ]  
`Centro`: Informações do centro da região. [ objeto ]  
`type`: Tipo do centro. [ string ]  
`coordinates`: Coordenadas do centro. [ array de números ]  

[Voltar a pagina principal](/README.md) 
