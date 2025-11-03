<template>
  <PublicLayout :title="course.title">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left: Course Info -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-start gap-4">
            <img v-if="course.image" :src="course.image" :alt="course.title" class="w-40 h-28 object-cover rounded" />
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-gray-900">{{ course.title }}</h1>
              <div class="mt-2 flex flex-wrap items-center gap-2">
                <span :class="['px-2 py-1 text-xs rounded-full', course.type === 'video' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800']">{{ course.type }}</span>
                <span class="text-sm text-gray-600">Thời lượng: {{ formatDuration(course.duration) }}</span>
              </div>
              <div class="mt-3 text-xl font-semibold text-green-600">{{ formatPrice(course.price) }}</div>
            </div>
          </div>
          <div class="mt-4 prose max-w-none text-gray-800">
            <p v-if="course.description">{{ course.description }}</p>
            <p v-else class="text-gray-500">Chưa có mô tả cho khóa học.</p>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-3">Giảng viên</h2>
          <div v-if="course.teachers && course.teachers.length" class="space-y-2">
            <div v-for="t in course.teachers" :key="t.id" class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold">{{ initials(t.name) }}</div>
              <div>
                <div class="font-medium text-gray-900">{{ t.name }}</div>
                <div class="text-sm text-gray-500">{{ t.email }}</div>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500">Chưa có giảng viên.</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-3">Phân loại</h2>
          <div v-if="course.categories && course.categories.length" class="flex flex-wrap gap-2">
            <span v-for="c in course.categories" :key="c.id" class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">{{ c.name }}</span>
          </div>
          <div v-else class="text-gray-500">Chưa có phân loại.</div>
        </div>
      </div>

      <!-- Right: Curriculum -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Lộ trình học</h2>
          <div v-if="course.sections && course.sections.length" class="space-y-4">
            <div v-for="(s, sIdx) in course.sections" :key="s.id" class="border rounded-lg">
              <div class="px-4 py-3 bg-gray-50 border-b rounded-t-lg font-medium">Học phần {{ sIdx + 1 }}: {{ s.title }}</div>
              <div class="p-4 space-y-3">
                <p v-if="s.description" class="text-sm text-gray-600">{{ s.description }}</p>
                <div v-if="s.lessons && s.lessons.length" class="space-y-2">
                  <div v-for="(l, lIdx) in s.lessons" :key="l.id" class="flex items-center gap-2 text-gray-800">
                    <span class="text-blue-600">Bài {{ lIdx + 1 }}:</span> <span class="font-medium">{{ l.title }}</span>
                    <span v-if="l.video_duration" class="text-xs text-gray-500 ml-auto">{{ l.video_duration }} phút</span>
                  </div>
                </div>
                <div v-if="s.tests && s.tests.length" class="space-y-2">
                  <div v-for="(t, tIdx) in s.tests" :key="t.id" class="flex items-center gap-2 text-gray-800">
                    <span class="text-rose-600">Kiểm tra {{ tIdx + 1 }}:</span> <span class="font-medium">{{ t.title }}</span>
                    <span v-if="t.time_limit" class="text-xs text-gray-500 ml-auto">{{ t.time_limit }} phút</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500">Chưa có lộ trình cho khóa học.</div>
        </div>
      </div>
    </div>
  </PublicLayout>
  
</template>

<script setup>
import PublicLayout from '@/layouts/PublicLayout.vue'
import { computed } from 'vue'

const props = defineProps({
  course: Object
})

const course = computed(() => props.course || {})

const formatPrice = (price) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0)
const formatDuration = (d) => (d == null ? '—' : `${Number(d).toFixed(1)} giờ`)
const initials = (name) => (name || '').split(' ').map(p => p[0]).join('').slice(0,2).toUpperCase()
</script>


