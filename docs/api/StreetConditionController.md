<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Condições das Ruas

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/street_condition/` 

## Parâmetros
Este método não aceita nenhum parâmetro.

## Retorno status:200 - um array de condições de ruas
```json
[
  {
    "id": 1,
    "name": "Pavimentada"
  },
  {
    "id": 2,
    "name": "Não pavimentada"
  },
  {
    "id": 3,
    "name": "Em construção"
  }
]
```
- `id`: ID da condição da rua. [ int ]
- `name`: Nome da condição da rua. [ string ]

[Voltar a pagina principal](/README.md)
