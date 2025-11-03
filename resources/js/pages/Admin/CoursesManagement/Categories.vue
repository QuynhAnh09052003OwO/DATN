<template>
  <AdminLayout title="Phân loại khóa học - Admin" page-title="Quản lý khóa học">
    <div class="space-y-6">
      <!-- Header Actions -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Phân loại danh mục khóa học</h2>
          <p class="text-gray-600">Quản lý các danh mục khóa học trong hệ thống</p>
        </div>
        <button 
          @click="createCategory"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Thêm danh mục
        </button>
      </div>

      <!-- Categories List -->
      <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Danh sách danh mục</h3>
        </div>
        
        <div v-if="categories.length === 0" class="px-6 py-12 text-center">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Chưa có danh mục nào</h3>
          <p class="mt-1 text-sm text-gray-500">Bắt đầu bằng cách tạo danh mục đầu tiên.</p>
          <div class="mt-6">
            <button @click="createCategory" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
              <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Thêm danh mục
            </button>
          </div>
        </div>

        <div v-else class="overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Danh mục
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Mô tả
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Số khóa học
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Trạng thái
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Ngày tạo
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Thao tác
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" :style="{ backgroundColor: category.color + '20' }">
                      <div class="h-4 w-4 rounded-full" :style="{ backgroundColor: category.color }"></div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                      <div class="text-sm text-gray-500">{{ category.slug }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ category.description || 'Chưa có mô tả' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ category.courses_count || 0 }} khóa học
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="[
                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                    category.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                  ]">
                    {{ category.is_active ? 'Hoạt động' : 'Tạm dừng' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(category.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button 
                      @click="editCategory(category.id)"
                      class="text-blue-600 hover:text-blue-900"
                    >
                      Chỉnh sửa
                    </button>
                    <button 
                      @click="deleteCategory(category.id)"
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
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Props
const props = defineProps({
  categories: Array
})

const page = usePage()

// Listen for flash messages
onMounted(() => {
  // Success message from backend
  if (page.props.flash?.success) {
    toast.success(page.props.flash.success)
  }
  
  // Error message from backend
  if (page.props.flash?.error) {
    toast.error(page.props.flash.error)
  }
  
  // Info message from backend
  if (page.props.flash?.info) {
    toast.info(page.props.flash.info)
  }
})

// Methods
const createCategory = () => {
  router.visit('/admin/courses/categories/create')
}

const editCategory = (categoryId) => {
  router.visit(`/admin/courses/categories/${categoryId}/edit`)
}

const deleteCategory = (categoryId) => {
  const category = props.categories.find(cat => cat.id === categoryId)
  const categoryName = category?.name || 'danh mục này'
  
  toast.warning(`Bạn có chắc chắn muốn xóa "${categoryName}"?`, {
    action: {
      label: 'Xóa',
      onClick: () => {
        router.delete(`/admin/courses/categories/${categoryId}`, {
          preserveScroll: true,
          onSuccess: async () => {
            await nextTick()
            const page = usePage()
            const flash = page.props.flash || {}
            const stillExists = (page.props.categories || []).some(c => c.id === categoryId)
            if (flash.error) {
              toast.error(flash.error)
              return
            }
            if (stillExists) {
              const cat = (page.props.categories || []).find(c => c.id === categoryId)
              const count = cat?.courses_count ?? 0
              toast.error(`Không thể xóa danh mục vì đang liên kết với ${count} khóa học.`)
              return
            }
            if (flash.success) {
              toast.success(flash.success)
            } else {
              toast.success(`Đã xóa danh mục "${categoryName}" thành công!`)
            }
          },
          onError: () => {
            toast.error('Có lỗi xảy ra khi xóa danh mục')
          }
        })
      }
    },
    cancel: {
      label: 'Hủy',
      onClick: () => {}
    },
    duration: 5000
  })
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('vi-VN')
}
</script>
