<template>
  <PublicLayout title="Khóa học - DoraEdu">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
        <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Tìm kiếm</label>
            <input 
              v-model="filters.search"
              type="text" 
              placeholder="Tìm kiếm khóa học..."
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900 placeholder-gray-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Phân loại khóa học</label>
            <select v-model="filters.category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
              <option value="">Tất cả</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Loại khóa học</label>
            <select v-model="filters.type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900">
              <option value="">Tất cả</option>
              <option value="video">Video (Record sẵn)</option>
              <option value="zoom">Zoom (Học trực tiếp)</option>
            </select>
          </div>
          <div class="md:col-span-3 flex justify-end">
            <button 
              type="submit"
              class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
            >
              Lọc khóa học
            </button>
          </div>
        </form>
      </div>

      <!-- Courses Grid -->
      <div v-if="courses.data && courses.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="course in courses.data" 
          :key="course.id"
          class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow course-card"
          :data-type="course.type"
        >
          <!-- Course Image -->
          <div class="h-48 relative">
            <img 
              v-if="course.image" 
              :src="course.image" 
              :alt="course.title"
              class="w-full h-full object-cover"
              @error="handleImageError"
            />
            <div 
              v-else
              class="h-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center"
            >
              <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
              </div>
            </div>
            <!-- Type Badge -->
            <div class="absolute top-4 left-4">
              <span 
                class="px-3 py-1 rounded-full text-xs font-medium"
                :class="course.type === 'video' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'"
              >
                {{ course.type === 'video' ? 'Video' : 'Zoom' }}
              </span>
            </div>
          </div>
          
          <!-- Course Content -->
          <div class="p-6">
            <!-- Category -->
            <div v-if="course.category" class="mb-2">
              <span class="text-sm font-medium text-blue-600">{{ course.category.name }}</span>
            </div>
            
            <!-- Title -->
            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ course.title }}</h3>
            
            <!-- Description -->
            <p class="text-gray-600 mb-4 line-clamp-3">{{ course.description || 'Chưa có mô tả' }}</p>
            
            <!-- Teacher -->
            <div v-if="course.teacher" class="mb-4">
              <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm text-gray-600">Giảng viên: {{ course.teacher.name }}</span>
              </div>
            </div>
            
            <!-- Price and Action -->
            <div class="flex items-center justify-between">
              <span class="text-lg font-bold text-green-600">{{ formatPrice(course.price) }}</span>
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
      
      <!-- No Courses Message -->
      <div v-else class="text-center py-12">
        <div class="bg-white rounded-lg shadow-lg p-8">
          <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Không tìm thấy khóa học nào</h3>
          <p class="text-gray-600">Hãy thử thay đổi bộ lọc để tìm khóa học phù hợp với bạn.</p>
        </div>
      </div>
      
      <!-- Pagination -->
      <div v-if="courses.data && courses.data.length > 0" class="mt-8 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button 
            v-for="page in paginationPages" 
            :key="page"
            @click="goToPage(page)"
            :class="[
              'px-3 py-2 text-sm font-medium rounded-md transition-colors',
              page === courses.current_page 
                ? 'bg-blue-600 text-white' 
                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
            ]"
          >
            {{ page }}
          </button>
        </nav>
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
        </div>d
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import PublicLayout from '@/layouts/PublicLayout.vue'

// Props
const props = defineProps({
  courses: Object,
  categories: Array,
  filters: Object
})

// Reactive data
const filters = ref({
  search: props.filters?.search || '',
  category: props.filters?.category || '',
  type: props.filters?.type || ''
})

// Computed
const paginationPages = computed(() => {
  if (!props.courses?.last_page) return []
  
  const pages = []
  const current = props.courses.current_page
  const last = props.courses.last_page
  
  // Show first page
  if (current > 3) {
    pages.push(1)
    if (current > 4) pages.push('...')
  }
  
  // Show pages around current
  for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
    pages.push(i)
  }
  
  // Show last page
  if (current < last - 2) {
    if (current < last - 3) pages.push('...')
    pages.push(last)
  }
  
  return pages
})

// Methods
const applyFilters = () => {
  router.get('/courses', filters.value, {
    preserveState: true,
    replace: true
  })
}

const goToPage = (page) => {
  if (page === '...') return
  
  router.get('/courses', {
    ...filters.value,
    page: page
  }, {
    preserveState: true,
    replace: true
  })
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND'
  }).format(price)
}

const handleImageError = (event) => {
  // Replace broken image with placeholder
  const img = event.target
  const courseTitle = img.alt || 'Course'
  const courseType = img.closest('.course-card')?.dataset.type || 'video'
  
  // Generate a simple placeholder
  const colors = {
    'video': { bg: '#4F46E5', text: '#FFFFFF' },
    'zoom': { bg: '#10B981', text: '#FFFFFF' }
  }
  
  const color = colors[courseType] || colors['video']
  const shortTitle = courseTitle.substring(0, 20)
  
  // Create SVG placeholder
  const svg = `<svg width="400" height="300" xmlns="http://www.w3.org/2000/svg">
    <rect width="400" height="300" fill="${color.bg}"/>
    <text x="200" y="150" text-anchor="middle" fill="${color.text}" font-family="Arial, sans-serif" font-size="24" font-weight="bold">${shortTitle}</text>
    <text x="200" y="180" text-anchor="middle" fill="${color.text}" font-family="Arial, sans-serif" font-size="16" opacity="0.8">${courseType.toUpperCase()} COURSE</text>
  </svg>`
  
  img.src = 'data:image/svg+xml;base64,' + btoa(svg)
}
</script>

<style scoped>
/* Custom font for handwritten style titles */
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.handwritten {
  font-family: 'Kalam', cursive;
}

/* Line clamp utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
