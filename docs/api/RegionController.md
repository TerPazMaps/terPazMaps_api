<!--
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Regiões

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/regions`

## Parâmetros

Este método não aceita nenhum parâmetro.

## Retorno status:200

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
                                        -48.4025469110289,
                                        -1.30916980179879
                                    ],
                                    [...]
                                ]
                            ]
                        },
                        "properties": {
                            "ID": 9,
                            "Nome": "São Francisco",
                            "Centro": {
                                "type": "Point",
                                "coordinates": [
                                    -48.336453437805176,
                                    -1.354496658713901
                                ]
                            }
                        }
                    }
                ]
            }
        }
    }
}
```

---

# Região por identificador

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/regions/{id}`

## Parâmetros

<!-- Este método não aceita nenhum parâmetro. -->

| Name | Description   |
| ---- | ------------- |
| id\* | int, required |

## Retorno status:200 - uma região especifica

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
                                -48.334619926032,
                                -1.35332748924725
                            ],[...]
                        ]
                    ]
                },
                "properties": {
                    "ID": 9,
                    "Nome": "São Francisco",
                    "Cidade": "Marituba",
                    "Centro": {
                        "type": "Point",
                        "coordinates": [
                            -48.336453437805176,
                            -1.354496658713901
                        ]
                    }
                }
            }
        }
    }
}
```

---

# Ruas por Região

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/regions/{id}/streets`

## Parâmetros

| Nome         | Descrição                                                                                                                                             |
| ------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------- |
| id\*         | int, obrigatório. O ID da região.                                                                                                                     |
| condition_id | array de inteiros. Opcional. Os IDs de condição para filtrar as ruas. Apenas as ruas correspondentes aos IDs de condição fornecidos serão retornadas. |

Exemplo: `/api/v5/geojson/regions/1/streets?condition_id=2,3`

## Retorno status:200 - ruas de região específica seja polygon, linestring, multilinestring

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
                                        -48.4125847104034,
                                        -1.34082207468804
                                    ],[...]
                                ]
                            ]
                        },
                        "properties": {
                            "id": 3263,
                            "region_id": 1,
                            "condition": "Trecho obstruído (vegetação ou entulho)",
                            "condition_id": 5,
                            "color": "#232323",
                            "with": null,
                            "continuous": 0,
                            "line_cap": "square",
                            "line_dash_pattern": "[3,20]",
                            "stroke": "#ff0000",
                            "stroke-opacity": 1,
                            "fill-opacity": 0,
                            "NOME_RUA": "PASSAGEM CABRAL"
                        }
                    }
                ]
            }
        }
    }
}
```

---

# Ícones por Região

[![GET](https://img.shields.io/badge/HTTP-GET-0080FF)](/api/v5/geojson/Region/{id}/icons)  
`/api/v5/geojson/regions/{id}/icons`

## Parâmetros

| Nome     | Descrição                                                                                                                          |
| -------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| id\*     | int, obrigatório. O ID da região.                                                                                                  |
| class_id | array, opcional. IDs de classe separados por vírgula. Apenas as atividades correspondentes às classes fornecidas serão retornadas. |

Exemplo: `/api/v5/geojson/regions/1/icons?class_id=2,3`

## Retorno status:200 - ícones de região específica

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
                                -48.4045690844909, -1.34538115355059
                            ]
                        },
                        "properties": {
                            "id": 11132,
                            "name": "Usina da Paz - Icuí",
                            "region_id": 1,
                            "subclass": {
                                "id": 22,
                                "class_id": 8,
                                "name": "Assistência social",
                                "icon": {
                                    "id": 522,
                                    "subclasse_id": 22,
                                    "file_name": "assistencia_social.png",
                                    "img_url": "http://127.0.0.1:8000/storage/616/f4b/f74/616f4bf742eba244972486.png"
                                }
                            }
                        }
                    }, [...]
                ]
            }
        }
    }
}
```

[Voltar a pagina principal](/README.md)
