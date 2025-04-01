<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            /* Corrige o problema de padding no lado direito */
        }

        .btn {
            background: #3F51B5;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
            width: 100%;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #303F9F;
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="{{ asset('images/logo.png') }}" class="logo" alt="Logo" />
        <h2>Crie uma nova senha</h2>
        <p>Digite o código de acesso que enviamos para o seu e-mail.</p>     

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request()->email }}">

            <div class="form-group">
                <label for="code">Código de acesso</label>
                <input type="text" name="code" id="code" value="{{ old('code') ?? '' }}" required autofocus>
                <!-- Exibindo mensagem de erro para o campo 'code' -->
                @if ($errors->has('code'))
                    <div class="text-danger" style="color:red">
                        {{ $errors->first('code') }}
                    </div>
                @endif

                @if ($errors->has('email'))
                <div class="text-danger" style="color:red">
                    {{ $errors->first('email') }}
                </div>
            @endif
            </div>

            <div class="form-group">
                <label for="password">Digite a nova senha</label>
                <input type="password" name="password" id="password" required>
                <!-- Exibindo mensagem de erro para o campo 'password' -->
                @if ($errors->has('password'))
                    <div class="text-danger" style="color:red">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar nova senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
                <!-- Exibindo mensagem de erro para o campo 'password_confirmation' -->
                @if ($errors->has('password_confirmation'))
                    <div class="text-danger" style="color:red">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
            </div>

            <button type="submit" class="btn">Confirmar</button>
        </form>
    </div>

</body>

</html>
