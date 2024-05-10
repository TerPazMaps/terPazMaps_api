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
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": [
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
                        "id": 10,
                        "user_id": 0,
                        "name": "São",
                        "region_id": 9,
                        "subclass_id": 28,
                        "created_at": "09/05/2024 14:19:21",
                        "updated_at": "09/05/2024 14:19:21"
                    }
                }
            ]
        }
    }
}
```
## Retorno caso de erro ou usuário sem registros

```json
{
    "error": {
        "status": "401",
        "title": "Not Found",
        "detail": "Este usuário não possui registros"
    }
}
```
```json
{
    "error": {
        "status": "500",
        "title": "Internal Server Error",
        "detail": "mensagem de erro especifica"
    }
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

>[!TIP] 
> Exemplo de entrada de geojson
>
>```json
>{
>    "type": "FeatureCollection",
>    "features": [
>        {
>            "type": "Feature",
>            "geometry": {
>                "type": "Point",
>                "coordinates": [
>                    -48.334619926032,
>                    -1.3533274892472
>                ]
>            },
>            "properties": {
>                "name": "São",
>                "subclass_id": 28,
>                "region_id": 9,
>                "Centro": {
>                    "type": "Point",
>                    "coordinates": [
>                        -48.338833248539,
>                        -1.3512433726156
>                    ]
>                }
>            }
>        }        
>    ]
>}
>```

## Retorno caso sucesso

```json
{
    "success": {
        "status": "201",
        "title": "Created",
        "detail": "Salvo com sucesso"
    }
}
```
## Retorno caso haja erros de validação

```json
{
    "error": {
        "status": "422",
        "title": "Unprocessable Entity",
        "detail": {
            "InvalidFeatureCollection": [
                "O JSON deve ser um FeatureCollection."
            ]
        }
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
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "geojson": {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [
                        -48.334619926032,
                        -1.3533274892472
                    ]
                },
                "properties": {
                    "id": 5,
                    "user_id": 0,
                    "name": "São",
                    "region_id": 9,
                    "subclass_id": 28,
                    "created_at": "08/05/2024 15:47:38",
                    "updated_at": "08/05/2024 15:47:38"
                }
            }
        }
    }
}
```

## Retorno caso haja erros  

```json
{
    "error": {
        "status": "404",
        "title": "Not Found",
        "detail": "Registro não encontrado"
    }
}
```
```json
{
    "error": {
        "status": "403",
        "title": "Forbidden",
        "detail": "Usuário não tem permissão para acessar o registro"
    }
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
    "success": {
        "status": "200",
        "title": "OK",
        "detail": "Atualizado com sucesso"
    }
}
```
## Retorno caso haja erros de validação

```json
{
    "error": {
        "status": "422",
        "title": "Unprocessable Entity",
        "detail": {
            "InvalidFeatureCollection": [
                "O JSON deve ser um FeatureCollection."
            ]
        }
    }
}
```
```json
{
    "error": {
        "status": "500",
        "title": "Internal Server Error",
        "detail": "Erro ao atualizar"
    }
}
```
```json
{
    "error": {
        "status": "404",
        "title": "Not Found",
        "detail": "Registro não encontrado"
    }
}
```
```json
{
    "error": {
        "status": "403",
        "title": "Forbidden",
        "detail": "Usuário não tem permissão para acessar o registro"
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
    "success": {
        "status": "200",
        "title": "OK",
        "detail": "Deletado com sucesso"
    }
}
```
## Retorno caso de erro

```json
{
    "error": {
        "status": "404",
        "title": "Not Found",
        "detail": "Registro não encontrado"
    }
}
```
```json
{
    "error": {
        "status": "403",
        "title": "Forbidden",
        "detail": "Usuário não tem permissão para acessar o registro"
    }
}
```
```json
{
    "error": {
        "status": "500",
        "title": "Internal Server Error",
        "detail": "Erro ao deletar"
    }
}
```


[Voltar a pagina principal](/README.md)
