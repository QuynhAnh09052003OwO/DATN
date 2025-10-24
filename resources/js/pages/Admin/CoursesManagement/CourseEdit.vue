<template>
  <AdminLayout title="Chỉnh sửa khóa học - Admin" page-title="Chỉnh sửa khóa học">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold text-gray-900">Chỉnh sửa khóa học</h2>
          <p class="text-gray-600">Cập nhật thông tin khóa học</p>
        </div>
        <button 
          @click="goBack"
          class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors"
        >
          Quay lại
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="updateCourse" class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin cơ bản</h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tên khóa học -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Tên khóa học <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.title"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nhập tên khóa học"
              />
              <div v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</div>
            </div>

            <!-- Mô tả -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="Nhập mô tả khóa học"
              ></textarea>
              <div v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</div>
            </div>

            <!-- Loại khóa học -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Loại khóa học <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.type"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="video">Video (Record sẵn)</option>
                <option value="zoom">Zoom (Học trực tiếp)</option>
              </select>
              <div v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</div>
            </div>

            <!-- Trạng thái -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Trạng thái <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="draft">Bản nháp</option>
                <option value="released">Đã phát hành</option>
              </select>
              <div v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status }}</div>
            </div>

            <!-- Danh mục -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Danh mục</label>
              <select
                v-model="form.category_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Chọn danh mục</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <div v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</div>
            </div>

            <!-- Hình ảnh -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh</label>
              <input
                v-model="form.image"
                type="url"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="https://example.com/image.jpg"
              />
              <div v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</div>
            </div>

            <!-- Giá -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Giá (VNĐ) <span class="text-red-500">*</span>
              </label>
              <input
                v-model.number="form.price"
                type="number"
                min="0"
                step="1000"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="0"
              />
              <div v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</div>
            </div>

            <!-- Thời lượng -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Thời lượng (phút)</label>
              <input
                v-model.number="form.duration"
                type="number"
                min="0"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="0"
              />
              <div v-if="errors.duration" class="mt-1 text-sm text-red-600">{{ errors.duration }}</div>
            </div>

            <!-- Giáo viên -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Giáo viên</label>
              <select
                v-model="form.teacher_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              >
                <option value="">Chọn giáo viên</option>
                <option v-for="teacher in teachers" :key="teacher.id" :value="teacher.id">
                  {{ teacher.name }} ({{ teacher.email }})
                </option>
              </select>
              <div v-if="errors.teacher_id" class="mt-1 text-sm text-red-600">{{ errors.teacher_id }}</div>
            </div>

            <!-- Đã xuất bản -->
            <div class="md:col-span-2">
              <div class="flex items-center">
                <input
                  v-model="form.is_published"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label class="ml-2 block text-sm text-gray-900">
                  Đã xuất bản
                </label>
              </div>
              <div v-if="errors.is_published" class="mt-1 text-sm text-red-600">{{ errors.is_published }}</div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
          <button
            type="button"
            @click="goBack"
            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
          >
            Hủy
          </button>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            {{ isSubmitting ? 'Đang lưu...' : 'Lưu thay đổi' }}
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Props
const props = defineProps({
  course: Object,
  categories: Array,
  teachers: Array
})

// Reactive data
const isSubmitting = ref(false)
const errors = ref({})

const form = reactive({
  title: props.course?.title || '',
  description: props.course?.description || '',
  type: props.course?.type || 'video',
  status: props.course?.status || 'draft',
  category_id: props.course?.category_id || null,
  image: props.course?.image || '',
  price: props.course?.price || 0,
  duration: props.course?.duration || 0,
  is_published: props.course?.is_published || false,
  teacher_id: props.course?.teacher_id || null
})

// Methods
const updateCourse = async () => {
  isSubmitting.value = true
  errors.value = {}

  try {
    router.put(`/admin/courses/${props.course.id}`, form, {
      onError: (errs) => {
        errors.value = errs
        isSubmitting.value = false
      },
      onSuccess: () => {
        isSubmitting.value = false
      }
    })
  } catch (error) {
    console.error('Error updating course:', error)
    isSubmitting.value = false
  }
}

const goBack = () => {
  router.visit('/admin/courses')
}

onMounted(() => {
  // Initialize form with course data
  if (props.course) {
    Object.assign(form, {
      title: props.course.title || '',
      description: props.course.description || '',
      type: props.course.type || 'video',
      status: props.course.status || 'draft',
      category_id: props.course.category_id || null,
      image: props.course.image || '',
      price: props.course.price || 0,
      duration: props.course.duration || 0,
      is_published: props.course.is_published || false,
      teacher_id: props.course.teacher_id || null
    })
  }
})
</script>
