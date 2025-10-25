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
              
              <!-- Image Preview -->
              <div v-if="imagePreview" class="mb-4">
                <img 
                  :src="imagePreview" 
                  alt="Preview" 
                  class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                />
                <button 
                  @click="removeImage"
                  type="button"
                  class="mt-2 text-sm text-red-600 hover:text-red-800"
                >
                  Xóa ảnh
                </button>
              </div>
              
              <!-- Upload Area -->
              <div 
                class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors"
                @dragover.prevent
                @dragenter.prevent
                @drop.prevent="handleDrop"
              >
                <input
                  ref="fileInput"
                  type="file"
                  accept="image/*"
                  @change="handleFileSelect"
                  class="hidden"
                />
                
                <div v-if="!imagePreview" class="space-y-2">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <div class="text-sm text-gray-600">
                    <button 
                      @click="$refs.fileInput.click()"
                      type="button"
                      class="font-medium text-blue-600 hover:text-blue-500"
                    >
                      Chọn ảnh từ thiết bị
                    </button>
                    hoặc kéo thả ảnh vào đây
                  </div>
                  <p class="text-xs text-gray-500">PNG, JPG, GIF tối đa 10MB</p>
                </div>
                
                <div v-else class="space-y-2">
                  <button 
                    @click="$refs.fileInput.click()"
                    type="button"
                    class="text-sm text-blue-600 hover:text-blue-500"
                  >
                    Thay đổi ảnh
                  </button>
                </div>
              </div>
              
              <!-- URL Input (Alternative) -->
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hoặc nhập URL ảnh</label>
                <input
                  v-model="form.image"
                  type="url"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  placeholder="https://example.com/image.jpg"
                />
              </div>
              
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
const imagePreview = ref('')
const selectedFile = ref(null)

const form = reactive({
  title: props.course?.title || '',
  description: props.course?.description || '',
  type: props.course?.type || 'video',
  category_id: props.course?.category_id || null,
  image: props.course?.image || '',
  price: props.course?.price || 0,
  duration: props.course?.duration || 0,
  is_published: props.course?.is_published || false,
  teacher_id: props.course?.teacher_id || null
})

// Methods
const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    processFile(file)
  }
}

const handleDrop = (event) => {
  const files = event.dataTransfer.files
  if (files.length > 0) {
    processFile(files[0])
  }
}

const processFile = (file) => {
  // Validate file size (10MB max)
  if (file.size > 10 * 1024 * 1024) {
    alert('File quá lớn. Vui lòng chọn file nhỏ hơn 10MB.')
    return
  }
  
  // Validate file type
  if (!file.type.startsWith('image/')) {
    alert('Vui lòng chọn file ảnh.')
    return
  }
  
  selectedFile.value = file
  
  // Create preview
  const reader = new FileReader()
  reader.onload = (e) => {
    imagePreview.value = e.target.result
  }
  reader.readAsDataURL(file)
}

const removeImage = () => {
  imagePreview.value = ''
  selectedFile.value = null
  form.image = ''
  // Reset file input
  const fileInput = document.querySelector('input[type="file"]')
  if (fileInput) {
    fileInput.value = ''
  }
}

const updateCourse = async () => {
  isSubmitting.value = true
  errors.value = {}

  try {
    // Create FormData for file upload
    const formData = new FormData()
    
    // Add form fields
    Object.keys(form).forEach(key => {
      if (form[key] !== null && form[key] !== undefined) {
        formData.append(key, form[key])
      }
    })
    
    // Add image file if selected
    if (selectedFile.value) {
      formData.append('image_file', selectedFile.value)
    }
    
    router.post(`/admin/courses/${props.course.id}`, {
      _method: 'PUT',
      ...form,
      image_file: selectedFile.value
    }, {
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
      category_id: props.course.category_id || null,
      image: props.course.image || '',
      price: props.course.price || 0,
      duration: props.course.duration || 0,
      is_published: props.course.is_published || false,
      teacher_id: props.course.teacher_id || null
    })
    
    // Set image preview if course has image
    if (props.course.image) {
      imagePreview.value = props.course.image
    }
  }
})
</script>
