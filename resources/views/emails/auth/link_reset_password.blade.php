<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div
        style="max-width: 600px; background: #fff; padding: 20px; margin: auto; border-radius: 10px; text-align: center; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <h2 style="color: #4F4F74;">Olá, {{ $user->name }}!</h2>
        <h3 style="color: #333;">Esqueceu sua senha?</h3>
        <p style="color: #555;">Não se preocupe, é fácil criar uma nova. Use o código abaixo para redefinir sua senha:
        </p>

        <div
            style="background: #e0e0e0; display: inline-block; padding: 10px 20px; font-size: 24px; font-weight: bold; color: #171717; border-radius: 5px; margin: 10px 0;">
            {{ $user->code }}
        </div>

        <p style="color: #555;">Ou clique no botão abaixo para redefinir sua senha:</p>

        <a href="{{ $url }}"
            style="background: #3F51B5; color: #fff; padding: 12px 20px; text-decoration: none; font-size: 18px; font-weight: bold; border-radius: 5px; display: inline-block;">
            Criar nova senha
        </a>

        <p style="font-size: 14px; color: #777; margin-top: 15px;">
            Esse código ficará disponível por 24 horas e só poderá ser usado uma vez.<br>
            Se não solicitou uma nova senha, ignore esta mensagem.
        </p>
    </div>
</body>

</html>
