# Configuração do serviço de envio de e-mails  

## Obter acesso para contas GMAIL. 

- Acesse sua Conta do Google.
- Selecione Segurança.
- Em "Como você faz login no Google", selecione Verificação em duas etapas.
- Na parte de baixo da página, selecione Senhas de app.
- Insira um nome que ajude você a lembrar onde usará a senha de app.
- Selecione Gerar.
Para inserir a senha de app, siga as instruções na tela. Essa senha é o código de 16 caracteres que é gerado no seu dispositivo (aaaa bbbb cccc dddd).
- Selecione Concluído.

## Setar as seguintes variáveis no .env(para contas gmail):

```json
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=teste@gmail.com
MAIL_PASSWORD="aaaa bbbb cccc dddd"
MAIL_ENCRYPTION=TLS
```



[Voltar a pagina principal](/README.md)

