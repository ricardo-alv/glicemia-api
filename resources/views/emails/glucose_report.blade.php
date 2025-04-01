<!DOCTYPE html>
<html>

<head>
    <title>Relatório de Glicemia {{ $textPeriod }}</title>
</head>

<body>
    {{-- <h2>Relatório de Glicemia</h2> --}}
    <p>Olá,</p>
    <p>Segue o relatório de glicemia conforme o período solicitado.</p>
    <p>{{ $textPeriod }}</p>
    <p>Atenciosamente,</p>
    <footer>
        <p><strong><i>{{ config('app.name') }}</i></strong></p> <!-- Aqui você pega o APP_NAME -->
    </footer>
</body>

</html>
