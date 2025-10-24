<template>
  <AdminLayout title="Quản lý danh mục - DoraEdu" page-title="Quản lý danh mục">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Phân loại khóa học</h2>
        <Link 
          href="/admin/categories/create" 
          class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          Tạo phân loại mới
        </Link>
      </div>

      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Status Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Trạng thái
            </label>
            <select 
              v-model="filters.status"
              @change="applyFilters"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="active">Đang hoạt động</option>
              <option value="inactive">Không hoạt động</option>
            </select>
          </div>

          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Tìm kiếm
            </label>
            <input 
              v-model="filters.search"
              @input="debouncedSearch"
              type="text"
              placeholder="Tìm theo tên phân loại..."
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            />
          </div>

        </div>
      </div>

      <!-- Categories Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  #
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Tên danh mục
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Trạng thái
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Số khóa học
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-if="categories.data.length === 0">
                <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                  Không có danh mục nào
                </td>
              </tr>
              <tr v-for="(category, index) in categories.data" :key="category.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
            
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ category.name }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ category.slug }}
                  </div>
                </td>
        
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': category.is_active,
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': !category.is_active
                    }"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ category.is_active ? 'Hoạt động' : 'Không hoạt động' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ category.courses_count }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                  {{ formatDate(category.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <Link
                      :href="`/admin/categories/${category.id}`"
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                      Xem
                    </Link>
                    <Link
                      :href="`/admin/categories/${category.id}/edit`"
                      class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                      Sửa
                    </Link>
                    <button
                      @click="deleteCategory(category.id)"
                      class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { debounce } from 'lodash-es'

const props = defineProps({
  categories: Object,
  filters: Object,
})

const filters = ref({
  search: props.filters.search || '',
  status: props.filters.status || '',
  per_page: props.filters.per_page || 15,
})

// Format date
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Apply filters
const applyFilters = () => {
  router.get('/admin/categories', filters.value, {
    preserveState: true,
    replace: true,
  })
}

// Debounced search
const debouncedSearch = debounce(() => {
  applyFilters()
}, 500)

// Watch for filter changes
watch(filters, () => {
  applyFilters()
}, { deep: true })

// Delete category
const deleteCategory = (categoryId) => {
  if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
    router.delete(`/admin/categories/${categoryId}`, {
      onSuccess: () => {
        // Show success message if needed
      }
    })
  }
}
</script>
