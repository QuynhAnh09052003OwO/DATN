<template>
  <AdminLayout title="Quản lý học viên" page-title="Học viên">
    <div class="space-y-6">
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-semibold text-gray-900">Danh sách học viên</h2>
        </div>

        <div v-if="students?.data?.length" class="divide-y">
          <div v-for="s in students.data" :key="s.id" class="py-3 flex items-center justify-between">
            <div>
              <div class="font-medium text-gray-900">{{ s.name }}</div>
              <div class="text-sm text-gray-500">{{ s.email }}</div>
            </div>
            <Link :href="`/admin/students/${s.id}/edit`" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Chỉnh sửa</Link>
          </div>
        </div>

        <div v-else class="text-gray-500">Không có học viên.</div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  students: Object,
})
</script>

