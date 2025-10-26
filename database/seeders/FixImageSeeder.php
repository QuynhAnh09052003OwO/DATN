<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class FixImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a simple placeholder image SVG
        $svg = '<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
  <rect width="400" height="300" fill="#4F46E5"/>
  <text x="200" y="150" text-anchor="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-size="24" font-weight="bold">Course Image</text>
</svg>';

        // Create the missing image file
        $filename = '1761404897_Screenshot_2025-06-15_013842.png';
        $storagePath = storage_path('app/public/course-images/' . $filename);
        $publicPath = public_path('storage/course-images/' . $filename);
        
        // Create a simple PNG placeholder
        $pngContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        
        // Save to both locations
        if (!file_exists(dirname($storagePath))) {
            mkdir(dirname($storagePath), 0755, true);
        }
        if (!file_exists(dirname($publicPath))) {
            mkdir(dirname($publicPath), 0755, true);
        }
        
        file_put_contents($storagePath, $pngContent);
        file_put_contents($publicPath, $pngContent);
        
        $this->command->info('Created missing image file: ' . $filename);
        $this->command->info('Storage path: ' . $storagePath);
        $this->command->info('Public path: ' . $publicPath);
        $this->command->info('File exists in storage: ' . (file_exists($storagePath) ? 'YES' : 'NO'));
        $this->command->info('File exists in public: ' . (file_exists($publicPath) ? 'YES' : 'NO'));
    }
}
