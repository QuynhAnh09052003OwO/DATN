<template>
  <AdminLayout :title="`Tạo bài kiểm tra - ${course.title}`" :page-title="'Tạo bài kiểm tra'">
    <div class="space-y-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold">
          Đang tạo bài kiểm tra cho {{ course.title }} - {{ section.title }}.
        </h2>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Danh sách câu hỏi</CardTitle>
          <CardDescription>Thêm câu hỏi, đáp án và nội dung đa phương tiện.</CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="questions.length === 0" class="text-sm text-gray-500">Chưa có câu hỏi nào.</div>
          <div v-for="(q, idx) in questions" :key="q.id" class="mb-6 border rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div class="font-medium">Câu {{ idx + 1 }}</div>
              <button type="button" class="text-red-600" @click="removeQuestion(idx)">Xóa</button>
            </div>
            <div class="mt-2">
              <Label>Nội dung câu hỏi</Label>
              <Input v-model="q.title" placeholder="Nhập nội dung câu hỏi" />
            </div>

            <!-- Media upload area -->
            <div class="mt-4">
              <div class="flex items-center justify-between mb-2">
                <Label>Thêm nội dung câu hỏi</Label>
              </div>
              <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                <div class="grid grid-cols-2 gap-6 max-w-md mx-auto">
                  <!-- Audio tile -->
                  <label class="cursor-pointer group">
                    <input type="file" class="hidden" accept="audio/*" @change="e => onAudioChange(idx, e)" />
                    <div class="flex flex-col items-center justify-center rounded-lg border border-gray-300 p-4 group-hover:border-gray-400">
                      <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6a2 2 0 114 0v13m-4 0a2 2 0 104 0" />
                      </svg>
                      <span class="mt-2 text-sm text-gray-700">Audio</span>
                    </div>
                  </label>

                  <!-- Picture tile -->
                  <label class="cursor-pointer group">
                    <input type="file" class="hidden" accept="image/*" @change="e => onImageChange(idx, e)" />
                    <div class="flex flex-col items-center justify-center rounded-lg border border-gray-300 p-4 group-hover:border-gray-400">
                      <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11l2 2 4-4m1 8H7a2 2 0 01-2-2V9" />
                      </svg>
                      <span class="mt-2 text-sm text-gray-700">Picture</span>
                    </div>
                  </label>
                </div>

                <!-- Selected info -->
                <div class="mt-3 text-sm text-gray-600 space-y-1">
                  <div v-if="q.imageName">Ảnh: <span class="font-medium">{{ q.imageName }}</span></div>
                  <div v-if="q.audioName">Audio: <span class="font-medium">{{ q.audioName }}</span></div>
                </div>
              </div>
            </div>

            <!-- Answers management -->
            <div class="mt-6">
              <Label>Đáp án</Label>
              <div class="mt-2 space-y-3">
                <div v-for="(a, aIdx) in q.answers" :key="a.id" class="flex items-center gap-3">
                  <!-- Radio to mark correct -->
                  <input type="radio" :name="`correct-${q.id}`" :checked="q.correctIndex === aIdx" @change="() => setCorrect(idx, aIdx)" />
                  <!-- Input text answer -->
                  <Input v-model="a.text" placeholder="Nhập đáp án {{ aIdx + 1 }}" class="flex-1" />

                  <button type="button" class="text-red-600" @click="removeAnswer(idx, aIdx)">Xóa</button>
                </div>
                <!-- Dropdown chọn đáp án đúng từ danh sách đã nhập -->
                <div class="flex items-center gap-3 pt-2">
                  <span class="text-sm text-gray-600">Đáp án đúng</span>
                  <select v-model.number="q.correctIndex" class="px-3 py-2 border rounded-lg text-sm">
                    <option v-for="(a, aIdx) in q.answers" :key="a.id" :value="aIdx">
                      {{ a.text && a.text.trim().length ? a.text : `Đáp án ${aIdx + 1}` }}
                    </option>
                  </select>
                </div>
                <button type="button" class="text-blue-600" @click="addAnswer(idx)">+ Thêm đáp án</button>
              </div>
            </div>
          </div>

          <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" @click="addQuestion">
            + Thêm câu hỏi
          </button>
        </CardContent>
      </Card>

      <!-- Actions -->
      <div class="flex justify-end gap-3">
        <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50" @click="cancelCreate">
          Hủy
        </button>
        <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" @click="saveTest">
          Lưu bài kiểm tra
        </button>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  course: Object,
  section: Object,
  questions: { type: Array, default: () => [] }
})

const course = props.course
const section = props.section
const questions = reactive((props.questions || []).map(q => ({
  id: q.id,
  title: q.title,
  imageFile: null,
  imageName: '',
  audioFile: null,
  audioName: '',
  answers: [{ id: Date.now(), text: '', type: 'text' }],
  correctIndex: 0,
})) || [])

function addQuestion () {
  questions.push({
    id: Date.now(),
    title: '',
    imageFile: null,
    imageName: '',
    audioFile: null,
    audioName: '',
    answers: [{ id: Date.now() + 1, text: '', type: 'text' }],
    correctIndex: 0,
  })
}

function removeQuestion (idx) {
  questions.splice(idx, 1)
}

function onImageChange(qIdx, e) {
  const file = e?.target?.files?.[0]
  if (!file) return
  questions[qIdx].imageFile = file
  questions[qIdx].imageName = file.name
}

function onAudioChange(qIdx, e) {
  const file = e?.target?.files?.[0]
  if (!file) return
  questions[qIdx].audioFile = file
  questions[qIdx].audioName = file.name
}

function addAnswer(qIdx) {
  questions[qIdx].answers.push({ id: Date.now(), text: '', type: 'text' })
}

function removeAnswer(qIdx, aIdx) {
  questions[qIdx].answers.splice(aIdx, 1)
  if (questions[qIdx].correctIndex >= questions[qIdx].answers.length) {
    questions[qIdx].correctIndex = Math.max(0, questions[qIdx].answers.length - 1)
  }
}

function setCorrect(qIdx, aIdx) {
  questions[qIdx].correctIndex = aIdx
}

function cancelCreate() {
  router.visit(`/admin/courses/${course.id}/edit`)
}

async function saveTest() {
  if (!questions.length) return
  const fd = new FormData()
  // Simple default title; in future, add a real input
  fd.append('title', `Bài kiểm tra - ${section.title}`)
  // Serialize questions (without files)
  const plainQuestions = questions.map((q, idx) => ({
    title: q.title,
    answers: q.answers,
    correctIndex: q.correctIndex,
    order: idx + 1,
    points: 1,
  }))
  fd.append('questions', JSON.stringify(plainQuestions))
  // Attach files per question
  questions.forEach((q, idx) => {
    if (q.imageFile) fd.append(`image_${idx}`, q.imageFile)
    if (q.audioFile) fd.append(`audio_${idx}`, q.audioFile)
  })

  const resp = await fetch(`/admin/sections/${section.id}/tests-json`, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '') },
    credentials: 'same-origin',
    body: fd
  })
  if (!resp.ok) {
    console.error('Save test failed', resp.status)
    return
  }
  const data = await resp.json()
  router.visit(`/admin/courses/${course.id}/edit`)
}
</script>


