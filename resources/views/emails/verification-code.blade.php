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
                            <img src="{{ rtrim(config('app.url'), '/') . '/images/logo.png' }}" alt="{{ config('app.name') }}" style="height:40px;vertical-align:middle;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px;">
                            <h2 style="margin:0 0 12px 0;font-size:20px;color:#0f172a;">{{ $title }}</h2>
                            <p style="margin:0 0 18px 0;font-size:14px;line-height:1.6;color:#475569;">
                                Use the verification code below to continue. This code expires at {{ $expiresAt?->toDateTimeString() }}.
                            </p>
                            <div style="font-size:28px;letter-spacing:6px;font-weight:bold;color:#0f172a;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:12px 16px;display:inline-block;">
                                {{ $code }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;color:#64748b;">
                                {{ config('app.name') }} • If you did not request this, you can ignore this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
