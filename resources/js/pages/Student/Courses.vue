<template>
  <div>
    <Head title="Khóa học của tôi" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-4">Khóa học của tôi</h1>

      <div v-if="courses && courses.length" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="c in courses"
          :key="c.id"
          class="bg-white rounded-lg shadow hover:shadow-md transition-shadow p-4 flex flex-col"
        >
          <img v-if="c.image" :src="c.image" :alt="c.title" class="w-full h-40 object-cover rounded-md" />
          <div class="mt-3 flex-1">
            <div class="text-sm text-gray-500">{{ c.categories?.map(x => x.name).join(', ') }}</div>
            <div class="font-semibold text-gray-900 mt-1 line-clamp-2">{{ c.title }}</div>
          </div>
          <div class="mt-4 flex items-center justify-between">
            <span class="text-teal-600 font-semibold">{{ formatPrice(c.price) }}</span>
            <Link :href="`/student/courses/${c.id}/learn`" class="px-3 py-1.5 bg-teal-600 text-white rounded-lg text-sm hover:bg-teal-700">Vào học</Link>
          </div>
        </div>
      </div>

      <div v-else class="bg-white rounded-lg shadow p-6 text-gray-600">Bạn chưa có khóa học nào.</div>
    </div>
  </div>
  </template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  courses: { type: Array, default: () => [] }
})

const formatPrice = (price) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0)
</script>
