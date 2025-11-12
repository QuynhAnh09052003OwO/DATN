<template>
  <PublicLayout :title="course.title">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Player/Content -->
        <div class="lg:col-span-2 space-y-4">
          <div class="bg-white rounded-lg shadow p-4">
            <h1 class="text-xl font-bold text-gray-900">{{ activeLesson?.title || 'Bài học' }}</h1>
          </div>

          <!-- Video area -->
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="aspect-video bg-black flex items-center justify-center">
              <video
                v-if="activeLesson && activeLesson.video_url"
                :src="activeLesson.video_url"
                controls
                class="w-full h-full"
              />
              <div v-else class="w-full h-full flex items-center justify-center text-white text-sm">
                Video không khả dụng
              </div>
            </div>
            <div class="p-4">
              <p class="text-sm text-gray-600" v-if="activeLesson?.description">{{ activeLesson.description }}</p>
            </div>
          </div>

          <!-- PDF viewer -->
          <div class="bg-white rounded-lg shadow p-4" v-if="activeLesson?.attachment">
            <h2 class="text-lg font-semibold mb-2">Tài liệu</h2>
            <div class="w-full h-96 border rounded">
              <iframe :src="activeLesson.attachment" class="w-full h-full"></iframe>
            </div>
          </div>

          <!-- Comments (UI placeholder) -->
          <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold mb-2">Bình luận</h2>
            <div class="space-y-3">
              <textarea v-model="newComment" rows="3" class="w-full border rounded-lg p-2" placeholder="Nhập bình luận..."></textarea>
              <div class="flex justify-end">
                <button class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700" @click="submitComment" :disabled="!newComment.trim()">Gửi</button>
              </div>
              <div v-if="comments.length === 0" class="text-sm text-gray-500">Chưa có bình luận.</div>
              <div v-else class="space-y-2">
                <div v-for="(c, idx) in comments" :key="idx" class="border rounded p-2">
                  <div class="text-sm text-gray-800">{{ c.text }}</div>
                  <div class="text-xs text-gray-400">{{ c.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Curriculum (reuse CourseShow structure) -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow p-6 sticky top-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-semibold">Lộ trình học</h2>
              <div class="text-sm text-gray-500">{{ course.sections?.length || 0 }} học phần</div>
            </div>

            <div v-if="course.sections && course.sections.length" class="space-y-1 max-h-[calc(100vh-200px)] overflow-y-auto">
              <Collapsible
                v-for="(s, sIdx) in course.sections"
                :key="s.id"
                v-model:open="openSections[sIdx]"
                class="border-b border-gray-200 last:border-b-0"
                @update:open="(isOpen) => { if (isOpen) selectSection(s) }"
              >
                <CollapsibleTrigger class="w-full flex items-center justify-between py-3 px-2 hover:bg-gray-50 transition-colors rounded-md">
                  <div class="flex items-center gap-2 flex-1 min-w-0">
                    <span :class="['font-medium truncate', openSections[sIdx] ? 'text-teal-600' : 'text-gray-900']">{{ s.title }}</span>
                  </div>
                  <ChevronDown :class="['w-5 h-5 transition-transform flex-shrink-0', openSections[sIdx] ? 'transform rotate-180 text-teal-600' : 'text-gray-500']" />
                </CollapsibleTrigger>
                <CollapsibleContent class="pb-4">
                  <div v-if="openSections[sIdx]" class="border-t-2 border-teal-500 pt-3">
                    <div v-if="s.lessons && s.lessons.length" class="space-y-1.5">
                      <button v-for="(lesson, lIdx) in s.lessons" :key="lesson.id" @click="playLesson(lesson)" :class="['w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200', activeLesson?.id === lesson.id ? 'bg-teal-50' : 'hover:bg-teal-50']">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" :class="lesson.video_url ? 'bg-teal-100' : 'bg-yellow-100'">
                            <Clock v-if="lesson.video_url" class="w-4 h-4 text-teal-600" />
                            <Book v-else class="w-4 h-4 text-yellow-600" />
                          </div>
                          <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                              <span class="text-xs text-gray-500 font-medium">Bài {{ lIdx + 1 }}:</span>
                              <span class="text-sm font-medium truncate">{{ lesson.title }}</span>
                            </div>
                          </div>
                        </div>
                      </button>
                    </div>

                    <div v-if="s.tests && s.tests.length" class="mt-3 pt-3 border-t border-gray-200 space-y-1.5">
                      <button v-for="(test, tIdx) in s.tests" :key="test.id" @click="openTest(test)" class="w-full flex items-center justify-between py-2.5 px-3 rounded-lg transition-all duration-200 hover:bg-red-50">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                          <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 bg-red-100">
                            <FileText class="w-4 h-4 text-red-600" />
                          </div>
                          <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                              <span class="text-xs text-red-600 font-medium">Kiểm tra {{ tIdx + 1 }}:</span>
                              <span class="text-sm font-medium truncate">{{ test.title }}</span>
                            </div>
                          </div>
                        </div>
                      </button>
                    </div>
                  </div>
                </CollapsibleContent>
              </Collapsible>
            </div>
          </div>
        </div>
      </div>
    </div>
  </PublicLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible'
import { ChevronDown, Clock, Book, FileText } from 'lucide-vue-next'

const props = defineProps({ course: Object })
const course = computed(() => props.course || {})
const openSections = reactive({})
const activeLesson = ref(null)
const comments = ref([])
const newComment = ref('')

onMounted(() => {
  // select first lesson of first section
  const s = course.value?.sections?.[0]
  const firstLesson = s?.lessons?.[0] || null
  activeLesson.value = firstLesson
  if (s) openSections[0] = true
})

const playLesson = (lesson) => { activeLesson.value = lesson }
const selectSection = (_s) => {}
const openTest = (test) => { /* future: navigate to test page */ }

const submitComment = () => {
  if (!newComment.value.trim()) return
  comments.value.unshift({ text: newComment.value.trim(), time: new Date().toLocaleString() })
  newComment.value = ''
}
</script>


