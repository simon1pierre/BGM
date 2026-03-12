<?php

namespace App\Livewire\Admin;

use App\Models\Audio;
use App\Models\Book;
use App\Models\Audiobook;
use App\Models\ContentCategory;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ContentTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $type = 'videos';
    public string $search = '';
    public string $status = 'all';
    public string $featured = 'all';
    public string $prayer = 'all';
    public string $deleted = 'exclude';
    public string $categoryId = '';
    public int $perPage = 5;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
        'featured' => ['except' => 'all'],
        'prayer' => ['except' => 'all'],
        'deleted' => ['except' => 'exclude'],
        'categoryId' => ['except' => ''],
        'perPage' => ['except' => 5],
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

    public function updatingPrayer(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
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
            'audios' => Audio::class,
            'audiobooks' => Audiobook::class,
            'documents' => Book::class,
            default => Video::class,
        };
    }

    private function searchColumns(): array
    {
        return match ($this->type) {
            'audios' => ['title', 'description', 'speaker', 'series'],
            'audiobooks' => ['title', 'description', 'narrator', 'series'],
            'documents' => ['title', 'description', 'author'],
            default => ['title', 'description', 'speaker', 'series'],
        };
    }

    private function buildQuery(): Builder
    {
        $model = $this->modelClass();
        $query = $model::query()->with('category');
        if ($this->type === 'documents') {
            $query->with(['audiobooks' => function ($relation) {
                $relation->select('id', 'book_id')->orderByDesc('id');
            }]);
        }

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

        if ($this->type === 'audiobooks' && $this->prayer !== 'all') {
            $query->where('is_prayer_audio', $this->prayer === 'yes');
        }

        if ($this->search !== '') {
            $query->where(function (Builder $builder): void {
                foreach ($this->searchColumns() as $column) {
                    $builder->orWhere($column, 'like', '%'.$this->search.'%');
                }
            });
        }

        if ($this->categoryId !== '') {
            $query->where('category_id', $this->categoryId);
        }

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $categories = ContentCategory::query()
            ->whereIn('type', match ($this->type) {
                'audios' => ['audio', 'all'],
                'audiobooks' => ['audio', 'all'],
                'documents' => ['document', 'all'],
                default => ['video', 'all'],
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.admin.content-table', [
            'items' => $this->buildQuery()->paginate($this->perPage),
            'type' => $this->type,
            'categories' => $categories,
        ]);
    }
}








