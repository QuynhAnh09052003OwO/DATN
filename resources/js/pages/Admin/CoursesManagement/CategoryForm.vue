<template>
  <AdminLayout title="Danh mục khóa học - Admin" :page-title="isEdit ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới'">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">
            {{ isEdit ? 'Chỉnh sửa danh mục' : 'Thêm danh mục mới' }}
          </h2>
          <p class="text-gray-600">
            {{ isEdit ? 'Cập nhật thông tin danh mục' : 'Tạo danh mục khóa học mới' }}
          </p>
        </div>
        <button 
          @click="goBack"
          class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors"
        >
          Quay lại
        </button>
      </div>

      <!-- Form -->
      <div class="bg-white rounded-lg shadow">
        <form @submit.prevent="submitForm" class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tên danh mục -->
            <div class="md:col-span-2">
              <Label>
                Tên danh mục <span class="text-red-500">*</span>
              </Label>
              <Input
                v-model="form.name"
                type="text"
                required
                placeholder="Nhập tên danh mục"
              />
              <div v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</div>
            </div>

            <!-- Mô tả -->
            <div class="md:col-span-2">
              <Label>Mô tả</Label>
              <textarea
                v-model="form.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                placeholder="Nhập mô tả danh mục"
              ></textarea>
              <div v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</div>
            </div>

            <!-- Màu sắc -->
            <div>
              <Label>Màu sắc</Label>
              <div class="flex items-center space-x-4">
                <input
                  v-model="form.color"
                  type="color"
                  class="h-10 w-20 border border-gray-300 rounded-lg cursor-pointer"
                />
                <Input
                  v-model="form.color"
                  type="text"
                  placeholder="#3B82F6"
                  class="flex-1"
                />
              </div>
              <div v-if="errors.color" class="mt-1 text-sm text-red-600">{{ errors.color }}</div>
            </div>

            <!-- Trạng thái -->
            <div>
              <Label>Trạng thái</Label>
              <div class="flex items-center">
                <input
                  v-model="form.is_active"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label class="ml-2 block text-sm text-gray-900">
                  Danh mục hoạt động
                </label>
              </div>
              <div v-if="errors.is_active" class="mt-1 text-sm text-red-600">{{ errors.is_active }}</div>
            </div>
          </div>

          <!-- Preview -->
          <div v-if="form.name" class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="text-sm font-medium text-gray-700 mb-2">Xem trước:</h4>
            <div class="flex items-center space-x-3">
              <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center" :style="{ backgroundColor: form.color + '20' }">
                <div class="h-4 w-4 rounded-full" :style="{ backgroundColor: form.color }"></div>
              </div>
              <div>
                <div class="text-sm font-medium text-gray-900">{{ form.name }}</div>
                <div class="text-sm text-gray-500">{{ form.description || 'Chưa có mô tả' }}</div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end space-x-4 mt-6">
            <button 
              type="button"
              @click="goBack"
              class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Hủy
            </button>
            <button 
              type="submit"
              :disabled="isSubmitting"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
            >
              <span v-if="isSubmitting">Đang lưu...</span>
              <span v-else>{{ isEdit ? 'Cập nhật' : 'Tạo mới' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'

// Props
const props = defineProps({
  category: Object
})

// Computed
const isEdit = computed(() => !!props.category)

// Reactive data
const isSubmitting = ref(false)
const errors = ref({})

const form = reactive({
  name: '',
  description: '',
  color: '#3B82F6',
  is_active: true
})

// Methods
const submitForm = async () => {
  isSubmitting.value = true
  errors.value = {}

  try {
    if (isEdit.value) {
      router.put(`/admin/courses/categories/${props.category.id}`, form)
    } else {
      router.post('/admin/courses/categories', form)
    }
  } catch (error) {
    console.error('Form submission error:', error)
  } finally {
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.visit('/admin/courses/categories')
}

// Initialize form data
onMounted(() => {
  if (props.category) {
    Object.assign(form, {
      name: props.category.name || '',
      description: props.category.description || '',
      color: props.category.color || '#3B82F6',
      is_active: props.category.is_active !== undefined ? props.category.is_active : true
    })
  }
})
</script>
