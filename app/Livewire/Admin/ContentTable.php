<?php

namespace App\Livewire\Admin;

use App\Models\audio;
use App\Models\book;
use App\Models\video;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ContentTable extends Component
{
    use WithPagination;

    public string $type = 'videos';
    public string $search = '';
    public string $status = 'all';
    public string $featured = 'all';
    public string $deleted = 'exclude';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'featured' => ['except' => 'all'],
        'deleted' => ['except' => 'exclude'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingFeatured(): void
    {
        $this->resetPage();
    }

    public function updatingDeleted(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortField = $field;
        $this->sortDirection = 'asc';
    }

    private function modelClass(): string
    {
        return match ($this->type) {
            'audios' => audio::class,
            'documents' => book::class,
            default => video::class,
        };
    }

    private function searchColumns(): array
    {
        return match ($this->type) {
            'audios' => ['title', 'description', 'speaker', 'series'],
            'documents' => ['title', 'description', 'author', 'category'],
            default => ['title', 'description', 'speaker', 'series'],
        };
    }

    private function buildQuery(): Builder
    {
        $model = $this->modelClass();
        $query = $model::query();

        if ($this->deleted === 'with') {
            $query->withTrashed();
        } elseif ($this->deleted === 'only') {
            $query->onlyTrashed();
        }

        if ($this->status !== 'all') {
            $query->where('is_published', $this->status === 'published');
        }

        if ($this->featured !== 'all') {
            $query->where('featured', $this->featured === 'yes');
        }

        if ($this->search !== '') {
            $query->where(function (Builder $builder): void {
                foreach ($this->searchColumns() as $column) {
                    $builder->orWhere($column, 'like', '%'.$this->search.'%');
                }
            });
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        return view('livewire.admin.content-table', [
            'items' => $this->buildQuery()->paginate(15),
            'type' => $this->type,
        ]);
    }
}
