<!--
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Classes

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classes`

## Parâmetros

Este método não aceita nenhum parâmetro.

> [!TIP]
> Exemplo: fff

## Retorno status:200 - um array paginado de classes

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "Comércio",
            "related_color": "#ed675f",
            "related_secondary_color": null
        },
        {
            "id": 2,
            "name": "Serviço",
            "related_color": "#f5ac6c",
            "related_secondary_color": null
        },
    ],
    "first_page_url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=2",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=2",
            "label": "2",
            "active": false
        },
        {
            "url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=2",
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": "http://127.0.0.1:8000/api/v5/geojson/classes?page=2",
    "path": "http://127.0.0.1:8000/api/v5/geojson/classes",
    "per_page": 12,
    "prev_page_url": null,
    "to": 12,
    "total": 14
}
```

---

# Classe por identificador

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classes/{id}`

## Parâmetros

<!-- Este método não aceita nenhum parâmetro. -->

| Name | Description   |
| ---- | ------------- |
| id\* | int, required |

## Retorno status:200 - uma classe especifica

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": {
                "id": 9,
                "name": "Imobiliário",
                "related_color": "#a3a3a3",
                "related_secondary_color": null
            }
        }
    }
}
```

---

# Subclasses por Classe (+icon)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classes/{id}/subclasses`

## Parâmetros

| Nome | Descrição                                                                          |
| ---- | ---------------------------------------------------------------------------------- |
| id\* | Inteiro, obrigatório. O ID da classe para recuperar as subclasses correspondentes. |

## Retorno status:200 - uma classe específica com suas subclasses e ícones

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": {
                "current_page": 1,
                "data": [
                    {
                        "id": 1,
                        "name": "Comércio",
                        "related_color": "#ed675f",
                        "created_at": "2021-09-23T15:04:35.000000Z",
                        "updated_at": "2021-09-23T16:04:20.000000Z",
                        "related_secondary_color": null,
                        "subclasse": [
                            {
                                "id": 1,
                                "class_id": 1,
                                "name": "Venda de chope e similares",
                                "related_color": null,
                                "created_at": "2021-09-23T15:04:35.000000Z",
                                "updated_at": "2021-09-23T15:04:35.000000Z",
                                "icon": {
                                    "id": 331,
                                    "subclasse_id": 1,
                                    "disk_name": "614cc55ad49fd205166190.png",
                                    "file_name": "venda_chope_similares.png",
                                    "created_at": "2021-09-23T18:20:10.000000Z",
                                    "updated_at": "2021-09-23T18:20:12.000000Z",
                                    "image_url": "http://127.0.0.1:8000/storage/614/cc5/5ad/614cc55ad49fd205166190.png"
                                }
                            },
                        ]
                    },[...]
                ],
                "first_page_url": "http://127.0.0.1:8000/api/v5/geojson/classe/1/subclasses?page=1",
                "from": 1,
                "last_page": 1,
                "last_page_url": "http://127.0.0.1:8000/api/v5/geojson/classe/1/subclasses?page=1",
                "links": [
                    {
                        "url": null,
                        "label": "&laquo; Previous",
                        "active": false
                    },
                    {
                        "url": "http://127.0.0.1:8000/api/v5/geojson/classe/1/subclasses?page=1",
                        "label": "1",
                        "active": true
                    },
                    {
                        "url": null,
                        "label": "Next &raquo;",
                        "active": false
                    }
                ],
                "next_page_url": null,
                "path": "http://127.0.0.1:8000/api/v5/geojson/classe/1/subclasses",
                "per_page": 15,
                "prev_page_url": null,
                "to": 1,
                "total": 1
            }
        }
    }
}
```


[Voltar a pagina principal](/README.md)
