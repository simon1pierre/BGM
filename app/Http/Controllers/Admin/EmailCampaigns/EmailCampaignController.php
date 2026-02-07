<?php

namespace App\Http\Controllers\Admin\EmailCampaigns;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailCampaignJob;
use App\Models\EmailCampaign;
use Illuminate\Http\Request;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::query()
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('Admin.EmailCampaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('Admin.EmailCampaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $campaign = EmailCampaign::create([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        if ($campaign->status === 'sent') {
            SendEmailCampaignJob::dispatch($campaign->id);
        }

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign created.');
    }

    public function edit(EmailCampaign $campaign)
    {
        return view('Admin.EmailCampaigns.edit', compact('campaign'));
    }

    public function update(Request $request, EmailCampaign $campaign)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $campaign->update([
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
        ]);

        if ($campaign->status === 'sent' && $campaign->sent_at === null) {
            SendEmailCampaignJob::dispatch($campaign->id);
        }

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign updated.');
    }
}
