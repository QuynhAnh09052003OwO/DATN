<template>
  <AdminLayout title="Tổng quan - Admin Dashboard" page-title="Tổng quan">
    <div class="space-y-6">
      <!-- Welcome Section -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white">
        <h2 class="text-2xl font-bold mb-2">Chào mừng, {{ user.name }}!</h2>
        <p class="text-blue-100">Bạn đang quản lý hệ thống DoraEdu với vai trò Quản trị viên</p>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Courses -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Tổng khóa học</p>
              <p class="text-2xl font-semibold text-gray-900">24</p>
            </div>
          </div>
        </div>

        <!-- Total Teachers -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Giáo viên</p>
              <p class="text-2xl font-semibold text-gray-900">12</p>
            </div>
          </div>
        </div>

        <!-- Total Students -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Học sinh</p>
              <p class="text-2xl font-semibold text-gray-900">156</p>
            </div>
          </div>
        </div>

        <!-- Active Users -->
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
              <p class="text-2xl font-semibold text-gray-900">89</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Hoạt động gần đây</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">Giáo viên mới đăng ký: Nguyễn Văn A</p>
                  <p class="text-xs text-gray-500">2 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">Khóa học mới: "Lập trình Python"</p>
                  <p class="text-xs text-gray-500">4 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-yellow-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">15 học sinh mới đăng ký</p>
                  <p class="text-xs text-gray-500">6 giờ trước</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Stats -->
        <div class="bg-white rounded-lg shadow">
          <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Thống kê nhanh</h3>
          </div>
          <div class="p-6">
            <div class="space-y-4">
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Khóa học đang mở</span>
                <span class="text-sm font-medium text-gray-900">18/24</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Tỷ lệ hoàn thành</span>
                <span class="text-sm font-medium text-gray-900">85%</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-gray-600">Đánh giá trung bình</span>
                <span class="text-sm font-medium text-gray-900">4.8/5</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
</script>