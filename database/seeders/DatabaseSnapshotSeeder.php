<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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

            DB::table($table)->delete();
            if ($driver === 'sqlite') {
                DB::statement("DELETE FROM sqlite_sequence WHERE name = '{$table}'");
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
            DB::statement("SET session_replication_role = 'replica'");
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
            DB::statement("SET session_replication_role = 'origin'");
        }
    }
}









