<template>
  <AdminLayout :title="`Chỉnh sửa học viên - ${student.name}`" page-title="Chỉnh sửa học viên">
    <div class="space-y-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Thông tin học viên</h2>
          <div class="mt-2 text-gray-700">
            <div class="font-medium">{{ student.name }}</div>
            <div class="text-sm text-gray-500">{{ student.email }}</div>
          </div>
        </div>

        <div>
          <h3 class="font-medium text-gray-900 mb-2">Gán khóa học</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 max-h-[400px] overflow-y-auto">
            <label v-for="c in courses" :key="c.id" class="flex items-start gap-3 border rounded-lg p-3 hover:bg-gray-50">
              <input type="checkbox" class="mt-1" :value="c.id" v-model="selectedCourseIds">
              <div class="flex-1">
                <div class="font-medium text-gray-900">{{ c.title }}</div>
                <div class="text-xs text-gray-500">{{ formatPrice(c.price) }}</div>
              </div>
            </label>
          </div>
          <div class="mt-4 flex justify-end gap-3">
            <Link href="/admin/students" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">Hủy</Link>
            <button @click="save" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Lưu</button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
  </template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  student: Object,
  courses: { type: Array, default: () => [] },
  enrolled_course_ids: { type: Array, default: () => [] }
})

const selectedCourseIds = ref([...(props.enrolled_course_ids || [])])

const formatPrice = (price) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0)

const save = () => {
  router.post(`/admin/students/${props.student.id}/courses`, {
    course_ids: selectedCourseIds.value
  }, {
    preserveScroll: true
  })
}
</script>



