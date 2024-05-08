# Instruções de autenticação(com uso de JWT)  
## Registro de Usuário

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/register` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| name       | Nome do usuário [***required,String,min:3,max:255***]        |
| email    | Email que será usado para login [***required,email,unique***]|
| password           | senha [***required,min:8***] |
| password_confirmation | confirmação da senha [***required,igual_a_password***] |

## Retorno caso haja erros de validação

```json
{
    "error": {
        "status": "400",
        "title": "Bad Request",
        "detail": {
            "name": [
                "O campo nome é obrigatório."
            ],
            "password": [
                "As senhas não são iguais."
            ]
        }
    }
}
```

## Retorno caso de sucesso


```json
{
    "success": {
        "status": "201",
        "title": "Created",
        "detail": {
            "name": "tedsdsd",
            "email": "teste@teste44",
            "updated_at": "2024-05-08T14:03:51.000000Z",
            "created_at": "2024-05-08T14:03:51.000000Z",
            "id": 6
        }
    }
}
```

## Login de usuário

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/login` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| email    | Email que será usado para login [***required,email,unique***]|
| password           | senha [***required,min:8***] |

## Retorno caso haja erros de validação

```json
{
    "erro": "Erro de usuário ou senha"
}
```

## Retorno caso de sucesso

Token com tempo de vida de 120 minutos

```json
"Token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L2xvZ2luIiwiaWF0IjoxNzEyNzgxNjI5LCJleHAiOjE3MTI3ODg4MjksIm5iZiI6MTcxMjc4MTYyOSwianRpIjoiS1hCcUQ5UVM1QmdNMlpZTCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.kSZAVXcmafKhdQp5wYj57Uli2YYCYIZ4AmAxcsCl8-8"
```

## Logout de usuário

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/logout` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |
|||

exemplo de um name   
Authorization =   
"bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L2xvZ2luIiwiaWF0IjoxNzEyNzgyMjUyLCJleHAiOjE3MTI3ODk0NTIsIm5iZiI6MTcxMjc4MjI1MiwianRpIjoiTTY4NWRMV0F6T3JLMU53UyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.nyXG9To5aLFoSDMOE1VASO8_GN7_LLtU9KmNTfpTb18"

## Retorno caso de erro

```json
{
    "message": "The token has been blacklisted",

```
OBS: Outros erros podem ser retornados de acordo com o que foi enviado, certifiquesse de fazer a requisição corretamante.

## Retorno caso de sucesso

O Token enviado na requisição é invalidado com o logout.

```json

{"msg":"Logout foi realizado com sucesso"}

```
## Refresh de usuário(token)

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/refresh` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |
|||


## Retorno caso erros

```json
{
    "message": "The token has been blacklisted",

```
OBS: use somente uma refresh para cada Token, apos isso tem um tempo muito grade para poder dar refresh no mesmo Token, é mais recomandavel solicitar um novo via login.

## Retorno caso de sucesso

O Token enviado na requisição recebe mais tempo util(mais 120 minutos de duração).

```json

{
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L3JlZnJlc2giLCJpYXQiOjE3MTI3ODI3NDEsImV4cCI6MTcxMjc4OTk1MywibmJmIjoxNzEyNzgyNzUzLCJqdGkiOiJ6QTd4MXp4dXRlRk5FNnVhIiwic3ViIjoiMSIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.O-4u4-UoqGBkC4uPUFHzGwvYsQZKamr6yMrPaXdmW7w"
}

```
## Me

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/me` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |
|||


## Retorno caso de erro

```json
{
    "message": "The token has been blacklisted",

```


## Retorno caso de sucesso

Retorna dados do usuário que esta logado

```json

{
    "id": 1,
    "name": "israel",
    "email": "israel@silvaa",
    "email_verified_at": null,
    "created_at": null,
    "updated_at": null
}

```