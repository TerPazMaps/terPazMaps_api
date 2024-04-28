<x-mail::message>
# Atualização de senha

Caro(a) <strong>{{$name}}</strong>, <br>
você solicitou a atualização de sua senha de acesso ao sistema TerPazMaps. <br>
Clique no link abaixo para cadastrar uma nova senha:

<x-mail::button :url="$url">
Atualizar minha senha
</x-mail::button>

O link de atualização de senha é válido para uma redefinição.<br>
Se você não requisitou atualização de senha, por favor desconsidere esse e-mail.

Obrigado,<br>
{{ config('app.name') }}
</x-mail::message>
