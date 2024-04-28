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
    "message": "O email foi enviado com sucesso."
}
```

## Retorno caso haja erros de validação
```json
{
    "errors": {
        "email": [
            "Este e-mail não pertence a um usuário."
        ]
    }
}
```
