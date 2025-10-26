<template>
  <AdminLayout 
    title="Tạo tài khoản giáo viên - Admin" 
    page-title="Tạo tài khoản giáo viên"
    :breadcrumbs="breadcrumbs"
  >
    <div class="max-w-4xl mx-auto">
      <Card>
        <CardHeader>
          <CardTitle>Tạo tài khoản giáo viên mới</CardTitle>
          <CardDescription>
            Thêm giáo viên mới vào hệ thống
          </CardDescription>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="createTeacher" class="space-y-6">
            <!-- Name -->
            <div class="space-y-2">
              <Label for="name">
                Họ và tên <span class="text-destructive">*</span>
              </Label>
              <Input
                id="name"
                v-model="form.name"
                type="text"
                required
                placeholder="Nhập họ và tên"
              />
              <div v-if="errors.name" class="text-sm text-destructive">{{ errors.name }}</div>
            </div>

            <!-- Email -->
            <div class="space-y-2">
              <Label for="email">
                Email <span class="text-destructive">*</span>
              </Label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                required
                placeholder="email@example.com"
              />
              <div v-if="errors.email" class="text-sm text-destructive">{{ errors.email }}</div>
            </div>

            <!-- Phone -->
            <div class="space-y-2">
              <Label for="phone">Số điện thoại</Label>
              <Input
                id="phone"
                v-model="form.phone"
                type="tel"
                placeholder="0123456789"
              />
              <div v-if="errors.phone" class="text-sm text-destructive">{{ errors.phone }}</div>
            </div>

            <!-- Gender -->
            <div class="space-y-2">
              <Label for="gender">Giới tính</Label>
              <Select v-model="form.gender">
                <SelectTrigger id="gender">
                  <SelectValue placeholder="Chọn giới tính" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="male">Nam</SelectItem>
                  <SelectItem value="female">Nữ</SelectItem>
                  <SelectItem value="other">Khác</SelectItem>
                </SelectContent>
              </Select>
              <div v-if="errors.gender" class="text-sm text-destructive">{{ errors.gender }}</div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
              <Label for="password">
                Mật khẩu <span class="text-destructive">*</span>
              </Label>
              <Input
                id="password"
                v-model="form.password"
                type="password"
                required
                placeholder="Mật khẩu mặc định"
              />
              <p class="text-sm text-muted-foreground">Mật khẩu mặc định sẽ được gửi qua email</p>
              <div v-if="errors.password" class="text-sm text-destructive">{{ errors.password }}</div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-6">
              <Link :href="/admin/teachers">
                <Button variant="outline" type="button">
                  Hủy
                </Button>
              </Link>
              <Button
                type="submit"
                :disabled="processing"
              >
                <UserPlus class="mr-2 h-4 w-4" />
                Tạo tài khoản
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { UserPlus } from 'lucide-vue-next'
import { router, Link } from '@inertiajs/vue3'

const props = defineProps({
  errors: Object,
})

const form = reactive({
  name: '',
  email: '',
  phone: '',
  gender: '',
  password: 'password123', // Mật khẩu mặc định
})

const processing = ref(false)

const createTeacher = () => {
  processing.value = true
  router.post('/admin/teachers', form, {
    onFinish: () => {
      processing.value = false
    },
  })
}

const breadcrumbs = [
  { title: 'Admin', href: '/admin' },
  { title: 'Quản lý giáo viên', href: '/admin/teachers' },
  { title: 'Tạo tài khoản' }
]
</script>
