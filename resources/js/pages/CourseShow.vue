<template>
  <PublicLayout :title="course.title">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Left: Course Info -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-start gap-4">
            <img v-if="course.image" :src="course.image" :alt="course.title" class="w-40 h-28 object-cover rounded" />
            <div class="flex-1">
              <h1 class="text-2xl font-bold text-gray-900">{{ course.title }}</h1>
              <div class="mt-2 flex flex-wrap items-center gap-2">
                <span :class="['px-2 py-1 text-xs rounded-full', course.type === 'video' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800']">{{ course.type }}</span>
                <span class="text-sm text-gray-600">Thời lượng: {{ formatDuration(course.duration) }}</span>
              </div>
              <div class="mt-3 text-xl font-semibold text-green-600">{{ formatPrice(course.price) }}</div>
            </div>
          </div>
          <div class="mt-4 prose max-w-none text-gray-800">
            <p v-if="course.description">{{ course.description }}</p>
            <p v-else class="text-gray-500">Chưa có mô tả cho khóa học.</p>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-3">Giảng viên</h2>
          <div v-if="course.teachers && course.teachers.length" class="space-y-2">
            <div v-for="t in course.teachers" :key="t.id" class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold">{{ initials(t.name) }}</div>
              <div>
                <div class="font-medium text-gray-900">{{ t.name }}</div>
                <div class="text-sm text-gray-500">{{ t.email }}</div>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500">Chưa có giảng viên.</div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-3">Phân loại</h2>
          <div v-if="course.categories && course.categories.length" class="flex flex-wrap gap-2">
            <span v-for="c in course.categories" :key="c.id" class="px-3 py-1 bg-gray-100 rounded-full text-sm text-gray-700">{{ c.name }}</span>
          </div>
          <div v-else class="text-gray-500">Chưa có phân loại.</div>
        </div>

        <!-- Sections Description List -->
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-lg font-semibold mb-4">Mô tả học phần</h2>
          <div v-if="course.sections && course.sections.length" class="space-y-4">
            <div
              v-for="(section, sIdx) in course.sections"
              :key="section.id"
              class="border-l-4 pl-4 pb-4 transition-colors"
              :class="selectedSection?.id === section.id ? 'border-teal-500 bg-teal-50/30 rounded-r-lg' : 'border-gray-200'"
              @click="selectSection(section)"
            >
              <div class="flex items-start justify-between mb-2">
                <h3 class="font-semibold text-gray-900 cursor-pointer hover:text-teal-600 transition-colors">
                  Học phần {{ sIdx + 1 }}: {{ section.title }}
                </h3>
                <div v-if="getSectionStats(section).total > 0" class="text-xs text-gray-500 whitespace-nowrap ml-2">
                  {{ getSectionStats(section).total }} bài
                </div>
              </div>
              <div v-if="section.description" class="mt-2 text-sm text-gray-700 leading-relaxed">
                {{ section.description }}
              </div>
              <div v-else class="mt-2 text-sm text-gray-400 italic">
                Chưa có mô tả cho học phần này.
              </div>
              <div v-if="getSectionStats(section).total > 0" class="mt-3 flex items-center gap-4 text-xs text-gray-500">
                <div class="flex items-center gap-1">
                  <Book class="w-3.5 h-3.5 text-teal-600" />
                  <span>{{ getSectionStats(section).lessons }} bài học</span>
                </div>
                <div v-if="getSectionStats(section).tests > 0" class="flex items-center gap-1">
                  <FileText class="w-3.5 h-3.5 text-red-600" />
                  <span>{{ getSectionStats(section).tests }} bài kiểm tra</span>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-500 text-sm">
            Chưa có học phần nào trong khóa học này.
          </div>
        </div>
      </div>

      <!-- Right: Curriculum -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Enrollment Card -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <div class="text-center mb-4">
            <div class="text-3xl font-bold text-teal-600 mb-2">{{ formatPrice(course.price) }}</div>
            <p v-if="course.price === 0" class="text-sm text-gray-600">Miễn phí</p>
          </div>
          
          <button
            v-if="!isEnrolled"
            @click="enrollCourse"
            :disabled="isEnrolling"
            class="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors shadow-md hover:shadow-lg disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <span v-if="isEnrolling" class="flex items-center gap-2">
              <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Đang xử lý...
            </span>
            <span v-else>Tham gia khóa học</span>
          </button>
          
          <div v-else class="w-full bg-green-100 border-2 border-green-300 text-green-800 font-semibold py-3 px-6 rounded-lg text-center">
            <div class="flex items-center justify-center gap-2">
              <Check class="w-5 h-5" />
              <span>Đã tham gia</span>
            </div>
          </div>
          
          <div class="mt-4 pt-4 border-t border-gray-200 space-y-2 text-sm text-gray-600">
            <div class="flex items-center justify-between">
              <span>Thời lượng:</span>
              <span class="font-medium">{{ formatDuration(course.duration) }}</span>
            </div>
            <div v-if="course.sections" class="flex items-center justify-between">
              <span>Học phần:</span>
              <span class="font-medium">{{ course.sections.length }}</span>
            </div>
            <div v-if="getTotalLessons() > 0" class="flex items-center justify-between">
              <span>Bài học:</span>
              <span class="font-medium">{{ getTotalLessons() }}</span>
            </div>
          </div>
        </div>

        <!-- Curriculum -->
        <div class="bg-white rounded-lg shadow p-6 sticky top-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Lộ trình học</h2>
            <div class="text-sm text-gray-500">
              {{ course.sections?.length || 0 }} học phần
            </div>
          </div>
          
          <div v-if="course.sections && course.sections.length" class="space-y-1 max-h-[calc(100vh-200px)] overflow-y-auto">
            <Collapsible
              v-for="(s, sIdx) in course.sections"
              :key="s.id"
              v-model:open="openSections[sIdx]"
              class="border-b border-gray-200 last:border-b-0"
              @update:open="(isOpen) => { if (isOpen) selectSection(s) }"
            >
              <CollapsibleTrigger 
                class="w-full flex items-center justify-between py-3 px-2 hover:bg-gray-50 transition-colors rounded-md"
              >
                <div class="flex items-center gap-2 flex-1 min-w-0">
                  <span :class="['font-medium truncate', openSections[sIdx] ? 'text-teal-600' : 'text-gray-900']">
                    {{ s.title }}
                  </span>
                  <span v-if="getSectionStats(s).total > 0" class="text-xs text-gray-500 whitespace-nowrap">
                    ({{ getSectionStats(s).total }})
                  </span>
                </div>
                <ChevronDown 
                  :class="['w-5 h-5 transition-transform flex-shrink-0', openSections[sIdx] ? 'transform rotate-180 text-teal-600' : 'text-gray-500']"
                />
              </CollapsibleTrigger>
              
              <CollapsibleContent class="pb-4">
                <div 
                  v-if="openSections[sIdx]" 
                  class="border-t-2 border-teal-500 pt-3 animate-fade-in"
                >
                  <!-- Lessons List -->
                  <div v-if="s.lessons && s.lessons.length" class="space-y-1.5">
                    <button
                      v-for="(lesson, lIdx) in s.lessons"
                      :key="lesson.id"
                      @click="handleItemClick({ ...lesson, type: lesson.video_url ? 'video' : (lesson.attachment ? 'document' : 'video'), is_locked: lesson.is_locked || false, completed: lesson.completed || false })"
                      :disabled="lesson.is_locked"
                      :class="[
                        'w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200',
                        lesson.is_locked 
                          ? 'opacity-60 cursor-not-allowed bg-gray-50' 
                          : 'hover:bg-teal-50 cursor-pointer hover:shadow-sm',
                        lesson.completed ? 'bg-teal-50/50' : ''
                      ]"
                    >
                      <div class="flex items-center gap-3 flex-1 min-w-0">
                        <!-- Icon based on type -->
                        <div :class="[
                          'w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0',
                          lesson.video_url ? 'bg-teal-100' : 'bg-yellow-100'
                        ]">
                          <Clock v-if="lesson.video_url" class="w-4 h-4 text-teal-600" />
                          <Book v-else class="w-4 h-4 text-yellow-600" />
                        </div>
                        <div class="flex-1 min-w-0">
                          <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 font-medium">Bài {{ lIdx + 1 }}:</span>
                            <span :class="['text-sm font-medium truncate', lesson.completed ? 'text-teal-700' : 'text-gray-900']">
                              {{ lesson.title }}
                            </span>
                            <Lock v-if="lesson.is_locked" class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" />
                          </div>
                          <div class="flex items-center gap-2 mt-0.5">
                            <span v-if="lesson.video_duration" class="text-xs text-gray-500">
                              {{ formatVideoDuration(lesson.video_duration) }}
                            </span>
                            <span v-if="lesson.description" class="text-xs text-gray-400 truncate max-w-[150px]">
                              • {{ lesson.description }}
                            </span>
                          </div>
                        </div>
                      </div>
                      <!-- Completion Status -->
                      <div class="flex items-center gap-2 flex-shrink-0">
                        <div :class="[
                          'w-6 h-6 rounded-full flex items-center justify-center transition-all',
                          lesson.completed ? 'bg-teal-600 shadow-sm' : lesson.is_locked ? 'bg-gray-300' : 'bg-gray-200'
                        ]">
                          <Check v-if="lesson.completed" class="w-3.5 h-3.5 text-white" />
                          <Lock v-else-if="lesson.is_locked" class="w-3.5 h-3.5 text-gray-500" />
                        </div>
                      </div>
                    </button>
                  </div>

                  <!-- Tests List -->
                  <div v-if="s.tests && s.tests.length" class="mt-3 pt-3 border-t border-gray-200 space-y-1.5">
                    <button
                      v-for="(test, tIdx) in s.tests"
                      :key="test.id"
                      @click="handleItemClick({ ...test, type: 'test', is_locked: test.is_locked || false, completed: test.completed || false })"
                      :disabled="test.is_locked"
                      :class="[
                        'w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200',
                        test.is_locked 
                          ? 'opacity-60 cursor-not-allowed bg-gray-50' 
                          : 'hover:bg-red-50 cursor-pointer hover:shadow-sm',
                        test.completed ? 'bg-red-50/50' : ''
                      ]"
                    >
                      <div class="flex items-center gap-3 flex-1 min-w-0">
                        <!-- Test Icon -->
                        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 bg-red-100">
                          <FileText class="w-4 h-4 text-red-600" />
                        </div>
                        <div class="flex-1 min-w-0">
                          <div class="flex items-center gap-2">
                            <span class="text-xs text-red-600 font-medium">Kiểm tra {{ tIdx + 1 }}:</span>
                            <span :class="['text-sm font-medium truncate', test.completed ? 'text-red-700' : 'text-gray-900']">
                              {{ test.title }}
                            </span>
                            <Lock v-if="test.is_locked" class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" />
                          </div>
                          <div class="flex items-center gap-2 mt-0.5">
                            <span v-if="test.time_limit" class="text-xs text-gray-500">
                              {{ test.time_limit }} phút
                            </span>
                          </div>
                        </div>
                      </div>
                      <!-- Completion Status -->
                      <div class="flex items-center gap-2 flex-shrink-0">
                        <div :class="[
                          'w-6 h-6 rounded-full flex items-center justify-center transition-all',
                          test.completed ? 'bg-red-600 shadow-sm' : test.is_locked ? 'bg-gray-300' : 'bg-gray-200'
                        ]">
                          <Check v-if="test.completed" class="w-3.5 h-3.5 text-white" />
                          <Lock v-else-if="test.is_locked" class="w-3.5 h-3.5 text-gray-500" />
                        </div>
                      </div>
                    </button>
                  </div>

                  <!-- Empty State -->
                  <div v-if="(!s.lessons || s.lessons.length === 0) && (!s.tests || s.tests.length === 0)" class="text-gray-500 text-sm py-4 text-center">
                    Chưa có bài học trong học phần này.
                  </div>
                </div>
              </CollapsibleContent>
            </Collapsible>
          </div>
          <div v-else class="text-gray-500 py-4 text-center">Chưa có lộ trình cho khóa học.</div>
        </div>
      </div>
    </div>
    </div>
  </PublicLayout>
  
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { ChevronDown, Clock, Book, Check, FileText, Lock } from 'lucide-vue-next'

const props = defineProps({
  course: Object
})

const page = usePage()
const openSections = reactive({})
const isEnrolling = ref(false)
const selectedSection = ref(null)

// Initialize - open first section by default and select it
onMounted(() => {
  if (props.course?.sections?.length > 0) {
    openSections[0] = true
    selectedSection.value = props.course.sections[0]
  }

  // Show flash messages
  if (page.props.flash?.success) {
    toast.success(page.props.flash.success)
  }
  if (page.props.flash?.error) {
    toast.error(page.props.flash.error)
  }
  if (page.props.flash?.info) {
    toast.info(page.props.flash.info)
  }
})

const course = computed(() => props.course || {})
const isEnrolled = computed(() => props.course?.is_enrolled || false)
const user = computed(() => page.props.auth?.user)

// Select section to show description and open it in curriculum
const selectSection = (section) => {
  // Find section index
  const sectionIndex = course.value.sections?.findIndex(s => s.id === section.id)
  
  if (selectedSection.value?.id === section.id) {
    // Deselect if clicking the same section
    selectedSection.value = null
    if (sectionIndex !== undefined && sectionIndex >= 0) {
      openSections[sectionIndex] = false
    }
  } else {
    selectedSection.value = section
    // Open the corresponding section in curriculum
    if (sectionIndex !== undefined && sectionIndex >= 0) {
      openSections[sectionIndex] = true
    }
  }
}

// Group lessons by lesson number
const getLessonGroups = (section) => {
  if (!section.lessons || section.lessons.length === 0) {
    // If no lessons, check if there are tests
    if (section.tests && section.tests.length > 0) {
      return section.tests.map((test, idx) => ({
        lessonNum: idx + 1,
        items: [{
          ...test,
          type: 'test',
          completed: test.completed !== undefined ? test.completed : false
        }],
        progress: test.completed ? 100 : 0
      }))
    }
    return []
  }
  
  const groups = []
  const groupMap = new Map()
  
  section.lessons.forEach((lesson, idx) => {
    // Try to extract lesson number from title or use index
    const lessonMatch = lesson.title?.match(/bài\s*(\d+)/i)
    const lessonNum = lessonMatch ? parseInt(lessonMatch[1]) : (idx + 1)
    const groupKey = lessonNum
    
    if (!groupMap.has(groupKey)) {
      const group = {
        lessonNum,
        items: [],
        progress: 0
      }
      groupMap.set(groupKey, group)
      groups.push(group)
    }
    
    const group = groupMap.get(groupKey)
    group.items.push({
      ...lesson,
      type: lesson.video_url ? 'video' : (lesson.attachment ? 'document' : 'video'),
      completed: lesson.completed !== undefined ? lesson.completed : false,
      is_locked: lesson.is_locked !== undefined ? lesson.is_locked : false
    })
    
    // Calculate progress based on completed items
    const completedCount = group.items.filter(item => item.completed).length
    group.progress = group.items.length > 0 ? (completedCount / group.items.length * 100) : 0
  })
  
  // Also add tests to groups if they exist
  if (section.tests && section.tests.length > 0) {
    section.tests.forEach((test, idx) => {
      const testOrder = test.order || (idx + 1)
      const groupKey = testOrder
      
      if (!groupMap.has(groupKey)) {
        const group = {
          lessonNum: testOrder,
          items: [],
          progress: 0
        }
        groupMap.set(groupKey, group)
        groups.push(group)
      }
      
      const group = groupMap.get(groupKey)
      group.items.push({
        ...test,
        type: 'test',
        completed: test.completed !== undefined ? test.completed : false,
        is_locked: test.is_locked !== undefined ? test.is_locked : false
      })
      
      // Recalculate progress
      const completedCount = group.items.filter(item => item.completed).length
      group.progress = group.items.length > 0 ? (completedCount / group.items.length * 100) : 0
    })
  }
  
  return groups.sort((a, b) => a.lessonNum - b.lessonNum)
}

const getProgressColor = (progress) => {
  if (progress >= 100) return '#14b8a6' // teal-500
  if (progress >= 50) return '#06b6d4' // cyan-500
  return '#3b82f6' // blue-500
}

// Get section statistics
const getSectionStats = (section) => {
  const lessonCount = section.lessons?.length || 0
  const testCount = section.tests?.length || 0
  return {
    lessons: lessonCount,
    tests: testCount,
    total: lessonCount + testCount
  }
}

// Handle item click (lesson or test)
const handleItemClick = (item) => {
  if (item.is_locked) {
    return // Don't navigate if locked
  }
  
  if (item.type === 'test') {
    // Navigate to test page
    router.visit(`/courses/${course.value.id}/tests/${item.id}`)
  } else if (item.type === 'video' || item.type === 'document') {
    // Navigate to lesson page
    router.visit(`/courses/${course.value.id}/lessons/${item.id}`)
  }
}

// Format video duration
const formatVideoDuration = (minutes) => {
  if (!minutes) return ''
  if (minutes < 60) {
    return `${minutes} phút`
  }
  const hours = Math.floor(minutes / 60)
  const mins = minutes % 60
  return mins > 0 ? `${hours}g${mins}p` : `${hours} giờ`
}

// Get total lessons count
const getTotalLessons = () => {
  if (!course.value.sections) return 0
  return course.value.sections.reduce((total, section) => {
    return total + (section.lessons?.length || 0) + (section.tests?.length || 0)
  }, 0)
}

// Enroll course
const enrollCourse = async () => {
  // Check if user is authenticated
  if (!user.value) {
    toast.error('Vui lòng đăng nhập để tham gia khóa học')
    router.visit('/login/student', {
      data: { 
        redirect: `/courses/${course.value.id}` 
      }
    })
    return
  }

  // Check if already enrolled
  if (isEnrolled.value) {
    toast.info('Bạn đã tham gia khóa học này rồi')
    return
  }

  isEnrolling.value = true

  try {
    router.post(`/courses/${course.value.id}/enroll`, {}, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success('Bạn đã tham gia khóa học thành công!')
        isEnrolling.value = false
      },
      onError: (errors) => {
        const errorMessage = errors.message || 'Có lỗi xảy ra khi đăng ký khóa học'
        toast.error(errorMessage)
        isEnrolling.value = false
      }
    })
  } catch (error) {
    console.error('Error enrolling course:', error)
    toast.error('Có lỗi xảy ra khi đăng ký khóa học')
    isEnrolling.value = false
  }
}

const formatPrice = (price) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price || 0)
const formatDuration = (d) => (d == null ? '—' : `${Number(d).toFixed(1)} giờ`)
const initials = (name) => (name || '').split(' ').map(p => p[0]).join('').slice(0,2).toUpperCase()
</script>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

.animate-slide-down {
  animation: slideDown 0.3s ease-in-out;
}

/* Custom scrollbar for curriculum */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>


