<template>
  <PublicLayout title="DoraEdu - Trang chủ">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
    </div>
  </PublicLayout>
</template>

<script setup>
import PublicLayout from '@/layouts/PublicLayout.vue'
</script>

<style scoped>
/* Custom font for handwritten style titles */
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.handwritten {
  font-family: 'Kalam', cursive;
}
</style>