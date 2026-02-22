<?php

namespace App\Http\Controllers\Admin\EmailCampaigns;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailCampaignJob;
use App\Models\EmailCampaign;
use App\Models\Subscriber;
use App\Models\UserActivityLog;
use App\Models\video;
use App\Models\audio;
use App\Models\book;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class EmailCampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaignsQuery = EmailCampaign::query();

        if ($request->filled('status')) {
            $campaignsQuery->where('status', (string) $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $campaignsQuery->where(function ($q) use ($search) {
                $q->where('subject', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('deleted')) {
            $deleted = (string) $request->query('deleted');
            if ($deleted === 'with') {
                $campaignsQuery->withTrashed();
            } elseif ($deleted === 'only') {
                $campaignsQuery->onlyTrashed();
            }
        }

        $campaigns = $campaignsQuery
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.EmailCampaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $subscribers = Subscriber::query()
            ->where('is_active', true)
            ->orderBy('email')
            ->get();

        $videos = video::query()->orderByDesc('created_at')->limit(50)->get();
        $audios = audio::query()->orderByDesc('created_at')->limit(50)->get();
        $documents = book::query()->orderByDesc('created_at')->limit(50)->get();

        return view('Admin.EmailCampaigns.create', compact('subscribers', 'videos', 'audios', 'documents'));
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
            'target_type' => ['required', 'in:all,selected,custom'],
            'subscriber_ids' => ['nullable', 'array'],
            'subscriber_ids.*' => ['integer', 'exists:subscribers,id'],
            'target_emails' => ['nullable', 'string'],
            'video_id' => ['nullable', 'integer', 'exists:videos,id'],
            'audio_id' => ['nullable', 'integer', 'exists:audios,id'],
            'document_id' => ['nullable', 'integer', 'exists:books,id'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $featuredImageUrl = $validated['featured_image_url'] ?? null;
        if ($request->hasFile('featured_image')) {
            $featuredImageUrl = asset('storage/'.$request->file('featured_image')->store('campaigns/images', 'public'));
        }
        $videoUrl = $validated['video_url'] ?? null;
        if (!empty($validated['video_id'])) {
            $videoModel = video::find($validated['video_id']);
            $videoUrl = $videoModel?->youtube_url ?? $videoUrl;
        }
        if ($request->hasFile('video_file')) {
            $videoUrl = asset('storage/'.$request->file('video_file')->store('campaigns/videos', 'public'));
        }
        $audioUrl = $validated['audio_url'] ?? null;
        if (!empty($validated['audio_id'])) {
            $audioModel = audio::find($validated['audio_id']);
            if ($audioModel?->audio_file) {
                $audioUrl = asset('storage/'.$audioModel->audio_file);
            }
        }
        if ($request->hasFile('audio_file')) {
            $audioUrl = asset('storage/'.$request->file('audio_file')->store('campaigns/audios', 'public'));
        }
        $documentUrl = $validated['document_url'] ?? null;
        if (!empty($validated['document_id'])) {
            $documentModel = book::find($validated['document_id']);
            if ($documentModel?->file_path) {
                $documentUrl = asset('storage/'.$documentModel->file_path);
            }
        }
        if ($request->hasFile('document_file')) {
            $documentUrl = asset('storage/'.$request->file('document_file')->store('campaigns/documents', 'public'));
        }

        $targets = $this->resolveTargets($validated['target_type'], $validated['subscriber_ids'] ?? [], $validated['target_emails'] ?? null);

        $campaign = EmailCampaign::create([
            'subject' => $validated['subject'],
            'preheader' => $validated['preheader'] ?? null,
            'message' => $validated['message'],
            'body_html' => $validated['body_html'] ?? null,
            'status' => $validated['status'],
            'target_type' => $targets['type'],
            'target_subscriber_ids' => $targets['subscriber_ids'],
            'target_emails' => $targets['emails'],
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
        $subscribers = Subscriber::query()
            ->where('is_active', true)
            ->orderBy('email')
            ->get();

        $videos = video::query()->orderByDesc('created_at')->limit(50)->get();
        $audios = audio::query()->orderByDesc('created_at')->limit(50)->get();
        $documents = book::query()->orderByDesc('created_at')->limit(50)->get();

        return view('Admin.EmailCampaigns.edit', compact('campaign', 'subscribers', 'videos', 'audios', 'documents'));
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
            'target_type' => ['required', 'in:all,selected,custom'],
            'subscriber_ids' => ['nullable', 'array'],
            'subscriber_ids.*' => ['integer', 'exists:subscribers,id'],
            'target_emails' => ['nullable', 'string'],
            'video_id' => ['nullable', 'integer', 'exists:videos,id'],
            'audio_id' => ['nullable', 'integer', 'exists:audios,id'],
            'document_id' => ['nullable', 'integer', 'exists:books,id'],
        ]);

        if ($validated['status'] === 'scheduled' && empty($validated['scheduled_at'])) {
            return back()->withErrors(['scheduled_at' => 'Scheduled time is required.'])->withInput();
        }

        $featuredImageUrl = $validated['featured_image_url'] ?? $campaign->featured_image_url;
        if ($request->hasFile('featured_image')) {
            $featuredImageUrl = asset('storage/'.$request->file('featured_image')->store('campaigns/images', 'public'));
        }
        $videoUrl = $validated['video_url'] ?? $campaign->video_url;
        if (!empty($validated['video_id'])) {
            $videoModel = video::find($validated['video_id']);
            $videoUrl = $videoModel?->youtube_url ?? $videoUrl;
        }
        if ($request->hasFile('video_file')) {
            $videoUrl = asset('storage/'.$request->file('video_file')->store('campaigns/videos', 'public'));
        }
        $audioUrl = $validated['audio_url'] ?? $campaign->audio_url;
        if (!empty($validated['audio_id'])) {
            $audioModel = audio::find($validated['audio_id']);
            if ($audioModel?->audio_file) {
                $audioUrl = asset('storage/'.$audioModel->audio_file);
            }
        }
        if ($request->hasFile('audio_file')) {
            $audioUrl = asset('storage/'.$request->file('audio_file')->store('campaigns/audios', 'public'));
        }
        $documentUrl = $validated['document_url'] ?? $campaign->document_url;
        if (!empty($validated['document_id'])) {
            $documentModel = book::find($validated['document_id']);
            if ($documentModel?->file_path) {
                $documentUrl = asset('storage/'.$documentModel->file_path);
            }
        }
        if ($request->hasFile('document_file')) {
            $documentUrl = asset('storage/'.$request->file('document_file')->store('campaigns/documents', 'public'));
        }

        $targets = $this->resolveTargets($validated['target_type'], $validated['subscriber_ids'] ?? [], $validated['target_emails'] ?? null);

        $campaign->update([
            'subject' => $validated['subject'],
            'preheader' => $validated['preheader'] ?? null,
            'message' => $validated['message'],
            'body_html' => $validated['body_html'] ?? null,
            'status' => $validated['status'],
            'target_type' => $targets['type'],
            'target_subscriber_ids' => $targets['subscriber_ids'],
            'target_emails' => $targets['emails'],
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

    public function destroy(EmailCampaign $campaign)
    {
        $campaign->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'campaign_deleted',
            'meta' => [
                'campaign_id' => $campaign->id,
                'subject' => $campaign->subject,
            ],
        ]);

        return redirect()->back()->with('status', 'Campaign deleted.');
    }

    public function restore(int $campaign)
    {
        $record = EmailCampaign::withTrashed()->findOrFail($campaign);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'campaign_restored',
            'meta' => [
                'campaign_id' => $record->id,
                'subject' => $record->subject,
            ],
        ]);

        return redirect()->back()->with('status', 'Campaign restored.');
    }

    public function forceDelete(int $campaign)
    {
        $record = EmailCampaign::withTrashed()->findOrFail($campaign);
        $subject = $record->subject;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'campaign_force_deleted',
            'meta' => [
                'campaign_id' => $campaign,
                'subject' => $subject,
            ],
        ]);

        return redirect()->back()->with('status', 'Campaign permanently deleted.');
    }

    private function resolveTargets(string $targetType, array $subscriberIds, ?string $targetEmails): array
    {
        if ($targetType === 'selected') {
            if (count($subscriberIds) === 0) {
                throw ValidationException::withMessages([
                    'subscriber_ids' => 'Please select at least one subscriber.',
                ]);
            }

            return [
                'type' => 'selected',
                'subscriber_ids' => array_values(array_unique($subscriberIds)),
                'emails' => null,
            ];
        }

        if ($targetType === 'custom') {
            $emails = collect(explode(',', (string) $targetEmails))
                ->map(fn ($email) => trim($email))
                ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
                ->unique()
                ->values()
                ->all();

            if (count($emails) === 0) {
                throw ValidationException::withMessages([
                    'target_emails' => 'Please provide at least one valid email address.',
                ]);
            }

            return [
                'type' => 'custom',
                'subscriber_ids' => null,
                'emails' => $emails,
            ];
        }

        return [
            'type' => 'all',
            'subscriber_ids' => null,
            'emails' => null,
        ];
    }

}


