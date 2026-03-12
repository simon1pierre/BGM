<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $latestSnapshotManifest = database_path('seeders/snapshots/latest/manifest.json');
        if (File::exists($latestSnapshotManifest)) {
            $this->call([
                DatabaseSnapshotSeeder::class,
            ]);

            return;
        }

        $contentSnapshotManifest = database_path('seeders/snapshots/content/manifest.json');
        if (File::exists($contentSnapshotManifest)) {
            $this->call([
                SystemContentSnapshotSeeder::class,
            ]);

            return;
        }

        $this->call([
            UserSeeder::class,
            ContentCategorySeeder::class,
            VideoSeriesSeeder::class,
            VideoSeeder::class,
            DevotionalSeeder::class,
        ]);
    }
}


