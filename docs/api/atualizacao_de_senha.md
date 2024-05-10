# Atualização de senha. 
## Envio de email de solicitação de atualização de senha

![POST](https://img.shields.io/badge/HTTP-POST-00CC00)  

`/api/v5/send-password-reset-notification` 

## Parâmetros
Deve receber uma requisição via POST com os names abaixo


| Nome          | Descrição/requisitos de validação                                                                  |
|---------------|----------------------------------------------------------------------------|
| email    | Email da conta do usuário que deseja atualizar a senha [***required, email, exists:users***]|

## Retorno caso de sucesso
```json
{
    "success": {
        "status": "200",
        "title": "OK",
        "detail": "O email foi enviado com sucesso."
    }
}
```

## Retorno caso haja erros de validação
```json
{
    "error": {
        "status": "400",
        "title": "Bad Request",
        "detail": {
            "email": [
                "Este e-mail não pertence a um usuário."
            ]
        }
    }
}
```


[Voltar a pagina principal](/README.md)
