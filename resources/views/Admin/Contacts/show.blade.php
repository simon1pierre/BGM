@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Contact Message</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.contacts.index') }}">Contact Inbox</a></li>
                    <li class="breadcrumb-item">Details</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Message Details</h6>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8">{{ $contactMessage->name ?: 'Anonymous' }}</dd>
                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8">{{ $contactMessage->email }}</dd>
                                <dt class="col-sm-4">Phone</dt>
                                <dd class="col-sm-8">{{ $contactMessage->phone ?: '-' }}</dd>
                                <dt class="col-sm-4">Subject</dt>
                                <dd class="col-sm-8">{{ $contactMessage->subject ?: 'No subject' }}</dd>
                                <dt class="col-sm-4">Received</dt>
                                <dd class="col-sm-8">{{ $contactMessage->created_at?->format('M d, Y H:i') }}</dd>
                                <dt class="col-sm-4">Locale</dt>
                                <dd class="col-sm-8">{{ $contactMessage->locale ?: '-' }}</dd>
                                <dt class="col-sm-4">Message</dt>
                                <dd class="col-sm-8"><div class="border rounded p-3 bg-light" style="white-space:pre-line;">{{ $contactMessage->message }}</div></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Reply</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.contacts.reply', $contactMessage) }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Reply Subject</label>
                                    <input type="text" name="subject" class="form-control" required value="{{ old('subject', $contactMessage->reply_subject ?: ('Re: '.($contactMessage->subject ?: 'Your message'))) }}">
                                    @error('subject') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Reply Body</label>
                                    <textarea name="body" rows="9" class="form-control" required>{{ old('body', $contactMessage->reply_body) }}</textarea>
                                    @error('body') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                </div>
                                <button class="btn btn-primary">Send Reply</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Reply Status</h6>
                        </div>
                        <div class="card-body">
                            @if ($contactMessage->replied_at)
                                <div class="alert alert-success mb-0">
                                    Replied {{ $contactMessage->replied_at->diffForHumans() }}
                                    @if ($contactMessage->repliedByUser)
                                        by {{ $contactMessage->repliedByUser->email }}.
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-warning mb-0">No reply sent yet.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
