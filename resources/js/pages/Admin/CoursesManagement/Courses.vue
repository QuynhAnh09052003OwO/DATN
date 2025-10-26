<template>
  <AdminLayout title="Quản lý khóa học - Admin" page-title="Quản lý khóa học">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Danh sách khóa học</h2>
          <p class="text-gray-600">Tạo và quản lý tất cả khóa học trong hệ thống</p>
        </div>
        <button 
          @click="createCourse"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Thêm khóa học
        </button>
      </div>

      <!-- Search and Filter -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tìm kiếm</label>
            <input 
              v-model="search"
              type="text" 
              placeholder="Tìm theo tên khóa học..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phân loại khóa học</label>
            <select v-model="categoryFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="">Tất cả</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Loại khóa học</label>
            <select v-model="typeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="">Tất cả</option>
              <option value="video">Video</option>
              <option value="zoom">Zoom</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Courses Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Khóa học
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Loại
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Danh mục
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Trạng thái
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Giá
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ngày tạo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Thao tác
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="course in filteredCourses" :key="course.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ course.title }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    course.type === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'
                  ]">
                    {{ course.type === 'video' ? 'Video' : 'Zoom' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ course.category?.name || 'Chưa phân loại' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    course.status === 'released' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                  ]">
                    {{ course.status === 'released' ? 'Đã phát hành' : 'Bản nháp' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatPrice(course.price) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(course.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button 
                      @click="editCourse(course.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Sửa
                    </button>
                    <button 
                      v-if="course.status === 'draft'"
                      @click="publishCourse(course.id)"
                      class="text-green-600 hover:text-green-900"
                    >
                      Publish
                    </button>
                    <button 
                      @click="deleteCourse(course.id)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Xóa
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="courses.data && courses.data.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
          <button 
            v-if="courses.prev_page_url"
            @click="goToPage(courses.current_page - 1)"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Trước
          </button>
          <button 
            v-if="courses.next_page_url"
            @click="goToPage(courses.current_page + 1)"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Sau
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Hiển thị <span class="font-medium">{{ courses.from || 0 }}</span> đến <span class="font-medium">{{ courses.to || 0 }}</span> của <span class="font-medium">{{ courses.total || 0 }}</span> kết quả
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button 
                v-if="courses.prev_page_url"
                @click="goToPage(courses.current_page - 1)"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              >
                Trước
              </button>
              <button 
                v-for="page in visiblePages" 
                :key="page"
                @click="goToPage(page)"
                :class="[
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                  page === courses.current_page 
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                ]"
              >
                {{ page }}
              </button>
              <button 
                v-if="courses.next_page_url"
                @click="goToPage(courses.current_page + 1)"
                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
              >
                Sau
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Props
const props = defineProps({
  courses: Object,
  categories: Array
})

// Reactive data
const search = ref('')
const categoryFilter = ref('')
const typeFilter = ref('')

// Computed
const filteredCourses = computed(() => {
  if (!props.courses?.data) return []
  
  return props.courses.data.filter(course => {
    const matchesSearch = course.title.toLowerCase().includes(search.value.toLowerCase()) ||
                         course.description?.toLowerCase().includes(search.value.toLowerCase())
    const matchesCategory = !categoryFilter.value || course.category_id == categoryFilter.value
    const matchesType = !typeFilter.value || course.type === typeFilter.value
    
    return matchesSearch && matchesCategory && matchesType
  })
})

const visiblePages = computed(() => {
  if (!props.courses) return []
  
  const current = props.courses.current_page
  const last = props.courses.last_page
  const pages = []
  
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  return pages
})

// Methods
const createCourse = () => {
  router.post('/admin/courses')
}

const editCourse = (courseId) => {
  router.visit(`/admin/courses/${courseId}/edit`)
}

const publishCourse = (courseId) => {
  if (confirm('Bạn có chắc chắn muốn phát hành khóa học này?')) {
    router.patch(`/admin/courses/${courseId}/publish`)
  }
}

const deleteCourse = (courseId) => {
  if (confirm('Bạn có chắc chắn muốn xóa khóa học này?')) {
    router.delete(`/admin/courses/${courseId}`)
  }
}

const goToPage = (page) => {
  router.visit(`/admin/courses?page=${page}`)
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN')
}

const formatDuration = (duration) => {
  if (!duration) return 'Chưa xác định'
  
  // duration now represents hours directly (e.g., 1.5 = 1 giờ 30 phút)
  const hours = Math.floor(duration)
  const decimalPart = duration - hours
  const minutes = Math.round(decimalPart * 60)
  
  if (hours > 0 && minutes > 0) {
    return `${hours} giờ ${minutes} phút`
  } else if (hours > 0) {
    return `${hours} giờ`
  } else {
    return `${minutes} phút`
  }
}

onMounted(() => {
  // Initialize any necessary data
})
</script>
