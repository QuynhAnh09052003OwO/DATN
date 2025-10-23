<template>
  <div>
    <Head title="Student Dashboard" />
    <div class="p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Student Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Khóa học của tôi</h2>
          <p class="text-gray-600">Xem các khóa học đã đăng ký</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Hồ sơ cá nhân</h2>
          <p class="text-gray-600">Cập nhật thông tin cá nhân</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Tiến độ học tập</h2>
          <p class="text-gray-600">Theo dõi tiến độ và điểm số</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
  user: Object
})
</script>
