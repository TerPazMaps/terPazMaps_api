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
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |

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
                        "type": "Polygon",
                        "coordinates": [
                            [
                                [
                                    -48.334619926032,
                                    -1.3533274892472
                                ],[...]
                                [
                                    -48.334619926032,
                                    -1.3533274892472
                                ]
                            ]
                        ]
                    },
                    "properties": {
                        "ID": 4,
                        "user_ID": 0,
                        "Nome": "São Francisco",
                        "Centro": {
                            "type": "Point",
                            "coordinates": [
                                -48.338833248539,
                                -1.3512433726156
                            ]
                        },
                        "created_at": "09/05/2024 20:23:00",
                        "updated_at": "09/05/2024 20:23:00"
                    }
                },[...]
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

## Salvar mapa

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/geojson/user-custom-maps` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo

| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |
| geojson       | FeatureCollection tipo polygon [***required,String***]        |

## exemplo de entrada gsojson
>[!TIP] 
> Exemplo de entrada de geojson
>```json
>{
>    "type": "FeatureCollection",
>    "features": [
>        {
>           "type": "Feature",
>           "geometry": {
>               "type": "Polygon",
>               "coordinates": [
>                   [
>                       [
>                           -48.334619926032,
>                           -1.35332748924725
>                       ],[...]
>                   ]
>               ]
>           },
>           "properties": {
>               "ID": 9,
>               "Nome": "São Francisco",
>               "Cidade": "Marituba",
>               "Centro": {
>                   "type": "Point",
>                   "coordinates": [
>                       -48.336453437805176,
>                       -1.354496658713901
>                   ]
>               }
>           }
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
## Ver mapa por ID

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |


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
                    "type": "Polygon",
                    "coordinates": [
                        [
                            [
                                -48.334619926032,
                                -1.3533274892472
                            ],[...]
                        ]
                    ]
                },
                "properties": {
                    "ID": 3,
                    "user_ID": 0,
                    "Nome": "teste francisco",
                    "Centro": {
                        "type": "Point",
                        "coordinates": [
                            -48.338833248539,
                            -1.3512433726156
                        ]
                    },
                    "created_at": "2024-05-09T16:25:52.000000Z",
                    "updated_at": "2024-05-09T18:06:14.000000Z"
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

## Atualizar mapa

![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição via PUT ou PATCH com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |
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
## Deletar mapa

![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)

`/api/v5/geojson/user-custom-maps/{id}` 

## Parâmetros
Deve receber uma requisição com verbo HTTP: DELETE com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |


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
