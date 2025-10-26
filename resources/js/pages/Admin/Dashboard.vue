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
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                <BookOpen class="h-6 w-6 text-blue-600 dark:text-blue-400" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-muted-foreground">Tổng khóa học</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold">{{ stats.totalCourses }}</p>
          </CardContent>
        </Card>

        <!-- Active Courses -->
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                <CheckCircle class="h-6 w-6 text-green-600 dark:text-green-400" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-muted-foreground">Khóa học hoạt động</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold">{{ stats.activeCourses }}</p>
          </CardContent>
        </Card>

        <!-- Total Students -->
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                <Users class="h-6 w-6 text-yellow-600 dark:text-yellow-400" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-muted-foreground">Học sinh đăng ký</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold">{{ stats.totalStudents }}</p>
          </CardContent>
        </Card>

        <!-- Total Teachers -->
        <Card>
          <CardHeader>
            <div class="flex items-center justify-between">
              <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                <GraduationCap class="h-6 w-6 text-purple-600 dark:text-purple-400" />
              </div>
            </div>
            <CardTitle class="text-sm font-medium text-muted-foreground">Giáo viên</CardTitle>
          </CardHeader>
          <CardContent>
            <p class="text-3xl font-bold">{{ stats.totalTeachers }}</p>
          </CardContent>
        </Card>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <Card>
          <CardHeader class="border-b">
            <CardTitle>Hoạt động khóa học gần đây</CardTitle>
          </CardHeader>
          <CardContent class="pt-6">
            <div class="space-y-4">
              <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm">Khóa học mới: "Lập trình Python cơ bản"</p>
                  <p class="text-xs text-muted-foreground">2 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm">45 học sinh đăng ký khóa "Web Development"</p>
                  <p class="text-xs text-muted-foreground">4 giờ trước</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-2 h-2 bg-yellow-600 rounded-full"></div>
                <div class="ml-3">
                  <p class="text-sm">Khóa "Machine Learning" sắp mở</p>
                  <p class="text-xs text-muted-foreground">6 giờ trước</p>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Quick Stats -->
        <Card>
          <CardHeader class="border-b">
            <CardTitle>Thống kê khóa học</CardTitle>
          </CardHeader>
          <CardContent class="pt-6">
            <div class="space-y-4">
              <div class="flex justify-between items-center">
                <span class="text-sm text-muted-foreground">Khóa học đang mở</span>
                <span class="text-sm font-medium">18/24</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-muted-foreground">Tỷ lệ hoàn thành</span>
                <span class="text-sm font-medium">85%</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-muted-foreground">Đánh giá trung bình</span>
                <span class="text-sm font-medium">4.8/5</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-muted-foreground">Học sinh trung bình/khóa</span>
                <span class="text-sm font-medium">51</span>
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