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


# Subclasses por Classe (+icon)

![GET](https://img.shields.io/badge/HTTP-GET-0080FF)  
`/api/v5/geojson/classe/{id}/subclasses` 

## Parâmetros

| Nome    | Descrição                                                                                                                                 |
|---------|----------------------------------------------------------------------------------------------------------------------------------------------|
| id*     | Inteiro, obrigatório. O ID da classe para recuperar as subclasses correspondentes.                                                                 |


## Retorno status:200 - uma classe específica com suas subclasses e ícones

```json
{
  "id": 6,
  "name": "Esporte/desporto",
  "related_color": "#6aab4f",
  "created_at": "2021-09-23T15:04:35.000000Z",
  "updated_at": "2021-09-23T18:06:45.000000Z",
  "related_secondary_color": null,
  "subclasse": [
    {
      "id": 15,
      "class_id": 6,
      "name": "Arena esportiva",
      "related_color": null,
      "created_at": "2021-09-23T15:04:35.000000Z",
      "updated_at": "2021-09-23T15:04:35.000000Z",
      "icon": [
        {
          "id": 346,
          "subclasse_id": 15,
          "disk_name": "614cc6bc9734a411287997.png",
          "file_name": "arena_esporte.png",
          "file_size": 4474,
          "content_type": "image/png",
          "title": null,
          "description": null,
          "field": "related_icon",
          "attachment_type": "BitsEBytes\\MapasDigitais\\Models\\ActivitySubclass",
          "is_public": true,
          "sort_order": 346,
          "created_at": "2021-09-23T18:26:04.000000Z",
          "updated_at": "2021-09-23T18:26:05.000000Z",
          "image_url": "http://localhost/storage/614/cc6/bc9/614cc6bc9734a411287997.png"
        }
      ]
    },
```

# Classe
`ID:` ID da classe. [ **int** ]  
`name`: Nome da classe.  [ **string** ]  
`related_color`: Cor relacionada à classe.[ **string** ]  
`related_secondary_color`: Cor secundária relacionada à classe.[ **string** ]  

## Subclasse
`ID:` ID da subclasse. [ **int** ]  
`class_id:` ID da classe a que pertence a subclasse. [ **int** ]  
`name`: Nome da subclasse.  [ **string** ]  
`related_color`: Cor relacionada à subclasse. [ **string** ]  
`created_at`: Data de criação da subclasse. [ **string (timestamp)** ]  
`updated_at`: Data da última atualização da subclasse. [ **string (timestamp)** ]  

### Ícone: arena_esporte.png
`ID:` ID do ícone. [ **int** ]  
`ID da Subclasse:` ID da subclasse a que pertence o ícone. [ **int** ]  
`Nome do Disco:` Nome do disco onde o arquivo do ícone está armazenado. [ **string** ]  
`Nome do Arquivo:` Nome do arquivo do ícone. [ **string** ]  
`Tamanho do Arquivo:` Tamanho do arquivo do ícone em bytes. [ **int** ]  
`Tipo de Conteúdo:` Tipo de conteúdo do arquivo do ícone. [ **string** ]  
`Título:` Título do ícone. [ **string** ]  
`Descrição:` Descrição do ícone. [ **string** ]  
`Campo:` Campo relacionado ao ícone. [ **string** ]  
`Tipo de Anexo:` Tipo de anexo relacionado ao ícone. [ **string** ]  
`Público:` Indica se o ícone é público. [ **boolean** ]  
`Ordem de Classificação:` Ordem de classificação do ícone. [ **int** ]  
`Data de criação:` Data de criação do ícone. [ **string (timestamp)** ]  
`Última atualização:` Data da última atualização do ícone. [ **string (timestamp)** ]  
`URL da Imagem:` URL para acessar a imagem do ícone. [ **string** ]  




[Voltar a pagina principal](/README.md) 
