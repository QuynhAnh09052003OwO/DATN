<template>
  <div class="min-h-screen bg-blue-50">
    <Head :title="title" />
    
    <!-- Fixed Header -->
    <header class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo and Navigation -->
          <div class="flex items-center space-x-8">
            <!-- Logo -->
            <Link href="/" class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-yellow-500" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M5 16L3 8l5.5 5L12 4l3.5 9L21 8l-2 8H5zm2.7-2h8.6l.9-4.4L14 12l-2-5.5L10 12l-3.2-2.4L7.7 14z"/>
                </svg>
              </div>
              <span class="text-2xl font-bold text-blue-800">DoraEdu</span>
            </Link>
            
            <!-- Navigation Links -->
            <nav class="hidden md:flex space-x-6">
              <Link href="/about" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors">VỀ DORAEDU</Link>
              <Link href="/courses" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors">KHÓA HỌC</Link>
              <Link href="/books" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors">SÁCH</Link>
              <Link href="/flashcards" class="text-blue-800 font-semibold hover:text-blue-600 transition-colors">FLASHCARD</Link>
            </nav>
          </div>

          <!-- Auth Buttons or User Menu -->
          <div class="flex items-center space-x-4">
            <!-- If user is not logged in -->
            <template v-if="!user">
              <Link 
                href="/login" 
                class="text-blue-800 font-semibold hover:text-blue-600 transition-colors border border-blue-800 px-4 py-2 rounded-lg"
              >
                Đăng nhập
              </Link>
              <Link 
                href="/register" 
                class="bg-blue-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-900 transition-colors"
              >
                Đăng ký
              </Link>
            </template>
            
            <!-- If user is logged in -->
            <template v-else>
              <div class="relative">
                <button 
                  @click="userMenuOpen = !userMenuOpen" 
                  type="button" 
                  class="flex items-center space-x-2 bg-white border border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <span class="text-sm font-medium text-gray-700">{{ user.name }}</span>
                  <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div 
                  v-if="userMenuOpen" 
                  @click.away="userMenuOpen = false"
                  class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                >
                  <!-- Show "Quản lý" if user is admin -->
                  <Link 
                    v-if="user && user.role === 'admin'"
                    href="/admin" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    @click="userMenuOpen = false"
                  >
                    Quản lý
                  </Link>
                  <Link 
                    href="/settings" 
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    @click="userMenuOpen = false"
                  >
                    Cài đặt tài khoản
                  </Link>
                  <Link 
                    href="/logout" 
                    method="post" 
                    as="button"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    @click="handleLogout"
                  >
                    Đăng xuất
                  </Link>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </header>
    
    <!-- Main Content with padding for fixed header -->
    <main class="pt-16">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

const props = defineProps({
  title: String,
})

const page = usePage()
const user = computed(() => page.props.auth?.user)
const userMenuOpen = ref(false)

// Reload layout sau khi đăng xuất
watch(
  () => page.props.auth?.user,
  (newUser, oldUser) => {
    // Nếu user chuyển từ có user sang không có user (đã đăng xuất)
    if (oldUser && !newUser) {
      // Reload lại trang để refresh layout
      router.reload({ only: [] })
    }
  }
)

// Handle logout click
const handleLogout = () => {
  userMenuOpen.value = false
}
</script>

