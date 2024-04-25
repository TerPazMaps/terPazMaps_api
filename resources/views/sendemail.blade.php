<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>Email Send Using PHPMailer - webappfix.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5" style="max-width: 750px">

        <h1>Recuperação de senha</h1>
        <p>
            Para recuperar a sua senha, informe seu endereço de email que nós enviaremos um link para alteração da senha.
        </p>

        <form action="{{ route('send-password-reset-notification') }}"  method="POST">

            <div class="form-group">
                <label>Email de recuperação:</label>
                <input type="email" name="email" class="form-control" />
            </div>
            <p>israel524.is@gmail.com</p>
            <div class="form-group mt-3 mb-3">
                <button type="submit" class="btn btn-success btn-block">Enviar</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
