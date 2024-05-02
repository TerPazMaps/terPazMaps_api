# Feedback de street do usuário 

  <!-- - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![POST](https://img.shields.io/badge/HTTP-POST-00CC00)[ /api/v5/geojson/user-custom-maps](/docs/api/UserCustomMapContoller.md)
  - ![GET](https://img.shields.io/badge/HTTP-GET-0080FF)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md)
  - ![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)[/api/v5/geojson/user-custom-maps/{id}](/docs/api/UserCustomMapContoller.md) -->
  
## Streets

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-feedback-street` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |

## Retorno caso de sucesso

```json
[
    {
        "id": 1,
        "user_id": 0,
        "street_id": 1,
        "street_condition_id": 2,
        "created_at": "02/05/2024 14:39:06",
        "updated_at": "02/05/2024 14:39:06"
    },
    {
        "id": 4,
        "user_id": 0,
        "street_id": 17,
        "street_condition_id": 2,
        "created_at": "02/05/2024 15:19:11",
        "updated_at": "02/05/2024 15:19:11"
    }
]
```
## Retorno caso de erro ou usuário sem registros

```json
{
    "message": "Este usuário não possui registros feedback de ruas"
}
```
```json
{
    "error": "Unauthorized",
    "message": "Token has expired"
}
```

## Salvar um feedback de street

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/geojson/user-feedback-street` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |
| street_id       | id da street que deseja dar o feedback [***required,exists:streets,unique:feedback_streets***]        |
| street_condition_id       | id da street_condition que atribuir a street [***required,exists:street_conditions***]        |

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
        "street_id": [
            "O campo street_id deve ser único na tabela feedback_streets."
        ]
    }
}
```
## Ver feedback por ID

![GET](https://img.shields.io/badge/HTTP-GET-0080FF) 

`/api/v5/geojson/user-feedback-street/{id}` 

## Parâmetros
Deve receber uma requisição via GET com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |


## Retorno caso sucesso

```json
[
    {
        "id": 1,
        "user_id": 0,
        "street_id": 1,
        "street_condition_id": 2,
        "created_at": "02/05/2024 14:39:06",
        "updated_at": "02/05/2024 14:39:06"
    }
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
    "message": "Este usuário não possui registros feedback de ruas"
}
```

## Atualizar feedback

![PUT](https://img.shields.io/badge/HTTP-PUT-FFFF00)

`/api/v5/geojson/user-feedback-street/{id}` 

## Parâmetros
Deve receber uma requisição via PUT ou PATCH com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o token) |
| street_condition_id       | id da street_condition que atribuir a street [***required,exists:street_conditions***]        |


## Retorno caso sucesso

```json
{
    "message": "Feedback de rua atualizado com sucesso"
}
```
## Retorno caso haja erros de validação

```json
{
    "errors": {
        "street_condition_id": [
            "O campo street_condition_id deve existir na tabela street_conditions."
        ]
    }
}
```
```json
{
    "message": "Feedback de rua não encontrado"
}
```
## Deletar feedback

![DELETE](https://img.shields.io/badge/HTTP-DELETE-FF0000)

`/api/v5/geojson/user-feedback-street/{id}` 

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
    "message": "Feedback de rua não encontrado"
}
```
```json
{
    "message": "Erro ao deletar"
}
```
