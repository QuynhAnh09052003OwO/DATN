<template>
  <div class="min-h-screen bg-blue-50">
    <Head title="DoraEdu - Trang chủ" />
    
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
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Three Course Cards -->
      <div class="space-y-8">
        <!-- Video-Based Courses Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
          <div class="flex items-center space-x-8">
            <!-- Illustration -->
            <div class="flex-shrink-0">
              <div class="w-32 h-32 bg-blue-100 rounded-lg flex items-center justify-center">
                <!-- SVG placeholder for head with gears -->
                <svg class="w-24 h-24 text-blue-600" viewBox="0 0 100 100" fill="currentColor">
                  <!-- Head silhouette -->
                  <circle cx="50" cy="40" r="25" fill="currentColor" opacity="0.8"/>
                  <!-- Gears inside head -->
                  <circle cx="40" cy="35" r="8" fill="#f97316" opacity="0.7"/>
                  <circle cx="60" cy="45" r="6" fill="#3b82f6" opacity="0.7"/>
                  <circle cx="50" cy="50" r="4" fill="#f97316" opacity="0.7"/>
                  <!-- Small lines around head -->
                  <path d="M20 30 L25 35 M75 35 L80 30 M15 50 L20 50 M80 50 L85 50" stroke="currentColor" stroke-width="2" fill="none"/>
                  <circle cx="15" cy="50" r="2" fill="currentColor"/>
                </svg>
              </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1">
              <h2 class="text-3xl font-bold text-blue-800 mb-4 handwritten">Video-Based Courses</h2>
              <ul class="text-lg text-gray-700 space-y-2">
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Các khóa học qua video
                </li>
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Học mọi lúc mọi nơi, dành cho người bận rộn
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Online Courses Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
          <div class="flex items-center space-x-8">
            <!-- Illustration -->
            <div class="flex-shrink-0">
              <div class="w-32 h-32 bg-green-100 rounded-lg flex items-center justify-center">
                <!-- SVG placeholder for woman with laptop -->
                <svg class="w-24 h-24 text-green-600" viewBox="0 0 100 100" fill="currentColor">
                  <!-- Woman figure -->
                  <circle cx="50" cy="25" r="12" fill="currentColor" opacity="0.8"/>
                  <path d="M35 40 Q50 35 65 40 L60 60 Q50 55 40 60 Z" fill="currentColor" opacity="0.6"/>
                  <!-- Laptop -->
                  <rect x="30" y="45" width="40" height="25" rx="2" fill="currentColor" opacity="0.4"/>
                  <rect x="32" y="47" width="36" height="15" rx="1" fill="white"/>
                  <!-- Person on screen -->
                  <circle cx="45" cy="52" r="3" fill="#8b5cf6" opacity="0.8"/>
                  <path d="M35 55 Q45 52 55 55" stroke="#8b5cf6" stroke-width="1" fill="none"/>
                </svg>
              </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1">
              <h2 class="text-3xl font-bold text-blue-800 mb-4 handwritten">Online Courses</h2>
              <ul class="text-lg text-gray-700 space-y-2">
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Các khóa học trực tuyến qua Zoom
                </li>
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Tương tác vui nhộn
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Offline Courses Card -->
        <div class="bg-white rounded-lg shadow-lg p-8">
          <div class="flex items-center space-x-8">
            <!-- Illustration -->
            <div class="flex-shrink-0">
              <div class="w-32 h-32 bg-purple-100 rounded-lg flex items-center justify-center">
                <!-- SVG placeholder for head with gears (same as first) -->
                <svg class="w-24 h-24 text-purple-600" viewBox="0 0 100 100" fill="currentColor">
                  <!-- Head silhouette -->
                  <circle cx="50" cy="40" r="25" fill="currentColor" opacity="0.8"/>
                  <!-- Gears inside head -->
                  <circle cx="40" cy="35" r="8" fill="#f97316" opacity="0.7"/>
                  <circle cx="60" cy="45" r="6" fill="#3b82f6" opacity="0.7"/>
                  <circle cx="50" cy="50" r="4" fill="#f97316" opacity="0.7"/>
                  <!-- Small lines around head -->
                  <path d="M20 30 L25 35 M75 35 L80 30 M15 50 L20 50 M80 50 L85 50" stroke="currentColor" stroke-width="2" fill="none"/>
                  <circle cx="15" cy="50" r="2" fill="currentColor"/>
                </svg>
              </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1">
              <h2 class="text-3xl font-bold text-blue-800 mb-4 handwritten">Offline Courses</h2>
              <ul class="text-lg text-gray-700 space-y-2">
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Học trực tiếp tại cơ sở của DoraEdu
                </li>
                <li class="flex items-center">
                  <span class="w-2 h-2 bg-blue-600 rounded-full mr-3"></span>
                  Cam kết chất lượng
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, usePage, Head } from '@inertiajs/vue3'

const page = usePage()
</script>

<style scoped>
/* Custom font for handwritten style titles */
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.handwritten {
  font-family: 'Kalam', cursive;
}
</style>