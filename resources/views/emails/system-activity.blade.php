<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f5f7fb;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 8px 24px rgba(15,23,42,0.08);">
                    <tr>
                        <td style="background:#0f172a;padding:24px 28px;text-align:left;">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="height:40px;vertical-align:middle; color:white">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <h2 style="margin:0 0 12px 0;font-size:20px;color:#0f172a;">{{ $title }}</h2>
                            <p style="margin:0 0 16px 0;font-size:14px;line-height:1.6;color:#475569;">
                                This notification summarizes an important activity in your system.
                            </p>
                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;">
                                        <strong style="color:#0f172a;">Actor:</strong>
                                        <span style="color:#334155;">{{ $actor }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;border-bottom:1px solid #e2e8f0;">
                                        <strong style="color:#0f172a;">Time:</strong>
                                        <span style="color:#334155;">{{ $time }}</span>
                                    </td>
                                </tr>
                                @if (!empty($meta))
                                    <tr>
                                        <td style="padding:10px 0;">
                                            <strong style="color:#0f172a;">Details:</strong>
                                            <ul style="margin:8px 0 0 18px;padding:0;color:#334155;font-size:14px;line-height:1.5;">
                                                @foreach ($meta as $key => $value)
                                                    <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ is_scalar($value) ? $value : json_encode($value) }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;color:#64748b;">
                                {{ config('app.name') }} • This is an automated message.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>







