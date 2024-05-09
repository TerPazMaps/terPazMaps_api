# Instruções de autenticação(com uso de JWT)  
## Registro de Usuário

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/register` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| name       | Nome do usuário [***required,String,min:8,max:16***]        |
| email    | Email que será usado para login [***required,email,unique***]|
| password           | senha [***required,min:8***] |
| password_confirmation | confirmação da senha, conteúdo deve ser igual a password [***required,password_comfirmed***] |

## Retorno caso de sucesso

```json
{
    "success": {
        "status": "201",
        "title": "Created",
        "detail": {
            "name": "rrt",
            "email": "rr@testes",
            "updated_at": "2024-05-08T23:32:57.000000Z",
            "created_at": "2024-05-08T23:32:57.000000Z",
            "id": 8
        }
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
            "email": [
                "Este e-mail já está em uso."
            ]
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

## Retorno caso de sucesso
```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "Token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L2xvZ2luIiwiaWF0IjoxNzE1MjA4NDMyLCJleHAiOjE3MTUyMTU2MzIsIm5iZiI6MTcxNTIwODQzMiwianRpIjoiTElhWlN2T2FhejlvQkdOZyIsInN1YiI6IjAiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.SEXpkN0JR32HvAqGJf6iu_id2oD4Bj55tlbSmj4lH4o"
        }
    }
}
```
## Retorno caso haja erros de validação
```json
{
    "error": {
        "status": "403",
        "title": "Forbidden",
        "detail": "Erro de usuário ou senha"
    }
}
```
```json
{
    "error": {
        "status": "422",
        "title": "Unprocessable Entity",
        "detail": {
            "password": [
                "A senha é obrigatória"
            ]
        }
    }
}
```
Token com tempo de vida de 120 minutos

## Me

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/me` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |

exemplo de um name   
Authorization =   
"bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L2xvZ2luIiwiaWF0IjoxNzEyNzgyMjUyLCJleHAiOjE3MTI3ODk0NTIsIm5iZiI6MTcxMjc4MjI1MiwianRpIjoiTTY4NWRMV0F6T3JLMU53UyIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.nyXG9To5aLFoSDMOE1VASO8_GN7_LLtU9KmNTfpTb18"

## Retorno caso de sucesso
Retorna dados do usuário que esta logado

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "User": {
                "id": 0,
                "name": "israel silva",
                "email": "israel524.is@gmail.com",
                "email_verified_at": null,
                "password_reset": "dx0g9Em5czGO3PUT5KD32kXuh8gZD50NLxJXdvdrezgy7FnerPS6uAHeub2v",
                "created_at": "2024-04-25T16:25:16.000000Z",
                "updated_at": "2024-05-08T19:35:29.000000Z"
            }
        }
    }
}
```

## Retorno caso de erro

```json
{
    "error": {
        "status": "401",
        "title": "Unauthorized",
        "detail": "Token not provided"
    }
}
```

## Refresh de usuário(token)

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/refresh` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |

## Retorno caso de sucesso

O Token enviado na requisição recebe mais tempo util(mais 120 minutos de duração).

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": {
            "Token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL3Y1L3JlZnJlc2giLCJpYXQiOjE3MTUyMTEzMzksImV4cCI6MTcxNTIxOTI2OCwibmJmIjoxNzE1MjEyMDY4LCJqdGkiOiJ2aHVrN1dkaVpmQkl6bzhhIiwic3ViIjoiMCIsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Y5jThUxnzpEG1da0HHnJutQEIYtBNZ1_Zk3SZ8-K2eE"
        }
    }
}
```
## Retorno caso erros

```json
{
    "error": {
        "status": "401",
        "title": "Unauthorized",
        "detail": "The token has been blacklisted"
    }
}
```
OBS: use somente uma refresh para cada Token, apos isso tem um tempo muito grade para poder dar refresh no mesmo Token, é mais recomendável solicitar um novo via login.


## Logout de usuário

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/logout` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| Authorization    | Token valido(deve ser uma string inciada com a palavra "bearer" depois um espaço e o Token) |


## Retorno caso de sucesso

O Token enviado na requisição é invalidado com o logout.

```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": "Logout foi realizado com sucesso"
    }
}
```

## Retorno caso de erro

```json
{
    "error": {
        "status": "401",
        "title": "Unauthorized",
        "detail": "Token not provided"
    }
}
```
OBS: Outros erros podem ser retornados de acordo com o que foi enviado, certifique-se de fazer a requisição corretamente.
