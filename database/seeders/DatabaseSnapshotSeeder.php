<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DatabaseSnapshotSeeder extends Seeder
{
    public function run(): void
    {
        $snapshotPath = database_path('seeders/snapshots/latest');
        if (!File::isDirectory($snapshotPath)) {
            $this->command?->warn('Snapshot directory not found: '.$snapshotPath);
            return;
        }

        $manifestPath = $snapshotPath.DIRECTORY_SEPARATOR.'manifest.json';
        $tables = [];
        if (File::exists($manifestPath)) {
            $manifest = json_decode((string) File::get($manifestPath), true);
            $tables = collect($manifest['tables'] ?? [])->pluck('name')->filter()->values()->all();
        }

        if (count($tables) === 0) {
            $tables = collect(File::files($snapshotPath))
                ->map(fn ($file) => $file->getFilename())
                ->filter(fn ($filename) => str_ends_with($filename, '.json') && $filename !== 'manifest.json')
                ->map(fn ($filename) => pathinfo($filename, PATHINFO_FILENAME))
                ->values()
                ->all();
        }
        $tables = $this->orderTables($tables);

        $driver = DB::getDriverName();
        $this->disableForeignKeys($driver);

        foreach ($tables as $table) {
            $path = $snapshotPath.DIRECTORY_SEPARATOR.$table.'.json';
            if (!File::exists($path)) {
                continue;
            }

            $rows = json_decode((string) File::get($path), true);
            if (!is_array($rows)) {
                $rows = [];
            }
            $rows = $this->filterRowsToColumns($table, $rows);

            if ($driver === 'pgsql') {
                $this->truncatePostgresTable($table);
            } else {
                DB::table($table)->delete();
                if ($driver === 'sqlite') {
                    DB::statement("DELETE FROM sqlite_sequence WHERE name = '{$table}'");
                }
            }

            foreach (array_chunk($rows, 300) as $chunk) {
                if (count($chunk) > 0) {
                    DB::table($table)->insert($chunk);
                }
            }

            $this->command?->line(sprintf('Seeded %s (%d row(s))', $table, count($rows)));
        }

        $this->enableForeignKeys($driver);
    }

    private function disableForeignKeys(string $driver): void
    {
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            return;
        }
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
            return;
        }
        if ($driver === 'pgsql') {
            // Render-managed Postgres does not allow changing session_replication_role.
            return;
        }
    }

    private function enableForeignKeys(string $driver): void
    {
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return;
        }
        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
            return;
        }
        if ($driver === 'pgsql') {
            return;
        }
    }

    /**
     * @param array<int, string> $tables
     * @return array<int, string>
     */
    private function orderTables(array $tables): array
    {
        if (count($tables) <= 1) {
            return $tables;
        }

        // Preferred order to satisfy common FK constraints.
        $priority = [
            'users',
            'content_categories',
            'video_series',
            'videos',
            'books',
            'audiobooks',
            'audiobook_parts',
            'audios',
            'devotionals',
            'user_activity_logs',
            'admin_notification_reads',
        ];

        $priorityIndex = array_flip($priority);
        $originalIndex = array_flip($tables);

        usort($tables, function (string $a, string $b) use ($priorityIndex, $originalIndex): int {
            $aPriority = $priorityIndex[$a] ?? PHP_INT_MAX;
            $bPriority = $priorityIndex[$b] ?? PHP_INT_MAX;

            if ($aPriority === $bPriority) {
                return ($originalIndex[$a] ?? 0) <=> ($originalIndex[$b] ?? 0);
            }

            return $aPriority <=> $bPriority;
        });

        return $tables;
    }

    private function truncatePostgresTable(string $table): void
    {
        $quoted = '"'.str_replace('"', '""', $table).'"';
        DB::statement("TRUNCATE TABLE {$quoted} RESTART IDENTITY CASCADE");
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function filterRowsToColumns(string $table, array $rows): array
    {
        if (count($rows) === 0) {
            return $rows;
        }

        try {
            $columns = Schema::getColumnListing($table);
        } catch (\Throwable $e) {
            return $rows;
        }

        if (count($columns) === 0) {
            return $rows;
        }

        $allowed = array_flip($columns);
        $filtered = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $filtered[] = array_intersect_key($row, $allowed);
        }

        return $filtered;
    }
}









