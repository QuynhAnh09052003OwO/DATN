<template>
  <AdminLayout 
    title="Quản lý khóa học - Admin Dashboard" 
    page-title="Dashboard" 
    :breadcrumbs="breadcrumbs"
  >
    <div class="space-y-6">
      <!-- Welcome Section -->
      <Card class="bg-gradient-to-r from-blue-600 to-blue-800 text-white border-none">
        <CardHeader>
          <CardTitle class="text-white text-2xl">Chào mừng, {{ user.name }}!</CardTitle>
          <CardDescription class="text-blue-100">
            Dashboard quản lý khóa học và hệ thống DoraEdu
          </CardDescription>
        </CardHeader>
      </Card>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Courses -->
        <Card class="bg-gradient-to-br from-blue-600 to-blue-700 text-white border-none">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-white/20">
                <BookOpen class="h-6 w-6 text-white" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-white">Tổng khóa học</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold text-white">{{ stats.totalCourses }}</p>
          </CardContent>
        </Card>

        <!-- Active Courses -->
        <Card class="bg-gradient-to-br from-green-600 to-green-700 text-white border-none">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-white/20">
                <CheckCircle class="h-6 w-6 text-white" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-white">Khóa học hoạt động</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold text-white">{{ stats.activeCourses }}</p>
          </CardContent>
        </Card>

        <!-- Total Students -->
        <Card class="bg-gradient-to-br from-yellow-600 to-yellow-700 text-white border-none">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-white/20">
                <Users class="h-6 w-6 text-white" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-white">Học sinh đăng ký</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold text-white">{{ stats.totalStudents }}</p>
          </CardContent>
        </Card>

        <!-- Total Teachers -->
        <Card class="bg-gradient-to-br from-purple-600 to-purple-700 text-white border-none">
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-white/20">
                <GraduationCap class="h-6 w-6 text-white" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-white">Giáo viên</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold text-white">{{ stats.totalTeachers }}</p>
          </CardContent>
        </Card>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <Card class="bg-gradient-to-br from-blue-400 to-indigo-100 border-blue-200">
          <CardHeader class="border-b border-blue-200">
            <CardTitle class="text-blue-900">Hoạt động khóa học gần đây</CardTitle>
          </CardHeader>
          <CardContent class="pt-6">
            <div class="space-y-4">
              <div class="flex items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">Khóa học mới: "Lập trình Python cơ bản"</p>
                  <p class="text-xs text-gray-600">2 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">45 học sinh đăng ký khóa "Web Development"</p>
                  <p class="text-xs text-gray-600">4 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <div class="w-2 h-2 bg-yellow-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm text-gray-900">Khóa "Machine Learning" sắp mở</p>
                  <p class="text-xs text-gray-600">6 giờ trước</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Quick Stats -->
        <Card class="bg-gradient-to-br from-green-300 to-indigo-100 border-blue-200">
          <CardHeader class="border-b border-blue-200">
            <CardTitle class="text-blue-900">Thống kê khóa học</CardTitle>
          </CardHeader>
          <CardContent class="pt-6">
            <div class="space-y-3">
              <div class="flex justify-between items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <span class="text-sm text-gray-700">Khóa học đang mở</span>
                <span class="text-sm font-semibold text-blue-900">18/24</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <span class="text-sm text-gray-700">Tỷ lệ hoàn thành</span>
                <span class="text-sm font-semibold text-blue-900">85%</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <span class="text-sm text-gray-700">Đánh giá trung bình</span>
                <span class="text-sm font-semibold text-blue-900">4.8/5</span>
              </div>
              <div class="flex justify-between items-center p-3 bg-white/60 rounded-lg hover:bg-white/80 transition-colors">
                <span class="text-sm text-gray-700">Học sinh trung bình/khóa</span>
                <span class="text-sm font-semibold text-blue-900">51</span>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { BookOpen, CheckCircle, Users, GraduationCap, Clock } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user
const props = defineProps({
  stats: {
    type: Object,
    required: true,
    default: () => ({
      totalCourses: 0,
      activeCourses: 0,
      totalStudents: 0,
      totalTeachers: 0,
    })
  }
})

const breadcrumbs = [
  { title: 'Admin', href: '/admin' },
  { title: 'Dashboard' }
]
</script>