<!DOCTYPE html>
<html lang="es-MX">
<head>
    <meta charset="utf-8">
    <title>Verifica tu correo electrónico</title>
</head>
<body>

<div>
    Hola {{ $userCustomer->userDetails->name }},
    <br>
    Para verificar tu correo electrónico, por favor ingresa los siguientes digitos en la app de Tx8.
    <br>
    {{ $userCustomer->verification_code }}
    <br>
    Saludos. Si tienes dudas o comentarios no dudes en contactarnos.
    <br/>
</div>

</body>
</html>
