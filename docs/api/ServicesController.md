# Serviços

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->

## Pesquisa de atividades por área

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/activities-nearbyPG`

## Parâmetros

| Nome        | Descrição                                   |
| ----------- | ------------------------------------------- |
| region_id   | Região onde sera feita a busca. [ **int** ] |
| subclass_id | Subclasses pesquisadas [ **array** ]        |
| latitude    | coordenada. [ **float** ]                   |
| longitude   | coordenada. [ **float** ]                   |
| raio        | Raio de busca. [ **int** ]                  |

Exemplo:    
api/v5/geojson/services/activities-nearbyPG?region_id=7&subclass_id=17,30,44,59,75,99,135,145,156,170&raio=1802&latitude=-1.4653&longitude=-48.4616  

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
                    -48.471863767,
                    -1.460782629
                ]
            },
            "properties": {
                "id": 7763,
                "region_id": 7,
                "subclass_id": 75,
                "name": "AGE"
            }
        },
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [
                    -48.470320301,
                    -1.469074684
                ]
            },
            "properties": {
                "id": 7798,
                "region_id": 7,
                "subclass_id": 17,
                "name": "Centro de Estudos O Bem Estar da Criança"
            }
        },[...]
}
```

## Retorno caso de erro ou sem pontos próximos (**retorna apenas a area de pesquisa como um polygono**)

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": {
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        -48.459401,
                                        -1.465815
                                    ],[...] 
								]
                            ]
                        },
                        "properties": {
                            "raio": "3"
                        }
                    }
                ]
            }
        }
    }
}
```

## Pesquisa de pontos de interesse próximos de outros pontos(Exemplo: escolas próximas de igrejas)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/points-of-interestPG`

## Parâmetros

| Nome           | Descrição                                                   |
| -------------- | ----------------------------------------------------------- |
| region_id      | Região onde sera feita a busca. [ **int** ]                 |
| referenciaId   | Referencias para busca(no raio a partir de lá). [ **int** ] |
| pontoBuscadoId | array de pontos pesquisados. [ **array** ]                  |
| raio           | Raio de busca. [ **int >= 6** ]                                  |

Exemplo: api/v5/geojson/services/points-of-interestPG?region_id=7&referenciaId=30&pontoBuscadoId=17,143,44&raio=500

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
                    -48.4023620247111,
                    -1.34480607595236
                ]
            },
            "properties": {
                "id": 18,
                "region_id": 1,
                "subclass_id": 7,
                "name": "Igreja Quadrangular Tabernáculo dos Milagres",
                "marker-color": "#FF0000"
            }
        },{...}
    ]
}
```

## Retorno caso de erro ou sem pontos próximos

```json
{
    "error": {
        "status": "404",
        "title": "Not Found",
        "detail": "Sem pontos de interesse próximo"
    }
}
```


## Pesquisa de pontos de interesse em areas de dificil acesso (em ruas de dificil acesso)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/difficult-access-activitiesPG`

## Parâmetros

| Nome           | Descrição                                                   |
| -------------- | ----------------------------------------------------------- |
| region_id      | Região onde sera feita a busca. [ **int** ]                 |
| subclass[]     | Subclasses pesquisadas. [ **int** ]                         |
| condidtion[]   | Condição de rua pesquisadas [ **int** ]                     |

Exemplo: api/v5/geojson/services/difficult-access-activitiesPG?region_id=1&subclass[]=29&subclass[]=28&condition[]=5&condition[]=6  

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
                    -48.398141459,
                    -1.340611121
                ]
            },
            "properties": {
                "id": 360,
                "region_id": 1,
                "subclass_id": 28,
                "name": "",
                "type": "activity"
            }
        },[...]
    ]
}
```

## Retorno caso de erro ou sem pontos nos parametros

```json
{
    "error": {
        "status": "404",
        "title": "Not Found",
        "detail": "Sem pontos de interesse próximo"
    }
}
```


## Pesquisa atividades e seus raios de influência (4 raios), tambem funciona em pontos enviados pelo usuario(não existentes no banco de dados)  

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/bufferSumPG`

## Parâmetros

| Nome           | Descrição                                                   |
| -------------- | ----------------------------------------------------------- |
| region_id      | Região onde sera feita a busca. [ **int** ]                 |
| subclass     | Subclasses pesquisadas. [ **int** ]                         |
| raio   | 4 valores de raios [ **string** ]                     |
| newActivities[]   | Pontos que nçao existem no banco de dados (escolha do usuário) [ **string** ]                     |

Exemplo: api/v5/geojson/services/bufferSumPG?region_id=1&subclass=20&raio=250,500,750,1000&newActivities[]=-1.3230,-48.4019&newActivities[]=-1.3241,-48.4178  


## Retorno caso de sucesso

```json
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "properties": {
                "stroke": "#ff0000",
                "stroke-width": 2,
                "stroke-opacity": 1,
                "fill": "#ff0000",
                "fill-opacity": 0.2
            },
            "geometry": {
                "type": "Polygon",
                "coordinates": [
                    [
                        [
                            -48.410089,
                            -1.356806357
                        ],[...]
                    ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "name": "Nova Construção (-1.3241, -48.4178)"
            },
            "geometry": {
                "type": "Point",
                "coordinates": [
                    -48.4178,
                    -1.3241
                ]
            }
        },
        {
            "type": "Feature",
            "properties": {
                "name": ""
            },
            "geometry": {
                "type": "Point",
                "coordinates": [
                    -48.410305471,
                    -1.339716936
                ]
            }
        },
    ]
}   
```

## Retorno caso de erro ou sem pontos nos parametros

```json
{
    "error": {
        "status": "500",
        "title": "Internal Server Error",
        "detail": "Undefined variable $points"
    }
}
```


## Calculo de comprimento de uma rua(por ID)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/length-street`

## Parâmetros

| Nome      | Descrição                                                |
| --------- | -------------------------------------------------------- |
| street_id | ID da rua que deseja calcular o comprimento. [ **int** ] |

Exemplo: api/v5/geojson/services/length-street?street_id=494

## Retorno caso de sucesso

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "length": "42.93",
            "unit": "metros",
            "type": "Linestring"
        }
    }
}
```

---   

## Calculo de distância entre dois pontos.

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/distance`

## Parâmetros

| Nome | Descrição                           |
| ---- | ----------------------------------- |
| lat  | latitude do ponto 1. [ **float** ]  |
| lon  | Longitude do ponto 1. [ **float** ] |
| lat2 | latitude do ponto 2. [ **float** ]  |
| lon2 | Longitude do ponto 2. [ **float** ] |

Exemplo:  
api/v5/geojson/services/distance?lat=-1.34538115355059&lon=-48.4045690844909&lat2=-1.34519276971018&lon2=-48.4041343555742  

## Retorno caso de sucesso

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "distance": "52.7 metros"
        }
    }
}
```

---

## Calculo de buffer(raio de influência de um ponto).

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/buffer`

## Parâmetros

| Nome      | Descrição                            |
| --------- | ------------------------------------ |
| latitude  | Latitude do ponto. [ **float** ]     |
| longitude | Longitude do ponto. [ **float** ]    |
| raio      | Define o raio do buffer. [ **int** ] |

Exemplo:  
api/v5/geojson/services/buffer?latitude=-1.34538115355059&longitude=-48.4045690844909&raio=7000  

## Retorno caso de sucesso

```json
{
    "type": "Feature",
    "geometry": {
        "type": "Polygon",
        "coordinates": [
            [
                [
                    -48.4041690844909,
                    -1.34538115355059
                ],[...],
                [
                    -48.4041690844909,
                    -1.34538115355059
                ]
            ]
        ]
    },
    "properties": {
        "raio": 6
    }
}
```

## Retorno caso de erro

```json
{
    "error": {
        "status": "422",
        "title": "Unprocessable Entity",
        "detail": "O raio deve ser maior ou igual a 6 metros."
    }
}
```

[Voltar a pagina principal](/README.md)
