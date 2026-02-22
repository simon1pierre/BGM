<?php

namespace App\Http\Controllers\Admin\Contacts;

use App\Http\Controllers\Controller;
use App\Mail\ContactReplyMailable;
use App\Models\ContactMessage;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->with('repliedByUser');

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('subject', 'like', '%'.$search.'%')
                    ->orWhere('message', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $status = (string) $request->query('status');
            if ($status === 'unread') {
                $query->where('is_read', false);
            } elseif ($status === 'read') {
                $query->where('is_read', true);
            } elseif ($status === 'replied') {
                $query->whereNotNull('replied_at');
            }
        }

        if ($request->filled('deleted')) {
            $deleted = (string) $request->query('deleted');
            if ($deleted === 'with') {
                $query->withTrashed();
            } elseif ($deleted === 'only') {
                $query->onlyTrashed();
            }
        }

        $messages = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $unreadCount = ContactMessage::query()->where('is_read', false)->count();

        return view('Admin.Contacts.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (!$contactMessage->is_read) {
            $contactMessage->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        $contactMessage->load('repliedByUser');

        return view('Admin.Contacts.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
        ]);

        Mail::to($contactMessage->email)->send(
            new ContactReplyMailable(
                $validated['subject'],
                $validated['body'],
                $contactMessage
            )
        );

        $contactMessage->update([
            'is_read' => true,
            'read_at' => $contactMessage->read_at ?? now(),
            'replied_at' => now(),
            'replied_by' => Auth::id(),
            'reply_subject' => $validated['subject'],
            'reply_body' => $validated['body'],
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'contact_message_replied',
            'meta' => [
                'contact_message_id' => $contactMessage->id,
                'email' => $contactMessage->email,
                'subject' => $validated['subject'],
            ],
        ]);

        return redirect()
            ->route('admin.contacts.show', $contactMessage)
            ->with('status', 'Reply sent successfully.');
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'contact_message_deleted',
            'meta' => [
                'contact_message_id' => $contactMessage->id,
                'email' => $contactMessage->email,
            ],
        ]);

        return redirect()->route('admin.contacts.index')->with('status', 'Message moved to trash.');
    }

    public function restore(int $contactMessage)
    {
        $record = ContactMessage::withTrashed()->findOrFail($contactMessage);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'contact_message_restored',
            'meta' => [
                'contact_message_id' => $record->id,
                'email' => $record->email,
            ],
        ]);

        return redirect()->route('admin.contacts.index')->with('status', 'Message restored.');
    }

    public function forceDelete(int $contactMessage)
    {
        $record = ContactMessage::withTrashed()->findOrFail($contactMessage);
        $email = $record->email;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'contact_message_force_deleted',
            'meta' => [
                'contact_message_id' => $contactMessage,
                'email' => $email,
            ],
        ]);

        return redirect()->route('admin.contacts.index')->with('status', 'Message permanently deleted.');
    }
}
