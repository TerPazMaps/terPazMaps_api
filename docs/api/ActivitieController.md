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

## Retorno status:200 - lista de atividades

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [longitude, latitude]
      },
      "properties": {
        "ID Geral": 1,
        "Nome": "Nome da Atividade",
        "ID Subclasse": 1,
        "ID Bairro": 1,
        "Nível": "Nível da Atividade"
      }
    }
    // Mais features aqui
  ]
}

`ID Geral`: ID da atividade. [ int ]
`Nome`: Nome da atividade. [ string ]
`ID Subclasse`: ID da subclasse da atividade. [ int ]
`ID Bairro`: ID do bairro da atividade. [ int ]
`Nível`: Nível da atividade. [ string ]

[Voltar a pagina principal](/README.md)
