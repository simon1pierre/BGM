<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ExportDataSnapshotCommand extends Command
{
    protected $signature = 'data:snapshot-seed
        {--path=database/seeders/snapshots/latest : Output directory for JSON snapshot files}
        {--tables= : Comma-separated table list. Default exports all non-system tables}';

    protected $description = 'Export current database rows into JSON snapshot files for future seeding.';

    /**
     * @var array<int, string>
     */
    private array $excludedTables = [
        'migrations',
        'failed_jobs',
        'password_reset_tokens',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'sessions',
        'personal_access_tokens',
    ];

    public function handle(): int
    {
        $outputPath = base_path((string) $this->option('path'));
        File::ensureDirectoryExists($outputPath);

        $tablesOption = trim((string) $this->option('tables'));
        $tables = $tablesOption !== ''
            ? array_values(array_filter(array_map(fn ($table) => $this->normalizeTableName(trim($table)), explode(',', $tablesOption))))
            : $this->defaultTables();

        if (count($tables) === 0) {
            $this->error('No tables found to export.');
            return self::FAILURE;
        }

        $this->info('Exporting '.count($tables).' table(s) to '.$outputPath);

        $manifest = [
            'exported_at' => now()->toDateTimeString(),
            'connection' => config('database.default'),
            'tables' => [],
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                $this->warn("Skipping missing table: {$table}");
                continue;
            }

            $rows = DB::table($table)->get()->map(fn ($row) => (array) $row)->all();
            File::put(
                $outputPath.DIRECTORY_SEPARATOR.$table.'.json',
                json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            );

            $manifest['tables'][] = [
                'name' => $table,
                'rows' => count($rows),
            ];

            $this->line(sprintf(' - %s: %d row(s)', $table, count($rows)));
        }

        File::put(
            $outputPath.DIRECTORY_SEPARATOR.'manifest.json',
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        );

        $this->info('Snapshot export complete.');
        $this->line('Seed using: php artisan db:seed --class=Database\\Seeders\\DatabaseSnapshotSeeder');

        return self::SUCCESS;
    }

    /**
     * @return array<int, string>
     */
    private function defaultTables(): array
    {
        $tables = Schema::getTableListing();
        $tables = array_values(array_unique(array_map(fn ($table) => $this->normalizeTableName((string) $table), $tables)));
        $tables = array_values(array_filter($tables, fn ($table) => !in_array($table, $this->excludedTables, true)));

        // Simple preferred order to satisfy common FK constraints first.
        $priority = [
            'users',
            'content_categories',
            'books',
            'audiobooks',
            'audiobook_parts',
            'audios',
            'videos',
        ];

        usort($tables, function (string $a, string $b) use ($priority): int {
            $aIndex = array_search($a, $priority, true);
            $bIndex = array_search($b, $priority, true);

            $aIndex = $aIndex === false ? PHP_INT_MAX : $aIndex;
            $bIndex = $bIndex === false ? PHP_INT_MAX : $bIndex;

            if ($aIndex === $bIndex) {
                return strcmp($a, $b);
            }

            return $aIndex <=> $bIndex;
        });

        return $tables;
    }

    private function normalizeTableName(string $table): string
    {
        if (str_contains($table, '.')) {
            $segments = explode('.', $table);
            return (string) end($segments);
        }

        return $table;
    }
}


