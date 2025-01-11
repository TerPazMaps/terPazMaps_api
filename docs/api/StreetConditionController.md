<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Condições das Ruas

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/street_condition` 

## Parâmetros
Este método não aceita nenhum parâmetro.

## Retorno status:200 - um array de condições de ruas
```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": [
                {
                    "id": 1,
                    "condition": "Rua pavimentada (asfalto)",
                    "color": "#ffad29",
                    "created_at": "2024-03-09T23:10:27.000000Z",
                    "updated_at": "2024-03-28T20:06:50.000000Z"
                },
                {
                    "id": 7,
                    "condition": "Sem tratamento",
                    "color": "#fcd1c4",
                    "created_at": "2024-03-09T23:11:26.000000Z",
                    "updated_at": "2024-03-28T20:09:39.000000Z"
                }
            ]
        }
    }
}
```


[Voltar a pagina principal](/README.md)
