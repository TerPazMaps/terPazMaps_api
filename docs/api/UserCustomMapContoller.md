# Mapas customizados do usuario 

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->
  
## Mapas

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-custom-maps` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Tokem valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o tokem) |

## Retorno caso de sucesso

```json
[
    {
        "type": "Feature",
        "geometry": {
            "type": "Polygon",
            "coordinates": [
                [
                    [
                        -48.334619926032,
                        -1.3533274892472
                    ],
                    [
                        -48.334619926032,
                        -1.3533274892472
                    ]
                ]
            ]
        },
        "properties": {
            "ID": 10,
            "user_ID": 0,
            "Nome": "São Francisco",
            "Centro": {
                "type": "Point",
                "coordinates": [
                    -48.338833248539,
                    -1.3512433726156
                ]
            },
            "created_at": "28/04/2024 21:40:46",
            "updated_at": "30/04/2024 22:56:45"
        }
    },
```
## Retorno caso de erro ou usuario sem registros

```json
{
    "message": "Este usuário não possui registros de mapas personalizados"
}
```
```json
{
    "error": "Unauthorized",
    "message": "Token has expired"
}
```

## Salvar mapa

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/geojson/user-custom-maps` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Tokem valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o tokem) |
| geojson       | FeatureCollection tipo polygon [***required,String***]        |

## Retorno caso sucesso

```json
{
    "message": "Salvo com sucesso"
}
```
## Retorno caso haja erros de validação

```json
{
    "errors": {
        "InvalidFeatureCollection": [
            "O JSON deve ser um FeatureCollection."
        ]
    }
}
```
## Ver mapa por ID

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Tokem valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o tokem) |


## Retorno caso sucesso

```json
[
    {
        "type": "Feature",
        "geometry": {
            "type": "Polygon",
            "coordinates": [
                [
                    [
                        -48.334619926032,
                        -1.3533274892472
                    ],
                    [
                        -48.334619926032,
                        -1.3533274892472
                    ]
                ]
            ]
        },
        "properties": {
            "ID": 10,
            "user_ID": 0,
            "Nome": "São Francisco",
            "Centro": {
                "type": "Point",
                "coordinates": [
                    -48.338833248539,
                    -1.3512433726156
                ]
            },
            "created_at": "28/04/2024 21:40:46",
            "updated_at": "30/04/2024 22:56:45"
        }
    },
]
```

## Retorno caso haja erros  

```json
{
    "error": "Unauthorized",
    "message": "Token has expired"
}
```
```json
{
    "message": "Mapa não encontrado"
}
```

## Atualizar mapa

![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição via PUT ou PATCH com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Tokem valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o tokem) |
| geojson       | FeatureCollection tipo polygon [***required,String***]        |


## Retorno caso sucesso

```json
{
    "message": "Salvo com sucesso"
}
```
## Retorno caso haja erros de validação

```json
{
    "errors": {
        "InvalidFeatureCollection": [
            "O JSON deve ser um FeatureCollection."
        ]
    }
}
```
## Deletar mapa

![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição com verbo HTTP: DELETE com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Tokem valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o tokem) |


## Retorno caso sucesso

```json
{
    "message": "Deletado com sucesso"
}
```
## Retorno caso de erro

```json
{
    "error": "Unauthorized",
    "message": "Token has expired"
}
```
```json
{
    "message": "Mapa não encontrado"
}
```
