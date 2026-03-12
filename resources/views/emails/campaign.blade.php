<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campaign->subject }}</title>
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
                            @if ($campaign->featured_image_url)
                                <img src="{{ $campaign->featured_image_url }}" alt="" style="width:100%;border-radius:10px;margin-bottom:18px;">
                            @endif

                            <h2 style="margin:0 0 8px 0;font-size:22px;color:#0f172a;">{{ $campaign->subject }}</h2>
                            @if ($campaign->preheader)
                                <p style="margin:0 0 18px 0;font-size:14px;line-height:1.6;color:#64748b;">
                                    {{ $campaign->preheader }}
                                </p>
                            @endif

                            @if (!empty($campaign->body_html))
                                <div style="font-size:15px;line-height:1.7;color:#334155;">
                                    {!! $campaign->body_html !!}
                                </div>
                            @else
                                <p style="font-size:15px;line-height:1.7;color:#334155;">{{ $campaign->message }}</p>
                            @endif

                            @if ($campaign->video_url || $campaign->audio_url || $campaign->document_url)
                                <div style="margin-top:18px;padding:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                                    <h4 style="margin:0 0 10px 0;font-size:14px;color:#0f172a;">Resources</h4>
                                    <ul style="margin:0;padding-left:18px;color:#334155;font-size:14px;">
                                        @if ($campaign->video_url)
                                            <li>Video: <a href="{{ $campaign->video_url }}">{{ $campaign->video_url }}</a></li>
                                        @endif
                                        @if ($campaign->audio_url)
                                            <li>Audio: <a href="{{ $campaign->audio_url }}">{{ $campaign->audio_url }}</a></li>
                                        @endif
                                        @if ($campaign->document_url)
                                            <li>Document: <a href="{{ $campaign->document_url }}">{{ $campaign->document_url }}</a></li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            @if ($campaign->cta_text && $campaign->cta_url)
                                <div style="margin-top:20px;">
                                    <a href="{{ $campaign->cta_url }}" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;padding:12px 20px;border-radius:8px;font-weight:bold;">
                                        {{ $campaign->cta_text }}
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 28px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                            <p style="margin:0;font-size:12px;color:#64748b;">
                                {{ config('app.name') }} • You are receiving this because you subscribed to our evangelism updates.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>







