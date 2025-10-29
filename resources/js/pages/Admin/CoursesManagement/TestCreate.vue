<template>
  <AdminLayout :title="`Tạo bài kiểm tra - ${course.title}`" :page-title="'Tạo bài kiểm tra'">
    <div class="space-y-6">
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold">
          Đang tạo bài kiểm tra cho {{ course.title }} - {{ section.title }}.
        </h2>
        <p class="text-sm text-gray-500 mt-1">Danh sách câu hỏi</p>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Danh sách câu hỏi</CardTitle>
          <CardDescription>Thêm câu hỏi, đáp án và nội dung đa phương tiện.</CardDescription>
        </CardHeader>
        <CardContent>
          <div v-if="questions.length === 0" class="text-sm text-gray-500">Chưa có câu hỏi nào.</div>
          <div v-for="(q, idx) in questions" :key="q.id" class="mb-4 border rounded-xl p-4">
            <div class="flex items-center justify-between">
              <div class="font-medium">Câu {{ idx + 1 }}</div>
              <button type="button" class="text-red-600" @click="removeQuestion(idx)">Xóa</button>
            </div>
            <div class="mt-2">
              <Label>Nội dung câu hỏi</Label>
              <Input v-model="q.title" placeholder="Nhập nội dung câu hỏi" />
            </div>
          </div>

          <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" @click="addQuestion">
            + Thêm câu hỏi
          </button>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { reactive } from 'vue'

const props = defineProps({
  course: Object,
  section: Object,
  questions: { type: Array, default: () => [] }
})

const course = props.course
const section = props.section
const questions = reactive(props.questions?.map(q => ({ id: q.id, title: q.title })) || [])

function addQuestion () {
  questions.push({ id: Date.now(), title: '' })
}

function removeQuestion (idx) {
  questions.splice(idx, 1)
}
</script>


