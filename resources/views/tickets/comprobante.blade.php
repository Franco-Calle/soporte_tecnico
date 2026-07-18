<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante {{ $orden->numero_ot }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #0A0D40; margin: 20px; }
        h1 { font-size: 16px; margin: 0 0 6px; }
        h2 { font-size: 12px; margin: 12px 0 4px; border-bottom: 1px solid #D0F2D3; padding-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { padding: 4px 6px; border-bottom: 1px solid #eee; }
        th { background: #D0F2D3; text-align: left; color: #0A0D40; }
        .right { text-align: right; }
        .totales td { border: none; padding: 2px 6px; }
        .totales .final { border-top: 2px solid #0A0D40; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; color: #555; text-align: center; }
    </style>
</head>
<body>
    <h1>{{ config('app.name') }}</h1>
    <p>Comprobante interno &middot; No es documento tributario</p>
    <p><strong>Orden:</strong> {{ $orden->numero_ot }} &middot; <strong>Fecha:</strong> {{ $orden->recibido_at->format('d/m/Y H:i') }}</p>

    <h2>Cliente</h2>
    <p>
        {{ $orden->equipo->cliente->nombre }} &middot; DNI {{ $orden->equipo->cliente->dni }}<br>
        Tel: {{ $orden->equipo->cliente->telefono ?? '—' }}
    </p>

    <h2>Equipo</h2>
    <p>
        {{ $orden->equipo->tipo->etiqueta() }} — {{ $orden->equipo->marca }} {{ $orden->equipo->modelo }}<br>
        {{ $orden->equipo->tipo->identificadorLabel() }}: {{ $orden->equipo->serie_imei ?? '—' }}
    </p>

    <h2>Detalle</h2>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Descripcion</th>
                <th class="right">Cant.</th>
                <th class="right">P. Unit</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orden->items as $it)
                <tr>
                    <td>{{ $it->tipo_snapshot->etiqueta() }}</td>
                    <td>{{ $it->nombre_snapshot }}</td>
                    <td class="right">{{ $it->cantidad }}</td>
                    <td class="right">S/. {{ number_format((float) $it->precio_unitario, 2) }}</td>
                    <td class="right">S/. {{ number_format((float) $it->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totales" style="margin-top: 8px;">
        <tr><td class="right">Total:</td><td class="right" style="width: 100px;">S/. {{ number_format((float) $orden->total, 2) }}</td></tr>
        <tr><td class="right">Pagado:</td><td class="right">S/. {{ number_format((float) $orden->total_pagado, 2) }}</td></tr>
        <tr class="final"><td class="right">Pendiente:</td><td class="right">S/. {{ number_format($orden->saldoPendiente(), 2) }}</td></tr>
    </table>

    @if($orden->pagos->isNotEmpty())
        <h2>Pagos</h2>
        <table>
            <thead>
                <tr><th>Fecha</th><th>Metodo</th><th>Referencia</th><th class="right">Monto</th></tr>
            </thead>
            <tbody>
                @foreach($orden->pagos as $p)
                    <tr>
                        <td>{{ $p->cobrado_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $p->metodo->etiqueta() }}</td>
                        <td>{{ $p->referencia ?? '—' }}</td>
                        <td class="right">S/. {{ number_format((float) $p->monto, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Comprobante generado el {{ now()->format('d/m/Y H:i') }} &middot; Tecnico: {{ $orden->tecnico?->name ?? '—' }}
    </div>
</body>
</html>
