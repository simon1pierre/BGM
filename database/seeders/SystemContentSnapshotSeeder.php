<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SystemContentSnapshotSeeder extends Seeder
{
    /**
     * Core content tables only (no logs/analytics/session/cache tables).
     *
     * @var array<int, string>
     */
    private array $tables = [
        'users',
        'content_categories',
        'video_series',
        'ministry_leaders',
        'books',
        'audiobooks',
        'audiobook_parts',
        'videos',
        'audios',
        'playlists',
        'playlist_items',
        'settings',
        'setting_translations',
        'content_translations',
        'events',
    ];

    public function run(): void
    {
        $snapshotPath = database_path('seeders/snapshots/content');
        if (!File::isDirectory($snapshotPath)) {
            $this->command?->warn('System content snapshot directory not found: '.$snapshotPath);
            return;
        }

        $driver = DB::getDriverName();
        $this->disableForeignKeys($driver);

        foreach ($this->tables as $table) {
            $file = $snapshotPath.DIRECTORY_SEPARATOR.$table.'.json';
            if (!File::exists($file)) {
                continue;
            }

            $rows = json_decode((string) File::get($file), true);
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



