<!--
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Icones

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/icon`

## Parâmetros

Este método não aceita nenhum parâmetro.

> [!TIP]
> Exemplo: fff

## Retorno status:200 - um array de ícones com sua subclasse.

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": [
                {
                    "id": 456,
                    "subclasse_id": 125,
                    "disk_name": "614cd60be5bac768231550.png",
                    "file_name": "venda_chope.png",
                    "created_at": "2021-09-23T19:31:23.000000Z",
                    "updated_at": "2021-09-23T19:31:25.000000Z",
                    "image_url": "http://127.0.0.1:8000/storage/614/cd6/0be/614cd60be5bac768231550.png",
                    "subclasse": {
                        "id": 125,
                        "class_id": 1,
                        "name": "Venda de chope, gelo e similares",
                        "related_color": null,
                        "created_at": "2021-09-23T15:43:05.000000Z",
                        "updated_at": "2021-09-23T15:43:05.000000Z"
                    }
                }
            ]
        }
    }
}
```


[Voltar a pagina principal](/README.md)
