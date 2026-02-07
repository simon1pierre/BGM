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
            'preheader' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'featured_image_url' => ['nullable', 'url', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'audio_url' => ['nullable', 'url', 'max:255'],
            'document_url' => ['nullable', 'url', 'max:255'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'video_file' => ['nullable', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:51200'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'document_file' => ['nullable', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:20480'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $featuredImageUrl = $validated['featured_image_url'] ?? null;
        if ($request->hasFile('featured_image')) {
            $featuredImageUrl = asset('storage/'.$request->file('featured_image')->store('campaigns/images', 'public'));
        }
        $videoUrl = $validated['video_url'] ?? null;
        if ($request->hasFile('video_file')) {
            $videoUrl = asset('storage/'.$request->file('video_file')->store('campaigns/videos', 'public'));
        }
        $audioUrl = $validated['audio_url'] ?? null;
        if ($request->hasFile('audio_file')) {
            $audioUrl = asset('storage/'.$request->file('audio_file')->store('campaigns/audios', 'public'));
        }
        $documentUrl = $validated['document_url'] ?? null;
        if ($request->hasFile('document_file')) {
            $documentUrl = asset('storage/'.$request->file('document_file')->store('campaigns/documents', 'public'));
        }

        $campaign = EmailCampaign::create([
            'subject' => $validated['subject'],
            'preheader' => $validated['preheader'] ?? null,
            'message' => $validated['message'],
            'body_html' => $validated['body_html'] ?? null,
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'featured_image_url' => $featuredImageUrl,
            'video_url' => $videoUrl,
            'audio_url' => $audioUrl,
            'document_url' => $documentUrl,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
        ]);

        if ($campaign->status === 'sent') {
            SendEmailCampaignJob::dispatchSync($campaign->id);
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
            'preheader' => ['nullable', 'string', 'max:255'],
            'body_html' => ['nullable', 'string'],
            'featured_image_url' => ['nullable', 'url', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'audio_url' => ['nullable', 'url', 'max:255'],
            'document_url' => ['nullable', 'url', 'max:255'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
            'video_file' => ['nullable', 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska', 'max:51200'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'document_file' => ['nullable', 'mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'max:20480'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'cta_url' => ['nullable', 'url', 'max:255'],
            'status' => ['required', 'in:draft,scheduled,sent'],
            'scheduled_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $featuredImageUrl = $validated['featured_image_url'] ?? $campaign->featured_image_url;
        if ($request->hasFile('featured_image')) {
            $featuredImageUrl = asset('storage/'.$request->file('featured_image')->store('campaigns/images', 'public'));
        }
        $videoUrl = $validated['video_url'] ?? $campaign->video_url;
        if ($request->hasFile('video_file')) {
            $videoUrl = asset('storage/'.$request->file('video_file')->store('campaigns/videos', 'public'));
        }
        $audioUrl = $validated['audio_url'] ?? $campaign->audio_url;
        if ($request->hasFile('audio_file')) {
            $audioUrl = asset('storage/'.$request->file('audio_file')->store('campaigns/audios', 'public'));
        }
        $documentUrl = $validated['document_url'] ?? $campaign->document_url;
        if ($request->hasFile('document_file')) {
            $documentUrl = asset('storage/'.$request->file('document_file')->store('campaigns/documents', 'public'));
        }

        $campaign->update([
            'subject' => $validated['subject'],
            'preheader' => $validated['preheader'] ?? null,
            'message' => $validated['message'],
            'body_html' => $validated['body_html'] ?? null,
            'status' => $validated['status'],
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'featured_image_url' => $featuredImageUrl,
            'video_url' => $videoUrl,
            'audio_url' => $audioUrl,
            'document_url' => $documentUrl,
            'cta_text' => $validated['cta_text'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
        ]);

        if ($campaign->status === 'sent' && $campaign->sent_at === null) {
            SendEmailCampaignJob::dispatchSync($campaign->id);
        }

        return redirect()->route('admin.campaigns.index')->with('status', 'Campaign updated.');
    }

    public function preview(EmailCampaign $campaign)
    {
        return view('Admin.EmailCampaigns.preview', compact('campaign'));
    }

    public function previewRaw(EmailCampaign $campaign)
    {
        return view('emails.campaign', ['campaign' => $campaign]);
    }

}
