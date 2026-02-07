@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <style>
        :root{
            --ev-blue:#0f172a;
            --ev-gold:#f59e0b;
            --ev-ink:#0b1220;
            --ev-sand:#f8fafc;
            --ev-mist:#e2e8f0;
        }
        .ev-hero{
            background: radial-gradient(1200px 300px at 10% -20%, rgba(245,158,11,.18), transparent),
                        linear-gradient(120deg, #0f172a, #1e293b);
            color:#fff;
            border-radius:16px;
            padding:22px 24px;
            box-shadow:0 10px 30px rgba(15,23,42,.25);
        }
        .ev-hero h5{margin:0 0 6px 0;font-weight:700;letter-spacing:.2px;}
        .ev-hero p{margin:0;color:#cbd5f5;font-size:.92rem;}
        .ev-card{
            border-radius:16px;
            border:1px solid var(--ev-mist);
            box-shadow:0 8px 20px rgba(15,23,42,.06);
        }
        .ev-card .card-title{
            font-weight:700;
            color:var(--ev-ink);
            margin-bottom:4px;
        }
        .ev-section-label{
            font-size:.8rem;
            letter-spacing:.08em;
            text-transform:uppercase;
            color:#64748b;
            font-weight:600;
        }
        .ev-help{font-size:.82rem;color:#6b7280;}
        .ev-pill{
            background:rgba(245,158,11,.15);
            color:#9a3412;
            padding:4px 10px;
            border-radius:999px;
            font-size:.75rem;
            font-weight:600;
        }
    </style>
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Campaign</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">Campaigns</a></li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="ev-hero mb-4">
            <h5>Refine and strengthen your message</h5>
            <p>Keep it Scripture-anchored, clear, and warm. Add media only if it helps.</p>
        </div>

        <div class="card ev-card">
            <div class="card-body">
                <form method="POST" id="campaignForm" action="{{ route('admin.campaigns.update', $campaign) }}" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="col-12 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="ev-section-label">Core Message</div>
                            <div class="card-title">Email Content</div>
                            <div class="ev-help">Keep the message short, strong, and focused.</div>
                        </div>
                        <span class="ev-pill">Evangelism</span>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" name="subject" id="subject" value="{{ old('subject', $campaign->subject) }}" class="form-control">
                        @error('subject')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Preheader</label>
                        <input type="text" name="preheader" id="preheader" value="{{ old('preheader', $campaign->preheader) }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Plain Message (fallback)</label>
                        <textarea name="message" rows="6" class="form-control">{{ old('message', $campaign->message) }}</textarea>
                        @error('message')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email Body (Rich Text)</label>
                        <div class="d-flex gap-2 mb-2 ev-toolbar flex-wrap">
                            <button type="button" class="btn btn-sm btn-light" onclick="formatDoc('bold')">Bold</button>
                            <button type="button" class="btn btn-sm btn-light" onclick="formatDoc('italic')">Italic</button>
                            <button type="button" class="btn btn-sm btn-light" onclick="formatDoc('insertUnorderedList')">Bullets</button>
                            <button type="button" class="btn btn-sm btn-light" onclick="formatDoc('insertOrderedList')">Numbered</button>
                            <button type="button" class="btn btn-sm btn-light" onclick="insertLink()">Link</button>
                        </div>
                        <div id="editor" contenteditable="true" class="form-control ev-editor">
                            {!! old('body_html', $campaign->body_html) !!}
                        </div>
                        <textarea name="body_html" id="body_html" class="d-none"></textarea>
                        <div class="ev-help mt-2">Tip: end with a clear invitation or next step.</div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="ev-section-label">Delivery</div>
                        <div class="card-title">Schedule or send now</div>
                        <div class="ev-help">Scheduled campaigns go out automatically to active subscribers.</div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" @selected(old('status', $campaign->status) === 'draft')>Draft</option>
                            <option value="scheduled" @selected(old('status', $campaign->status) === 'scheduled')>Scheduled</option>
                            <option value="sent" @selected(old('status', $campaign->status) === 'sent')>Send Now</option>
                        </select>
                        @error('status')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Scheduled At (Africa/Kigali)</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
                        @error('scheduled_at')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mt-2">
                        <div class="ev-section-label">Media</div>
                        <div class="card-title">Add visuals and resources</div>
                        <div class="ev-help">Optional. Add only what supports the message.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Featured Image (upload)</label>
                        <input type="file" name="featured_image" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Featured Image URL (optional)</label>
                        <input type="text" name="featured_image_url" value="{{ old('featured_image_url', $campaign->featured_image_url) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Video URL (YouTube)</label>
                        <input type="text" name="video_url" value="{{ old('video_url', $campaign->video_url) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Video File (optional)</label>
                        <input type="file" name="video_file" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Audio URL (optional)</label>
                        <input type="text" name="audio_url" value="{{ old('audio_url', $campaign->audio_url) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Audio File (optional)</label>
                        <input type="file" name="audio_file" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Document/Book URL (optional)</label>
                        <input type="text" name="document_url" value="{{ old('document_url', $campaign->document_url) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Document/Book File (optional)</label>
                        <input type="file" name="document_file" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CTA Text</label>
                        <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text', $campaign->cta_text) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CTA URL</label>
                        <input type="text" name="cta_url" id="cta_url" value="{{ old('cta_url', $campaign->cta_url) }}" class="form-control">
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Save Campaign</button>
                        <a href="{{ route('admin.campaigns.preview', $campaign) }}" class="btn btn-outline-primary">Preview</a>
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
function formatDoc(command) {
    document.execCommand(command, false, null);
    syncEditor();
}
function insertLink() {
    const url = prompt('Enter URL');
    if (url) {
        document.execCommand('createLink', false, url);
        syncEditor();
    }
}
function syncEditor() {
    const editor = document.getElementById('editor');
    const hidden = document.getElementById('body_html');
    hidden.value = editor.innerHTML.trim();
}
document.addEventListener('DOMContentLoaded', () => {
    syncEditor();
    document.getElementById('editor').addEventListener('input', syncEditor);
});
</script>
@endsection
