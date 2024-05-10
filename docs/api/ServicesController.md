# Serviços

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->

## Pesquisa de atividades por área

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/activities-nearby`

## Parâmetros

| Nome        | Descrição                                   |
| ----------- | ------------------------------------------- |
| region_id   | Região onde sera feita a busca. [ **int** ] |
| subclass_id | Subclasses pesquisadas [ **array** ]        |
| latitude    | coordenada. [ **float** ]                   |
| longitude   | coordenada. [ **float** ]                   |
| raio        | Raio de busca. [ **int** ]                  |

Exemplo:  
api/v5/geojson/services/activities-nearby?region_id=7&subclass_id=7&raio=50&latitude=-1.465815&longitude=-48.459401

## Retorno caso de sucesso

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
                            "type": "Point",
                            "coordinates": [
                                -48.4626158023459,
                                -1.46317686702397
                            ]
                        },
                        "properties": {
                            "id": 11124,
                            "region_id": 7,
                            "subclass_id": 28,
                            "name": ""
                        }
                    },{...},
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [
                                        -48.432401,
                                        -1.465815
                                    ],
                                    [
                                        -48.432401,
                                        -1.465815
                                    ],[...]
                                ]
                            ]
                        },
                        "properties": {
                            "raio": "3000"
                        }
                    }
                ]
            }
        }
    }
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

`/api/v5/geojson/services/points-of-interest`

## Parâmetros

| Nome           | Descrição                                                   |
| -------------- | ----------------------------------------------------------- |
| region_id      | Região onde sera feita a busca. [ **int** ]                 |
| referenciaId   | Referencias para busca(no raio a partir de lá). [ **int** ] |
| pontoBuscadoId | array de pontos pesquisados. [ **array** ]                  |
| raio           | Raio de busca. [ **int >= 6** ]                                  |

Exemplo: api/v5/geojson/services/points-of-interest?region_id=1&referenciaId=7&pontoBuscadoId=28&raio=100

## Retorno caso de sucesso

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
        }
    }
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

## Calculo de comprimento de uma rua(por ID)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)

`/api/v5/geojson/services/length-street`

## Parâmetros

| Nome      | Descrição                                                |
| --------- | -------------------------------------------------------- |
| street_id | ID da rua que deseja calcular o comprimento. [ **int** ] |

Exemplo: api/v5/geojson/services/length-street?street_id=1

## Retorno caso de sucesso

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "length": "198.86",
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
api/v5/geojson/services/buffer?latitude=-1.34538115355059&longitude=-48.4045690844909&raio=90

## Retorno caso de sucesso

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": {
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
        }
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
