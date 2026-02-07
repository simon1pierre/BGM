<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Notifications\SystemTestEmailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = Setting::current();

        if (!$settings) {
            $settings = new Setting([
                'site_name' => config('app.name'),
                'contact_email' => config('mail.from.address'),
                'notifications_email' => config('mail.from.address'),
                'notifications_enabled' => true,
                'mail_mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_username' => config('mail.mailers.smtp.username'),
                'mail_password' => null,
                'mail_scheme' => config('mail.mailers.smtp.scheme'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name'),
            ]);
        }

        return view('Admin.Settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'notifications_email' => ['nullable', 'email', 'max:255'],
            'notifications_enabled' => ['nullable', 'boolean'],
            'mail_mailer' => ['nullable', 'string', 'max:50'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_scheme' => ['nullable', 'string', 'max:50'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'mail_from_name' => ['nullable', 'string', 'max:255'],
        ]);

        $settings = Setting::current() ?? new Setting();

        $validated['notifications_enabled'] = (bool) ($validated['notifications_enabled'] ?? false);

        if (!empty($validated['mail_scheme'])) {
            $scheme = strtolower($validated['mail_scheme']);
            if ($scheme === 'tls') {
                $validated['mail_scheme'] = 'smtp';
            } elseif ($scheme === 'ssl') {
                $validated['mail_scheme'] = 'smtps';
            }
        }

        if (blank($validated['mail_password'] ?? null)) {
            unset($validated['mail_password']);
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }

    public function testEmail()
    {
        $settings = Setting::current();
        $to = $settings?->notifications_email
            ?: $settings?->contact_email
            ?: config('mail.from.address');

        if (!$to) {
            return redirect()->route('admin.settings.edit')->with('status', 'No notification email is configured.');
        }

        Notification::route('mail', $to)->notify(new SystemTestEmailNotification());

        return redirect()->route('admin.settings.edit')->with('status', 'Test email sent.');
    }
}
