<!-- 
![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  
![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)  
![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)   -->


# Classes


![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classe` 

## Parâmetros
 Este método não aceita nenhum parâmetro.

<!-- | Name    | Description                                                                                                                                 |
|---------|----------------------------------------------------------------------------------------------------------------------------------------------|
| orderBy |  use o parâmetro orderBy com o valor `name`. | -->

## Retorno status:200 - um array de classes
```json
[
  {
    "Classe": {
      "ID": 1,
      "Nome": "Nome da Classe",
      "related_color": "#FFFFFF",
      "related_secondary_color": "#CCCCCC"
    }
  },
]

```

`ID:` ID da classe. [ **int** ]  
`Nome`: Nome da classe.  [ **string** ]  
`related_color`: Cor relacionada à classe.[ **string** ]  
`related_secondary_color`: Cor secundária relacionada à classe.[ **string** ]  

.  

# Classe por identificador


![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classe/{id}` 

## Parâmetros

<!-- Este método não aceita nenhum parâmetro. -->

| Name    | Description                                                                                                                                 |
|---------|----------------------------------------------------------------------------------------------------------------------------------------------|
| id* |  int, required  |



## Retorno status:200 - uma classe especifica
```json
[
  {
    "Classe": {
      "ID": 1,
      "Nome": "Nome da Classe",
      "related_color": "#FFFFFF",
      "related_secondary_color": "#CCCCCC"
    }
  }
]

```

`ID:` ID da classe. [ **int** ]  
`Nome`: Nome da classe.  [ **string** ]  
`related_color`: Cor relacionada à classe.[ **string** ]  
`related_secondary_color`: Cor secundária relacionada à classe.[ **string** ]  

[Voltar a pagina principal](/README.md) 
