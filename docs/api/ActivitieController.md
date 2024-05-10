<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->


# Lista de Atividades Geoespaciais

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/activities` 

## Parâmetros

| Nome          | Descrição                                                                  |
|---------------|----------------------------------------------------------------------------|
| regions       | Lista de IDs das regiões para filtrar as atividades. [ **array** ]         |
| subclasses    | Lista de IDs das subclasses para filtrar as atividades. [ **array** ]      |
| ids           | Lista de IDs das atividades para filtrar as atividades. [ **array** ]      |
| only_references | Parâmetro booleano para retornar apenas as referências das atividades. [ **boolean** ] |


> [!TIP]
>  Exemplo: /api/v5/geojson/activitie?regions=1&subclasses=1&ids=850&only_references=1

## Retorno status:200 - lista de atividades

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
                        "time": "25.2669",
                        "type": "Feature",
                        "geometry": {
                            "type": "Point",
                            "coordinates": [
                                -48.4136999152688,
                                -1.32204767843368
                            ]
                        },
                        "properties": {
                            "ID Geral": 850,
                            "Nome": "",
                            "ID Subclasse": 1,
                            "ID Bairro": 1,
                            "Nível": 2
                        }
                    }
                ]
            }
        }
    }
}
```

[Voltar a pagina principal](/README.md)
