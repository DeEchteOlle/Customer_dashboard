<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 10px 14px; text-align: left; }
        th { background: #f3f4f6; }
        .bad { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 24px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <h2>Performance alert — {{ $website->name }}</h2>
    <p>
        Bij de laatste PageSpeed-scan van <strong>{{ $website->url }}</strong>
        zijn de volgende metrics buiten de drempelwaarde gevallen:
    </p>

    <table>
        <thead>
            <tr>
                <th>Metric</th>
                <th>Gemeten waarde</th>
                <th>Drempel</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($failingMetrics as $name => $data)
                <tr>
                    <td>{{ $name }}</td>
                    <td class="bad">{{ $data['value'] }}{{ $data['unit'] }}</td>
                    <td>&lt; {{ $data['threshold'] }}{{ $data['unit'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        <a href="{{ url('websites/' . $website->id . '/results') }}">
            Bekijk de volledige resultaten →
        </a>
    </p>

    <p class="footer">
        Dit bericht is automatisch verstuurd door Customer Dashboard.
    </p>
</body>
</html>