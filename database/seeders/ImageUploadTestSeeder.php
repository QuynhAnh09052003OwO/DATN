<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;

class ImageUploadTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo file ảnh test với tên có ký tự đặc biệt (giống lỗi thực tế)
        $testContent = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==';
        
        // Test 1: Tên file bình thường
        $filename1 = 'test-upload-' . time() . '.png';
        $path1 = storage_path('app/public/course-images/' . $filename1);
        file_put_contents($path1, base64_decode($testContent));
        
        // Test 2: Tên file có khoảng trắng (giống lỗi thực tế)
        $filename2 = time() . '_Screenshot 2025-06-27 175814.png';
        $path2 = storage_path('app/public/course-images/' . $filename2);
        file_put_contents($path2, base64_decode($testContent));
        
        // Test 3: Tên file có ký tự đặc biệt
        $filename3 = time() . '_Screenshot%202025-06-27%20175814.png';
        $path3 = storage_path('app/public/course-images/' . $filename3);
        file_put_contents($path3, base64_decode($testContent));
        
        // Kiểm tra file có tồn tại không
        $this->command->info('Test 1 - Normal filename:');
        $this->command->info('  File: ' . $filename1);
        $this->command->info('  Storage exists: ' . (file_exists($path1) ? 'YES' : 'NO'));
        $this->command->info('  Public exists: ' . (file_exists(public_path('storage/course-images/' . $filename1)) ? 'YES' : 'NO'));
        $this->command->info('  URL: ' . asset('storage/course-images/' . $filename1));
        
        $this->command->info('Test 2 - Space in filename:');
        $this->command->info('  File: ' . $filename2);
        $this->command->info('  Storage exists: ' . (file_exists($path2) ? 'YES' : 'NO'));
        $this->command->info('  Public exists: ' . (file_exists(public_path('storage/course-images/' . $filename2)) ? 'YES' : 'NO'));
        $this->command->info('  URL: ' . asset('storage/course-images/' . $filename2));
        
        $this->command->info('Test 3 - URL encoded filename:');
        $this->command->info('  File: ' . $filename3);
        $this->command->info('  Storage exists: ' . (file_exists($path3) ? 'YES' : 'NO'));
        $this->command->info('  Public exists: ' . (file_exists(public_path('storage/course-images/' . $filename3)) ? 'YES' : 'NO'));
        $this->command->info('  URL: ' . asset('storage/course-images/' . $filename3));
        
        // Cập nhật course với ảnh test
        $course = Course::find(52);
        if ($course) {
            $course->update([
                'image' => asset('storage/course-images/' . $filename1)
            ]);
            $this->command->info('Updated course 52 with test image: ' . $course->image);
        }
    }
}
