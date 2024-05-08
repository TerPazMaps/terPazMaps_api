# Feedback de activities do usuário 

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->
  
## Activities

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-feedback-activitie` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |

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
## Retorno caso de erro ou usuário sem registros

```json
{
    "message": "Este usuário não possui registros feedbacks de activities"
}
```
```json
{
    "error": "Unauthorized",
    "message": "Token has expired"
}
```

## Salvar um feedback de activitie

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/geojson/user-feedback-activitie` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo

| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |
| geojson       | FeatureCollection tipo polygon [***required,String***]        |

## exemplo de entrada de geojson

```json
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [
                    -48.334619926032,
                    -1.3533274892472
                ]
            },
            "properties": {
                "name": "São",
                "subclass_id": 28,
                "region_id": 9,
                "Centro": {
                    "type": "Point",
                    "coordinates": [
                        -48.338833248539,
                        -1.3512433726156
                    ]
                }
            }
        }        
    ]
}
```

## Retorno caso sucesso

```json
{
    "success": {
        "status": "201",
        "title": "Created",
        "detail": "Feedback da atividade salvo com sucesso"
    }
}
```
## Retorno caso haja erros de validação

```json
{
    "errors": {
        "InvalidGeometry": [
            "A geometria deve ser um Point."
        ]
    }
}
```
## Ver feedback por ID

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-feedback-activitie/{id}` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |


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
    "message": "Este usuário não possui registros feedbacks de activities"
}
```

## Atualizar feedback

![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)

`/api/v5/geojson/user-feedback-activitie/{id}` 

## Parâmetros
Deve receber uma requisição via PUT ou PATCH com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |
| geojson       | FeatureCollection tipo polygon [***required,String***]        |


## Retorno caso sucesso

```json
{
    "message": "Atividade atualizada com sucesso"
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
## Deletar feedback

![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)

`/api/v5/geojson/user-feedback-activitie/{id}` 

## Parâmetros
Deve receber uma requisição com verbo HTTP: DELETE com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |


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
    "message": "Atividade não encontrada"
}
```
```json
{
    "message": "Erro ao deletar"
}
```
