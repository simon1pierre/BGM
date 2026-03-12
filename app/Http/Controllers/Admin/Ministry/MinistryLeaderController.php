<?php

namespace App\Http\Controllers\Admin\Ministry;

use App\Http\Controllers\Controller;
use App\Models\MinistryLeader;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MinistryLeaderController extends Controller
{
    public function index(Request $request)
    {
        $query = MinistryLeader::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('position', 'like', '%'.$search.'%')
                    ->orWhere('country', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('role_type')) {
            $query->where('role_type', (string) $request->query('role_type'));
        }

        if ($request->filled('status')) {
            $status = (string) $request->query('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->string('deleted') === 'with') {
            $query->withTrashed();
        } elseif ($request->string('deleted') === 'only') {
            $query->onlyTrashed();
        }

        $leaders = $query
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.MinistryLeaders.index', compact('leaders'));
    }

    public function create()
    {
        return view('Admin.MinistryLeaders.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateLeader($request);
        $photoPath = null;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ministry-leaders', 'public');
        }

        $leader = MinistryLeader::create([
            ...$validated,
            'photo_path' => $photoPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_created',
            'meta' => [
                'ministry_leader_id' => $leader->id,
                'name' => $leader->name,
            ],
        ]);

        return redirect()->route('admin.ministry-leaders.index')->with('status', 'Ministry profile created.');
    }

    public function edit(MinistryLeader $ministryLeader)
    {
        return view('Admin.MinistryLeaders.edit', compact('ministryLeader'));
    }

    public function update(Request $request, MinistryLeader $ministryLeader)
    {
        $validated = $this->validateLeader($request);
        $photoPath = $ministryLeader->photo_path;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ministry-leaders', 'public');
        }

        $ministryLeader->update([
            ...$validated,
            'photo_path' => $photoPath,
            'is_active' => $request->boolean('is_active'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_updated',
            'meta' => [
                'ministry_leader_id' => $ministryLeader->id,
                'name' => $ministryLeader->name,
            ],
        ]);

        return redirect()->route('admin.ministry-leaders.index')->with('status', 'Ministry profile updated.');
    }

    public function destroy(MinistryLeader $ministryLeader)
    {
        $ministryLeader->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_deleted',
            'meta' => [
                'ministry_leader_id' => $ministryLeader->id,
                'name' => $ministryLeader->name,
            ],
        ]);

        return redirect()->back()->with('status', 'Ministry profile deleted.');
    }

    public function restore(int $ministry_leader)
    {
        $record = MinistryLeader::withTrashed()->findOrFail($ministry_leader);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_restored',
            'meta' => [
                'ministry_leader_id' => $record->id,
                'name' => $record->name,
            ],
        ]);

        return redirect()->back()->with('status', 'Ministry profile restored.');
    }

    public function forceDelete(int $ministry_leader)
    {
        $record = MinistryLeader::withTrashed()->findOrFail($ministry_leader);
        $name = $record->name;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_force_deleted',
            'meta' => [
                'ministry_leader_id' => $ministry_leader,
                'name' => $name,
            ],
        ]);

        return redirect()->back()->with('status', 'Ministry profile permanently deleted.');
    }

    public function toggleActive(MinistryLeader $ministryLeader)
    {
        $ministryLeader->update(['is_active' => !$ministryLeader->is_active]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'ministry_leader_active_toggled',
            'meta' => [
                'ministry_leader_id' => $ministryLeader->id,
                'name' => $ministryLeader->name,
                'is_active' => $ministryLeader->is_active,
            ],
        ]);

        return back()->with('status', $ministryLeader->is_active ? 'Profile activated.' : 'Profile hidden from home page.');
    }

    private function validateLeader(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'position' => ['required', 'string', 'max:160'],
            'country' => ['nullable', 'string', 'max:120'],
            'role_type' => ['required', 'in:leader,preacher'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:60'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['nullable', 'boolean'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);
    }
}










