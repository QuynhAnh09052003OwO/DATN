<template>
  <div>
    <Head title="Teacher Dashboard" />
    <div class="p-6">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Teacher Dashboard</h1>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Khóa học của tôi</h2>
          <p class="text-gray-600">Quản lý các khóa học đang dạy</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Học sinh</h2>
          <p class="text-gray-600">Quản lý danh sách học sinh</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-4">Lịch dạy</h2>
          <p class="text-gray-600">Xem lịch dạy và lên kế hoạch</p>
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
