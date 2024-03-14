<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->

# Subclasses

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/subclasse` 

## Parâmetros
| Nome          | Descrição                                                                  |
|---------------|----------------------------------------------------------------------------|
| name       | pesquina de subclasses com esse nome  [ **string** ]         |

## Retorno status:200 - um array de subclasses
```json
[
  {
    "id": 331,
    "subclasse_id": 1,
    "disk_name": "614cc55ad49fd205166190.png",
    "file_name": "venda_chope_similares.png",
    "file_size": 3717,
    "content_type": "image/png",
    "title": null,
    "description": null,
    "field": "related_icon",
    "attachment_type": "BitsEBytes\\MapasDigitais\\Models\\ActivitySubclass",
    "is_public": true,
    "sort_order": 331,
    "created_at": "2021-09-23T18:20:10.000000Z",
    "updated_at": "2021-09-23T18:20:12.000000Z",
    "image_url": "http://localhost/storage/614/cc5/5ad/614cc55ad49fd205166190.png",
    "subclasse": {
      "id": 1,
      "class_id": 1,
      "name": "Venda de chope e similares",
      "related_color": null,
      "created_at": "2021-09-23T15:04:35.000000Z",
      "updated_at": "2021-09-23T15:04:35.000000Z"
    }
  }
]
```

`ID`: ID do ícone. [ **int** ]  
`subclasse_id`: ID da subclasse. [ **int** ]  
`disk_name`: Nome do arquivo no disco. [ **string** ]  
`file_name`: Nome do arquivo. [ **string** ]  
`file_size`: Tamanho do arquivo em bytes. [ **int** ]  
`content_type`: Tipo de conteúdo do arquivo. [ **string** ]  
`title`: Título do ícone. [ **string** ]  
`description`: Descrição do ícone. [ **string** ]  
`field`: Campo relacionado do ícone. [ **string** ]  
`attachment_type`: Tipo de anexo do ícone. [ **string** ]  
`is_public`: Indicador se o ícone é público. [ **boolean** ]  
`sort_order`: Ordem de classificação do ícone. [ **int** ]  
`created_at`: Data de criação do ícone. [ **string** ]  
`updated_at`: Data de atualização do ícone. [ **string** ]  
`image_url`: URL para acessar a imagem do ícone. [ **string** ]  
`subclasse.id`: ID da subclasse relacionada. [ **int** ]  
`subclasse.class_id`: ID da classe relacionada. [ **int** ]  
`subclasse.name`: Nome da subclasse. [ **string** ]  
`subclasse.related_color`: Cor relacionada da subclasse. [ **string** ]  
`subclasse.created_at`: Data de criação da subclasse. [ **string** ]  
`subclasse.updated_at`: Data de atualização da subclasse. [ **string** ]  

.

[Voltar a pagina principal](/README.md) 
