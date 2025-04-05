<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Glicose</title>

    <style>
        body {
            font-family: "Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 0.5px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 0.65rem;
            width: auto;
            white-space: nowrap;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        @page {
            size: A4 landscape;
            margin: 15px;
            /* Margens menores */
        }


        .no-border {
            border: none;
        }

        .header {
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between
        }

        .header .logo {
            width: 30px;
            height: 30px;
        }

        .header .title {
            text-align: center;
            font-size: 0.85rem;
            font-weight: bold
        }
    </style>
</head>

<body>
    <div class="header"> 
        {{-- <img src="{{ public_path('images/unicornio.png') }}" class="logo" alt="logo" /> --}}
        <img src="https://glicemia-api.vercel.app/images/unicornio.png" class="logo" alt="logo" />
        <strong style="font-size: 0.85rem">{{ config('app.name') }}</strong>
        <div class="title">MÊS {{ $currentMonthFormat }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="no-border"></th>
                <th class="no-border"></th>
                <th colspan="4">CAFÉ DA MANHÃ</th>
                <th colspan="4">ALMOÇO</th>
                <th colspan="4">COLAÇÃO</th>
                <th colspan="4">JANTAR</th>
                <th colspan="5">CEIA</th>
            </tr>
            <tr>
                <th>Dia</th>
                <th>Basal</th>
                <th>Antes</th>
                <th>Ultra Rápida</th>
                <th>CHO</th>
                <th>2h Após</th>
                <th>Antes</th>
                <th>Ultra Rápida</th>
                <th>CHO</th>
                <th>2h Após</th>
                <th>Antes</th>
                <th>Ultra Rápida</th>
                <th>CHO</th>
                <th>2h Após</th>
                <th>Antes</th>
                <th>Ultra Rápida</th>
                <th>CHO</th>
                <th>2h Após</th>
                <th>Antes</th>
                <th>Ultra Rápida</th>
                <th>CHO</th>
                <th>2h Após</th>
                <th>3h Manhã</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedGlucoses as $day => $data)
                <tr>
                    <td>{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $data['basal'] ?? '' }}</td>

                    @foreach (['Café da Manhã', 'Almoço', 'Colação', 'Jantar', 'Ceia'] as $type)
                        @php
                            $glucoseData = $data['meals']->get($type);
                        @endphp

                        <td>{{ $glucoseData?->first()?->before_glucose ?? '' }}</td>
                        <td>{{ $glucoseData?->first()?->ultra_fast_insulin ?? '' }}</td>
                        <td>{{ $glucoseData?->first()?->carbs ?? '' }}</td>
                        <td>{{ $glucoseData?->first()?->after_glucose ?? '' }}</td>

                        @if ($type == 'Ceia')
                            <td>{{ $glucoseData?->first()?->glucose_3morning ?? '' }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
