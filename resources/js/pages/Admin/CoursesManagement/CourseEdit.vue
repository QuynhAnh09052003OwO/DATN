<template>
  <AdminLayout title="Chỉnh sửa khóa học - Admin" page-title="Chỉnh sửa khóa học">
    <div class="space-y-6">
      <!-- Toast success -->
      <transition name="fade">
        <div v-if="toast.show" class="fixed right-6 bottom-6 z-50 rounded-lg bg-green-600 text-white px-4 py-2 shadow-lg">
          {{ toast.message }}
        </div>
      </transition>
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
              <Label>
                Tên khóa học <span class="text-red-500">*</span>
              </Label>
              <Input
                v-model="form.title"
                type="text"
                required
                placeholder="Nhập tên khóa học"
              />
              <div v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</div>
            </div>

            <!-- Mô tả -->
            <div class="md:col-span-2">
              <Label>Mô tả</Label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
                placeholder="Nhập mô tả khóa học"
              ></textarea>
              <div v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</div>
            </div>

            <!-- Loại khóa học -->
            <div>
              <Label>
                Loại khóa học <span class="text-red-500">*</span>
              </Label>
              <select
                v-model="form.type"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-900"
              >
                <option value="video">Video (Record sẵn)</option>
                <option value="zoom">Zoom (Học trực tiếp)</option>
              </select>
              <div v-if="errors.type" class="mt-1 text-sm text-red-600">{{ errors.type }}</div>
            </div>


            <!-- Phân loại (đa lựa chọn giống giảng viên) -->
            <div>
              <Label>Phân loại</Label>
              <div class="relative">
                <button
                  @click="showCategoryDropdown = !showCategoryDropdown"
                  type="button"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-left flex items-center justify-between"
                >
                  <span class="text-gray-700">
                    {{ selectedCategories.length > 0 ? `${selectedCategories.length} phân loại đã chọn` : 'Chọn phân loại' }}
                  </span>
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>
                <div v-if="showCategoryDropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                  <div class="p-2">
                    <div v-for="cat in categories" :key="cat.id" class="flex items-center px-3 py-2 hover:bg-gray-50 rounded">
                      <input
                        :id="`category-${cat.id}`"
                        type="checkbox"
                        :checked="form.category_ids && form.category_ids.includes(cat.id)"
                        @change="toggleCategory(cat.id)"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                      />
                      <label :for="`category-${cat.id}`" class="ml-2 text-sm text-gray-700 cursor-pointer flex-1">
                        {{ cat.name }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="selectedCategories.length > 0" class="mt-2 flex flex-wrap gap-2">
                <span
                  v-for="cat in selectedCategories"
                  :key="cat.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800"
                >
                  {{ cat.name }}
                  <button @click="removeCategory(cat.id)" type="button" class="ml-2 text-green-600 hover:text-green-800">×</button>
                </span>
              </div>
              <div v-if="errors.category_ids" class="mt-1 text-sm text-red-600">{{ errors.category_ids }}</div>
            </div>

            <!-- Hình ảnh -->
            <div>
              <Label>Hình ảnh</Label>
              
              <!-- Image Preview -->
              <div v-if="imagePreview" class="mb-4">
                <img 
                  :src="imagePreview" 
                  alt="Preview" 
                  class="w-32 h-32 object-cover rounded-lg border border-gray-300"
                  @error="handleImageError"
                  @load="handleImageLoad"
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
                <Label>Hoặc nhập URL ảnh</Label>
                <Input
                  v-model="form.image"
                  type="url"
                  placeholder="https://example.com/image.jpg"
                />
              </div>
              
              <div v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</div>
            </div>

            <!-- Giá -->
            <div>
              <Label>
                Giá (VNĐ) <span class="text-red-500">*</span>
              </Label>
              <Input
                v-model.number="form.price"
                type="number"
                min="0"
                max="50000000"
                required
                placeholder="0"
              />
              <div v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</div>
            </div>

            <!-- Số giờ học -->
            <div>
              <Label>Số giờ học</Label>
              <Input
                v-model.number="form.duration"
                type="number"
                min="0"
                step="0.5"
                placeholder="0"
              />
              <div v-if="errors.duration" class="mt-1 text-sm text-red-600">{{ errors.duration }}</div>
              <p class="mt-1 text-sm text-gray-500">Ví dụ: 1.5 = 1 giờ 30 phút, 2.0 = 2 giờ</p>
            </div>

            <!-- Giảng viên phụ trách -->
            <div>
              <Label>Giảng viên phụ trách</Label>
              
              <!-- Dropdown -->
              <div class="relative">
                <button
                  @click="showTeacherDropdown = !showTeacherDropdown"
                  type="button"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-left flex items-center justify-between"
                >
                  <span class="text-gray-700">
                    {{ selectedTeachers.length > 0 ? `${selectedTeachers.length} giảng viên đã chọn` : 'Chọn giảng viên' }}
                  </span>
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>
                
                <!-- Dropdown menu -->
                <div v-if="showTeacherDropdown" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                  <div class="p-2">
                    <div v-for="teacher in teachers" :key="teacher.id" class="flex items-center px-3 py-2 hover:bg-gray-50 rounded">
                      <input
                        :id="`teacher-${teacher.id}`"
                        type="checkbox"
                        :checked="form.teacher_ids && form.teacher_ids.includes(teacher.id)"
                        @change="toggleTeacher(teacher.id)"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                      />
                      <label :for="`teacher-${teacher.id}`" class="ml-2 text-sm text-gray-700 cursor-pointer flex-1">
                        {{ teacher.name }} ({{ teacher.email }})
                      </label>
                    </div>
                  </div>

                </div>
              </div>
              
              <!-- Selected teachers display -->
              <div v-if="selectedTeachers.length > 0" class="mt-2 flex flex-wrap gap-2">
                <span
                  v-for="teacher in selectedTeachers"
                  :key="teacher.id"
                  class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800"
                >
                  {{ teacher.name }}
                  <button
                    @click="removeTeacher(teacher.id)"
                    type="button"
                    class="ml-2 text-blue-600 hover:text-blue-800"
                  >
                    ×
                  </button>
                </span>
              </div>
              
              <div v-if="errors.teacher_id" class="mt-1 text-sm text-red-600">{{ errors.teacher_id }}</div>
            </div>
          </div>
        </div>

        <!-- Quản lý học phần -->
        <Card class="mt-6">
          <CardHeader>
            <CardTitle>Danh sách học phần</CardTitle>
            <CardDescription>Quản lý các học phần thuộc khóa học này. Có thể chỉnh sửa tiêu đề, mô tả từng học phần, cũng như thêm mới.</CardDescription>
          </CardHeader>
          <CardContent>
            <div v-for="(section, sIdx) in sections" :key="section.id" class="mb-6 border p-4 rounded-xl bg-muted/40">
              <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-4">
                <Label :for="`section-title-${sIdx}`" class="font-semibold mb-1 md:mb-0">Học phần {{ sIdx + 1 }}</Label>
                <Input :id="`section-title-${sIdx}`" v-model="section.title" placeholder="Nhập tiêu đề học phần" class="flex-1 mb-1 md:mb-0" />
                <Button type="button" variant="ghost" size="sm" class="text-red-500" @click.stop.prevent="removeSection(sIdx)">Xóa</Button>
              </div>
            <div class="mt-3">
                <Label :for="`section-desc-${sIdx}`">Mô tả bài học</Label>
                <textarea :id="`section-desc-${sIdx}`" v-model="section.description" class="w-full rounded-lg border p-2 mt-1" rows="2" placeholder="Thêm mô tả."></textarea>
              </div>


              <!-- Danh sách bài học trong học phần -->
              <div v-for="(lesson, lIdx) in section.lessons" :key="lesson.id" class="mb-4 rounded-xl border p-4 bg-white">
                <div class="flex flex-col gap-3">
                  <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                    <Label :for="`lesson-title-${sIdx}-${lIdx}`" class="font-medium mb-1 md:mb-0">Bài {{ lIdx + 1 }}</Label>
                    <Input :id="`lesson-title-${sIdx}-${lIdx}`" v-model="lesson.title" placeholder="Nhập tiêu đề bài học" class="flex-1" />
                    <button type="button" class="text-red-600 px-3 py-1" @click.stop.prevent="removeLesson(sIdx, lIdx)">Xóa</button>
                  </div>

                  <div>
                    <Label :for="`lesson-desc-${sIdx}-${lIdx}`">Mô tả bài học</Label>
                    <textarea :id="`lesson-desc-${sIdx}-${lIdx}`" v-model="lesson.description" class="w-full rounded-lg border p-2 mt-1" rows="3" placeholder="Thêm mô tả."></textarea>
                  </div>

                  <!-- Tài liệu học: giao diện giống upload ảnh của course -->
                  <div class="mt-2">
                    <Label>Tài liệu học</Label>
                    <div
                      class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors mt-1"
                    >
                      <!-- Tiles -->
                      <div class="grid grid-cols-2 gap-6 max-w-md mx-auto">
                        <!-- Video tile -->
                        <label class="cursor-pointer group">
                          <input type="file" class="hidden" accept="video/*" @change="e => onLessonVideoFileChange(sIdx, lIdx, e)" />
                          <div class="flex flex-col items-center justify-center rounded-lg border border-gray-300 p-4 group-hover:border-gray-400">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14" />
                              <rect x="3" y="6" width="12" height="12" rx="2" ry="2"></rect>
                            </svg>
                            <span class="mt-2 text-sm text-gray-700">Video</span>
                          </div>
                        </label>

                        <!-- Attachment tile -->
                        <label class="cursor-pointer group">
                          <input type="file" class="hidden" accept="application/pdf" @change="e => onLessonAttachmentChange(sIdx, lIdx, e)" />
                          <div class="flex flex-col items-center justify-center rounded-lg border border-gray-300 p-4 group-hover:border-gray-400">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z" />
                            </svg>
                            <span class="mt-2 text-sm text-gray-700">Attachment</span>
                          </div>
                        </label>
                      </div>

                      <!-- Selected previews/info -->
                      <div class="mt-4 space-y-2 text-sm text-gray-700">
                        <div v-if="lesson.videoName" class="flex items-center justify-center gap-2">
                          <span>Video đã chọn:</span>
                          <span class="font-medium">{{ lesson.videoName }}</span>
                          <button type="button" class="text-blue-600 hover:text-blue-800" @click="clearLessonVideo(sIdx, lIdx)">Gỡ</button>
                        </div>
                        <div v-if="lesson.attachmentName" class="flex items-center justify-center gap-2">
                          <span>PDF đã chọn:</span>
                          <span class="font-medium">{{ lesson.attachmentName }}</span>
                          <button type="button" class="text-blue-600 hover:text-blue-800" @click="clearLessonAttachment(sIdx, lIdx)">Gỡ</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <!-- Actions: Thêm bài học / bài kiểm tra -->
            <div class="mt-4 border-t pt-4">
              <div class="flex items-center gap-3 mb-4">
                <button type="button" @click="addLesson(sIdx)" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                  <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Thêm bài học
                </button>
                <button type="button" @click="addQuiz(sIdx)" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                  + Thêm bài kiểm tra
                </button>
              </div>
            </div>

            <!-- Section actions INSIDE the section card -->
            <div class="mt-4 flex justify-end gap-3">
              <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors" @click="resetSection(sIdx)">Hủy</button>
              <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors" @click="saveSection(sIdx)">Lưu học phần</button>
            </div>

            </div>
            <div class="flex">
              <button type="button" @click="addSection" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Học phần
              </button>
            </div>
          </CardContent>
        </Card>

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
            {{ isSubmitting ? 'Đang lưu...' : 'Lưu khóa học' }}
          </button>
        </div>
      </form>
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
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'

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
const showTeacherDropdown = ref(false)
const showCategoryDropdown = ref(false)

// Toast
const toast = reactive({ show: false, message: '' })
function showSuccess(msg) {
  toast.message = msg
  toast.show = true
  setTimeout(() => { toast.show = false }, 2000)
}

const form = reactive({
  title: props.course?.title || '',
  description: props.course?.description || '',
  type: props.course?.type || 'video',
  // dùng đa phân loại qua category_ids
  category_ids: props.course?.category_ids || [],
  image: props.course?.image || '',
  price: props.course?.price || 0,
  duration: props.course?.duration || 0,
  is_published: props.course?.is_published || false,
  teacher_ids: props.course?.teacher_ids || [] // Array of teacher IDs
})

// Computed for selected teachers display
const selectedTeachers = computed(() => {
  if (!form.teacher_ids || !Array.isArray(form.teacher_ids)) return []
  return props.teachers.filter(teacher => form.teacher_ids.includes(teacher.id))
})

// Computed for selected categories display
const selectedCategories = computed(() => {
  if (!form.category_ids || !Array.isArray(form.category_ids)) return []
  return (props.categories || []).filter(cat => form.category_ids.includes(cat.id))
})

// Học phần - sectionsList
const sections = ref([
  // Có thể thay rỗng [] nếu data server chưa có
  // {id: 1, title: 'Giới thiệu', description: 'Mở đầu khóa học', lessons: []},
])

function addSection() {
  sections.value.push({
    id: Date.now() + Math.floor(Math.random() * 10000),
    title: '',
    description: '',
    lessons: []
  })
}

async function removeSection(idx) {
  const section = sections.value[idx]
  if (!section) return
  if (!confirm('Bạn có chắc muốn xóa học phần này?')) return
  if (section.serverId || section.id) {
    const id = section.serverId || section.id
    const resp = await fetch(`/admin/sections/${id}`, {
      method: 'DELETE',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '') },
      credentials: 'same-origin'
    })
    if (!resp.ok) {
      alert('Xóa học phần thất bại. Mã lỗi: ' + resp.status)
      return
    }
    await loadSections()
    showSuccess('Đã xóa học phần')
  } else {
    sections.value.splice(idx, 1)
  }
}

// Lessons
function addLesson(sectionIdx) {
  const section = sections.value[sectionIdx]
  if (!section.lessons) section.lessons = []
  section.lessons.push({
    id: null,
    title: '',
    description: '',
    videoFile: null,
    videoName: '',
    attachmentFile: null,
    attachmentName: ''
  })
}

async function removeLesson(sectionIdx, lessonIdx) {
  const section = sections.value[sectionIdx]
  if (!section?.lessons) return
  const lesson = section.lessons[lessonIdx]
  if (!lesson) return
  if (!confirm('Bạn có chắc muốn xóa bài học này?')) return
  if (lesson.id) {
    const resp = await fetch(`/admin/lessons/${lesson.id}`, {
      method: 'DELETE',
      headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '') },
      credentials: 'same-origin'
    })
    if (!resp.ok) {
      alert('Xóa bài học thất bại. Mã lỗi: ' + resp.status)
      return
    }
    await loadSections()
    showSuccess('Đã xóa bài học')
  } else {
    section.lessons.splice(lessonIdx, 1)
  }
}

function onLessonAttachmentChange(sectionIdx, lessonIdx, event) {
  const file = event?.target?.files?.[0]
  if (!file) return
  const lesson = sections.value[sectionIdx]?.lessons?.[lessonIdx]
  if (!lesson) return
  lesson.attachmentFile = file
  lesson.attachmentName = file.name
}

function onLessonVideoFileChange(sectionIdx, lessonIdx, event) {
  const file = event?.target?.files?.[0]
  if (!file) return
  const lesson = sections.value[sectionIdx]?.lessons?.[lessonIdx]
  if (!lesson) return
  lesson.videoFile = file
  lesson.videoName = file.name
}

function clearLessonVideo(sectionIdx, lessonIdx) {
  const lesson = sections.value[sectionIdx]?.lessons?.[lessonIdx]
  if (!lesson) return
  lesson.videoFile = null
  lesson.videoName = ''
}

function clearLessonAttachment(sectionIdx, lessonIdx) {
  const lesson = sections.value[sectionIdx]?.lessons?.[lessonIdx]
  if (!lesson) return
  lesson.attachmentFile = null
  lesson.attachmentName = ''
}

// Save/Reset section (client-side placeholder)
function saveSection(sectionIdx) {
  const section = sections.value[sectionIdx]
  if (!section) return
  // Basic validation
  if (!section.title || section.title.trim().length === 0) {
    alert(`Vui lòng nhập tiêu đề cho học phần #${sectionIdx + 1}`)
    return
  }
  // Create or update section, then upsert lessons
  const payload = {
    title: section.title,
    order: section.order ?? sectionIdx + 1
  }
  const isUpdate = !!section.serverId
  const sectionUrl = isUpdate
    ? `/admin/sections/${section.serverId}/json`
    : `/admin/courses/${props.course.id}/sections-json`
  const sectionMethod = isUpdate ? 'PUT' : 'POST'
  fetch(sectionUrl, {
    method: sectionMethod,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '')
    },
    credentials: 'same-origin',
    body: JSON.stringify(payload)
  }).then(async r => {
    if (!r.ok) {
      if (r.status === 422) {
        const err = await r.json().catch(() => ({}))
        console.error('Validation error (section)', err)
        showSuccess('Không thể lưu học phần: thiếu tiêu đề hoặc dữ liệu không hợp lệ')
        throw new Error('422')
      }
    }
    return r.json()
  }).then(async (data) => {
    const sectionId = data?.section?.id
    if (!sectionId) return
    section.serverId = sectionId
    // Save or update lessons sequentially
    for (let lIdx = 0; lIdx < (section.lessons?.length || 0); lIdx++) {
      const lesson = section.lessons[lIdx]
      const fd = new FormData()
      const safeTitle = (lesson.title && lesson.title.trim().length > 0) ? lesson.title : `Bài ${lIdx + 1}`
      fd.append('title', safeTitle)
      if (lesson.description) fd.append('description', lesson.description)
      if (lesson.videoFile) fd.append('video_file', lesson.videoFile)
      if (lesson.attachmentFile) fd.append('attachment', lesson.attachmentFile)
      if (typeof lesson.order !== 'undefined') fd.append('order', String(lesson.order))
      if (typeof lesson.videoDuration !== 'undefined') fd.append('video_duration', String(lesson.videoDuration))
      const lessonIdNum = Number(lesson.id)
      const isLessonUpdate = Number.isFinite(lessonIdNum) && lessonIdNum > 0
      const lessonUrl = isLessonUpdate ? `/admin/lessons/${lessonIdNum}/json` : `/admin/sections/${sectionId}/lessons-json`
      const lessonMethod = isLessonUpdate ? 'PUT' : 'POST'
      const resp = await fetch(lessonUrl, {
        method: lessonMethod,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '') },
        credentials: 'same-origin',
        body: fd
      })
      if (!resp.ok) {
        if (resp.status === 422) {
          const err = await resp.json().catch(() => ({}))
          console.error('Validation error (lesson)', err)
          alert('Không thể lưu bài học: thiếu tiêu đề hoặc tệp không hợp lệ/kích thước quá lớn.')
        } else {
          alert('Không thể lưu bài học. Mã lỗi: ' + resp.status)
        }
        return
      }
    }
    // Reload sections from DB and re-bind
    await loadSections()
    showSuccess('Đã lưu học phần và bài học')
  }).catch(() => {
    alert('Không thể lưu học phần. Vui lòng thử lại.')
  })
}

function resetSection(sectionIdx) {
  const section = sections.value[sectionIdx]
  if (!section) return
  section.title = ''
  section.description = ''
  section.lessons = []
}

function addQuiz(sectionIdx) {
  const section = sections.value[sectionIdx]
  if (!section?.serverId) {
    showSuccess('Vui lòng lưu học phần trước khi tạo bài kiểm tra')
    return
  }
  router.visit(`/admin/courses/${props.course.id}/sections/${section.serverId}/tests/create`)
}

// Load sections from server and bind to local state
async function loadSections() {
  try {
    const res = await fetch(`/admin/courses/${props.course.id}/sections-json`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    const data = await res.json()
    const mapped = (data.sections || []).map(s => ({
      id: s.id,
      serverId: s.id,
      title: s.title,
      description: s.description || '',
      order: s.order,
      lessons: (s.lessons || []).map(l => ({
        id: l.id,
        title: l.title,
        description: l.description || '',
        videoFile: null,
        videoName: l.video_url ? l.video_url.split('/').pop() : '',
        attachmentFile: null,
        attachmentName: l.attachment ? l.attachment.split('/').pop() : '',
        order: l.order,
        videoDuration: l.video_duration || 0
      }))
    }))
    sections.value = mapped
  } catch (e) {
    console.error('Failed to load sections', e)
  }
}

// Methods for teacher selection
const toggleTeacher = (teacherId) => {
  if (!form.teacher_ids) form.teacher_ids = []
  
  const index = form.teacher_ids.indexOf(teacherId)
  if (index > -1) {
    form.teacher_ids.splice(index, 1)
  } else {
    form.teacher_ids.push(teacherId)
  }
}

const removeTeacher = (teacherId) => {
  if (!form.teacher_ids) return
  const index = form.teacher_ids.indexOf(teacherId)
  if (index > -1) {
    form.teacher_ids.splice(index, 1)
  }
}

// Categories handlers
const toggleCategory = (categoryId) => {
  if (!form.category_ids) form.category_ids = []
  const index = form.category_ids.indexOf(categoryId)
  if (index > -1) {
    form.category_ids.splice(index, 1)
  } else {
    form.category_ids.push(categoryId)
  }
}

const removeCategory = (categoryId) => {
  if (!form.category_ids) return
  const index = form.category_ids.indexOf(categoryId)
  if (index > -1) {
    form.category_ids.splice(index, 1)
  }
}

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

const handleImageError = (event) => {
  console.error('Image load error:', event.target.src)
  
  // Try to use a placeholder instead
  if (imagePreview.value && !imagePreview.value.startsWith('data:')) {
    // If it's a URL that failed, try to create a placeholder
    const svg = `<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
      <rect width="150" height="150" fill="#9CA3AF"/>
      <text x="75" y="80" text-anchor="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-size="14" font-weight="bold">Image not found</text>
    </svg>`
    imagePreview.value = 'data:image/svg+xml;base64,' + btoa(svg)
  } else {
    // Clear if we can't use placeholder
    imagePreview.value = ''
    form.image = ''
  }
}

const handleImageLoad = (event) => {
  console.log('Image loaded successfully:', event.target.src)
}

const updateCourse = async () => {
  isSubmitting.value = true
  errors.value = {}

  // Validate price range
  if (form.price < 0 || form.price > 50000000) {
    errors.value = {
      price: 'Giá phải nằm trong khoảng từ 0 đến 50,000,000 VNĐ'
    }
    isSubmitting.value = false
    return
  }

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
    // Get teacher_ids from course (now using course_user table)
    const teacherIds = props.course.teacher_ids || []
    
    Object.assign(form, {
      title: props.course.title || '',
      description: props.course.description || '',
      type: props.course.type || 'video',
      category_ids: props.course.category_ids || [],
      image: props.course.image || '',
      price: props.course.price || 0,
      duration: props.course.duration || 0,
      is_published: props.course.is_published || false,
      teacher_ids: teacherIds
    })
    
    // Set image preview if course has image
    if (props.course.image) {
      imagePreview.value = props.course.image
    }
  }
  
  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.relative')) {
      showTeacherDropdown.value = false
      showCategoryDropdown.value = false
    }
  })

  // Load sections from server initially
  loadSections()
})
</script>
