<template>
  <div class="min-h-screen bg-blue-50">
    <Head title="Khóa học - DoraEdu" />
    
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo and Navigation -->
          <div class="flex items-center space-x-8">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M5 16L3 8l5.5 5L12 4l3.5 9L21 8l-2 8H5zm2.7-2h8.6l.9-4.4L14 12l-2-5.5L10 12l-3.2-2.4L7.7 14z"/>
                </svg>
              </div>
              <span class="text-2xl font-bold text-blue-800">DoraEdu</span>
            </div>
            
            <!-- Navigation Links -->
            <nav class="hidden md:flex space-x-6">
              <Link href="#" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors underline">VỀ DORAEDU</Link>
              <Link href="/courses" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors underline">KHÓA HỌC</Link>
              <Link href="#" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors underline">SÁCH</Link>
              <Link href="#" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors underline">FLASHCARD</Link>
            </nav>
          </div>

          <!-- Auth Buttons -->
          <div class="flex items-center space-x-4">
            <Link 
              href="/login" 
              class="text-blue-800 font-semibold hover:text-blue-600 transition-colors border border-blue-800 px-4 py-2 rounded-lg"
            >
              Đăng nhập
            </Link>
            <Link 
              href="/register" 
              class="bg-blue-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-900 transition-colors"
            >
              Đăng ký
            </Link>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Page Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
          Danh sách khóa học
        </h1>
        <p class="text-lg text-gray-600">
          Khám phá các khóa học chất lượng cao tại DoraEdu
        </p>
      </div>

      <!-- Filter Section -->
      <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Tìm kiếm</label>
            <input 
              type="text" 
              placeholder="Tìm kiếm khóa học..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Phân loại khóa học</label>
            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
              <option value="">Tất cả</option>
              <option value="N5">N5</option>
              <option value="N4">N4</option>
              <option value="N3">N3</option>
              <option value="N2">N2</option>
              <option value="N1">N1</option>
              <option value="JLPT">JLPT</option>
              <option value="Kaiwa">Kaiwa</option>
              <option value="Business">Business</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Courses Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Course Card 1 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600">Video-Based</span>
              <span class="text-sm text-gray-500">4.8 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Lập trình Python cơ bản</h3>
            <p class="text-gray-600 mb-4">Học lập trình Python từ cơ bản đến nâng cao với các bài tập thực hành</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600">2,500,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>

        <!-- Course Card 2 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Online</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">4.9 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Web Development với React</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Xây dựng ứng dụng web hiện đại với React và các công nghệ liên quan</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600 dark:text-green-400">3,200,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>

        <!-- Course Card 3 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-700 to-blue-900 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Offline</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">4.7 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Machine Learning cơ bản</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Giới thiệu về Machine Learning và AI với các bài tập thực hành</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600 dark:text-green-400">4,500,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>

        <!-- Course Card 4 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Video-Based</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">4.6 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Database Design</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Thiết kế và quản lý cơ sở dữ liệu hiệu quả</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600 dark:text-green-400">2,800,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>

        <!-- Course Card 5 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Online</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">4.8 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Toán học nâng cao</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Các chủ đề toán học nâng cao cho học sinh THPT</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600 dark:text-green-400">1,800,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>

        <!-- Course Card 6 -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
          <div class="h-48 bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
              </svg>
            </div>
          </div>
          <div class="p-6">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Offline</span>
              <span class="text-sm text-gray-500 dark:text-gray-400">4.9 ⭐</span>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Vật lý ứng dụng</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4">Học vật lý qua các thí nghiệm và ứng dụng thực tế</p>
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600 dark:text-green-400">2,200,000 VNĐ</span>
              <Link 
                href="/register" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition-colors"
              >
                Đăng ký ngay
              </Link>
            </div>
          </div>
        </div>
      </div>

      <!-- Call to Action -->
      <div class="mt-16 text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
          <h2 class="text-3xl font-bold text-gray-900 mb-4">
            Sẵn sàng bắt đầu học tập?
          </h2>
          <p class="text-lg text-gray-600 mb-6">
            Đăng ký tài khoản ngay để truy cập vào các khóa học chất lượng cao
          </p>
          <div class="flex justify-center space-x-4">
            <Link 
              href="/register" 
              class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors"
            >
              Đăng ký ngay
            </Link>
            <Link 
              href="/login" 
              class="border border-blue-600 text-blue-600 hover:bg-blue-50 px-8 py-3 rounded-lg font-semibold transition-colors"
            >
              Đăng nhập
            </Link>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3'
</script>

<style scoped>
/* Custom font for handwritten style titles */
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.handwritten {
  font-family: 'Kalam', cursive;
}
</style>
