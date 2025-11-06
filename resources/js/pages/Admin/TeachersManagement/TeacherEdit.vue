<template>
  <AdminLayout title="Chỉnh sửa giáo viên" page-title="Chỉnh sửa giáo viên">
    <div class="space-y-6">
      <Card>
        <CardHeader>
          <CardTitle>Thông tin giáo viên</CardTitle>
          <CardDescription>Cập nhật thông tin và phân công khóa học</CardDescription>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <Label>Họ tên</Label>
              <Input v-model="form.name" placeholder="Nhập họ tên" />
            </div>
            <div>
              <Label>Email</Label>
              <Input v-model="form.email" />
            </div>
            <div>
              <Label>Điện thoại</Label>
              <Input v-model="form.phone" placeholder="Số điện thoại" />
            </div>
            <div>
              <Label>Giới tính</Label>
              <select v-model="form.gender" class="w-full px-3 py-2 border rounded-lg">
                <option :value="null">Chưa chọn</option>
                <option value="male">Nam</option>
                <option value="female">Nữ</option>
                <option value="other">Khác</option>
              </select>
            </div>

            <!-- Phân công khóa học -->
            <div class="md:col-span-2">
              <Label>Khóa học phụ trách</Label>
              <div class="relative">
                <button @click="toggleCourses" type="button" class="w-full px-3 py-2 border rounded-lg bg-white text-left flex items-center justify-between">
                  <span class="text-gray-700">{{ selectedCourses.length ? `${selectedCourses.length} khóa học đã chọn` : 'Chọn khóa học' }}</span>
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div v-if="showCourses" class="absolute z-10 w-full mt-1 bg-white border rounded-lg shadow max-h-60 overflow-y-auto">
                  <div class="p-2">
                    <div v-if="!courses || !courses.length" class="px-3 py-2 text-sm text-gray-500">Không có dữ liệu</div>
                    <div v-for="c in courses" :key="c.id" class="flex items-center px-3 py-2 hover:bg-gray-50 rounded">
                      <input :id="`course-${c.id}`" type="checkbox" :checked="form.course_ids.includes(c.id)" @change="toggleCourse(c.id)" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
                      <label :for="`course-${c.id}`" class="ml-2 text-sm text-gray-700 cursor-pointer flex-1">{{ c.title }}</label>
                    </div>
                  </div>
                </div>
              </div>
              <div v-if="selectedCourses.length" class="mt-2 flex flex-wrap gap-2">
                <span v-for="c in selectedCourses" :key="c.id" class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                  {{ c.title }}
                  <button type="button" class="ml-2 text-blue-600" @click="removeCourse(c.id)">×</button>
                </span>
              </div>
            </div>
          </div>

          <div class="mt-6 flex justify-end gap-3">
            <Button variant="outline" @click="goBack">Hủy</Button>
            <Button @click="save">Lưu</Button>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle>Khóa học đã giao</CardTitle>
          <CardDescription>Danh sách rút gọn các khóa học hiện tại</CardDescription>
        </CardHeader>
        <CardContent>
          <ul class="list-disc pl-6 text-sm text-gray-700">
            <li v-for="c in selectedCourses" :key="c.id">{{ c.title }}</li>
            <li v-if="!selectedCourses.length" class="text-gray-500">Chưa có khóa học nào</li>
          </ul>
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
import { Button } from '@/components/ui/button'
import { router } from '@inertiajs/vue3'
import { computed, reactive, ref } from 'vue'

const props = defineProps({
  teacher: Object,
  courses: Array,
})

const form = reactive({
  name: props.teacher?.name || '',
  email: props.teacher?.email || '',
  phone: props.teacher?.phone || '',
  gender: props.teacher?.gender || null,
  course_ids: Array.isArray(props.teacher?.course_ids) ? [...props.teacher.course_ids] : [],
})

const showCourses = ref(false)
const toggleCourses = () => { showCourses.value = !showCourses.value }

const selectedCourses = computed(() => {
  if (!form.course_ids) return []
  return (props.courses || []).filter(c => form.course_ids.includes(c.id))
})

const toggleCourse = (id) => {
  const idx = form.course_ids.indexOf(id)
  if (idx > -1) form.course_ids.splice(idx, 1)
  else form.course_ids.push(id)
}

const removeCourse = (id) => {
  const idx = form.course_ids.indexOf(id)
  if (idx > -1) form.course_ids.splice(idx, 1)
}

const goBack = () => router.visit('/admin/teachers')

const save = () => {
  router.put(`/admin/teachers/${props.teacher.id}`, form)
}
</script>


