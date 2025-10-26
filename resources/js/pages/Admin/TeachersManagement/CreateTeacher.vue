<template>
  <AdminLayout title="Tạo tài khoản giáo viên - Admin" page-title="Tạo tài khoản giáo viên">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white shadow rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Tạo tài khoản giáo viên mới</h2>
        
        <form @submit.prevent="createTeacher" class="space-y-6">
          <!-- Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Họ và tên <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Nhập họ và tên"
            />
            <div v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</div>
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Email <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="email@example.com"
            />
            <div v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</div>
          </div>

          <!-- Phone -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
            <input
              v-model="form.phone"
              type="tel"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="0123456789"
            />
            <div v-if="errors.phone" class="mt-1 text-sm text-red-600">{{ errors.phone }}</div>
          </div>

          <!-- Gender -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
            <select
              v-model="form.gender"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
              <option value="">Chọn giới tính</option>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
              <option value="other">Khác</option>
            </select>
            <div v-if="errors.gender" class="mt-1 text-sm text-red-600">{{ errors.gender }}</div>
          </div>

          <!-- Password -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Mật khẩu <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Mật khẩu mặc định"
            />
            <p class="mt-1 text-sm text-gray-500">Mật khẩu mặc định sẽ được gửi qua email</p>
            <div v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</div>
          </div>

          <!-- Buttons -->
          <div class="flex justify-end space-x-4 pt-6">
            <Link
              href="/admin/teachers"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Hủy
            </Link>
            <button
              type="submit"
              :disabled="processing"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              Tạo tài khoản
            </button>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
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
</script>
