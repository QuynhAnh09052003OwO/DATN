<template>
  <div class="min-h-screen bg-blue-50">
    <Head :title="title" />
    
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
      <!-- Logo -->
      <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
        <div class="flex items-center space-x-2">
          <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
            <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
              <path d="M5 16L3 8l5.5 5L12 4l3.5 9L21 8l-2 8H5zm2.7-2h8.6l.9-4.4L14 12l-2-5.5L10 12l-3.2-2.4L7.7 14z"/>
            </svg>
          </div>
          <span class="text-xl font-bold text-white">DoraEdu Admin</span>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="mt-5">
        <Link href="/admin" :class="{'bg-blue-100 text-blue-700': $page.url.startsWith('/admin') && $page.url === '/admin', 'text-gray-600 hover:bg-gray-50 hover:text-gray-900': !($page.url.startsWith('/admin') && $page.url === '/admin')}" class="flex items-center px-4 py-2 text-sm font-medium rounded-md">
          <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
          </svg>
          Tổng quan
        </Link>
        <Link href="/admin/courses" :class="{'bg-blue-100 text-blue-700': $page.url.startsWith('/admin/courses'), 'text-gray-600 hover:bg-gray-50 hover:text-gray-900': !$page.url.startsWith('/admin/courses')}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium rounded-md">
          <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
          </svg>
          Quản lý khóa học
        </Link>
        <Link href="/admin/teachers" :class="{'bg-blue-100 text-blue-700': $page.url.startsWith('/admin/teachers'), 'text-gray-600 hover:bg-gray-50 hover:text-gray-900': !$page.url.startsWith('/admin/teachers')}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium rounded-md">
          <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
          </svg>
          Quản lý giáo viên
        </Link>
        <Link href="/admin/students" :class="{'bg-blue-100 text-blue-700': $page.url.startsWith('/admin/students'), 'text-gray-600 hover:bg-gray-50 hover:text-gray-900': !$page.url.startsWith('/admin/students')}" class="mt-1 flex items-center px-4 py-2 text-sm font-medium rounded-md">
          <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
          </svg>
          Quản lý học sinh
        </Link>
      </nav>
    </div>

    <!-- Main content area -->
    <div class="lg:pl-64 flex flex-col flex-1">
      <!-- Top bar -->
      <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow-sm">
        <button @click="sidebarOpen = !sidebarOpen" class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 lg:hidden">
          <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        <div class="flex-1 px-4 flex justify-between">
          <div class="flex-1 flex items-center">
            <h1 class="text-2xl font-semibold text-gray-900">{{ pageTitle }}</h1>
          </div>
          <div class="ml-4 flex items-center md:ml-6">
            <!-- User dropdown -->
            <div class="ml-3 relative">
              <div>
                <button @click="userMenuOpen = !userMenuOpen" type="button" class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150/0000FF/FFFFFF?text=Admin" alt="">
                </button>
              </div>

              <div v-if="userMenuOpen" @click.away="userMenuOpen = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Your Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Settings</a>
                <Link href="/logout" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                  Đăng xuất
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>

      <main class="flex-1">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Content goes here -->
            <slot />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'

const props = defineProps({
  title: String,
  pageTitle: String,
})

const sidebarOpen = ref(false)
const userMenuOpen = ref(false)

const page = usePage()
const user = page.props.auth.user
</script>
