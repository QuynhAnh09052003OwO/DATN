<script setup>
import { ref } from 'vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { SidebarProvider, Sidebar, SidebarContent, SidebarHeader, SidebarGroup, SidebarGroupContent, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem, SidebarTrigger } from '@/components/ui/sidebar'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu'
import { Button } from '@/components/ui/button'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import { getInitials } from '@/composables/useInitials'
import { Home, BookOpen, Users, GraduationCap, User, LogOut } from 'lucide-vue-next'

const props = defineProps({
  title: String,
  pageTitle: String,
  breadcrumbs: {
    type: Array,
    default: () => [],
  },
})

const coursesMenuOpen = ref(false)
const teachersMenuOpen = ref(false)

const page = usePage()
const user = page.props.auth.user

// Auto-open menus based on current route
if (page.url.startsWith('/admin/courses')) {
  coursesMenuOpen.value = true
}

if (page.url.startsWith('/admin/teachers')) {
  teachersMenuOpen.value = true
}

// Methods
const toggleCoursesMenu = () => {
  coursesMenuOpen.value = !coursesMenuOpen.value
}

const toggleTeachersMenu = () => {
  teachersMenuOpen.value = !teachersMenuOpen.value
}
</script>

<template>
  <SidebarProvider>
    <Head :title="title" />
    
    <div class="flex min-h-screen w-full">
      <!-- Sidebar -->
      <Sidebar collapsible="icon" variant="inset" class="border-r">
        <SidebarHeader class="bg-blue-600">
          <SidebarMenu>
            <SidebarMenuItem>
              <SidebarMenuButton size="lg" as-child>
                <Link href="/admin">
                  <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                      <svg class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M5 16L3 8l5.5 5L12 4l3.5 9L21 8l-2 8H5zm2.7-2h8.6l.9-4.4L14 12l-2-5.5L10 12l-3.2-2.4L7.7 14z"/>
                      </svg>
                    </div>
                    <span class="text-xl font-bold text-white">DoraEdu Admin</span>
                  </div>
                </Link>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
          <SidebarGroup>
            <SidebarGroupContent>
              <!-- Tổng quan -->
              <SidebarMenuItem>
                <SidebarMenuButton :is-active="page.url === '/admin'" as-child>
                  <Link href="/admin">
                    <Home class="mr-2 h-4 w-4" />
                    <span>Tổng quan</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>

              <!-- Trang chủ -->
              <SidebarMenuItem>
                <SidebarMenuButton :is-active="page.url === '/'" as-child>
                  <Link href="/">
                    <Home class="mr-2 h-4 w-4" />
                    <span>Trang chủ</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>

              <!-- Khóa học -->
              <SidebarMenuItem>
                <SidebarMenuButton :is-active="page.url === '/courses'" as-child>
                  <Link href="/courses">
                    <BookOpen class="mr-2 h-4 w-4" />
                    <span>Khóa học</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>

              <!-- Quản lý khóa học -->
              <SidebarMenuItem>
                <!-- <SidebarMenuSub> -->
                  <SidebarMenuSubButton @click="toggleCoursesMenu">
                    <BookOpen class="mr-2 h-4 w-4" />
                    <span>Quản lý khóa học</span>
                  </SidebarMenuSubButton>
                  <SidebarMenuSub v-show="coursesMenuOpen">
                    <SidebarMenuButton :is-active="page.url === '/admin/courses'" as-child>
                      <Link href="/admin/courses">
                        <span>Danh sách khóa học</span>
                      </Link>
                    </SidebarMenuButton>
                  </SidebarMenuSub>
                  <SidebarMenuSub v-show="coursesMenuOpen">
                    <SidebarMenuButton :is-active="page.url.startsWith('/admin/courses/categories')" as-child>
                      <Link href="/admin/courses/categories">
                        <span>Phân loại khóa học</span>
                      </Link>
                    </SidebarMenuButton>
                  </SidebarMenuSub>
                <!-- </SidebarMenuSub> -->
              </SidebarMenuItem>

              <!-- Quản lý giáo viên -->
              <SidebarMenuItem>
                <!-- <SidebarMenuSub> -->
                  <SidebarMenuSubButton @click="toggleTeachersMenu">
                    <GraduationCap class="mr-2 h-4 w-4" />
                    <span>Quản lý giáo viên</span>
                  </SidebarMenuSubButton>
                  <SidebarMenuSub v-show="teachersMenuOpen">
                    <SidebarMenuButton :is-active="page.url === '/admin/teachers'" as-child>
                      <Link href="/admin/teachers">
                        <span>Danh sách giáo viên</span>
                      </Link>
                    </SidebarMenuButton>
                  </SidebarMenuSub>
                  <SidebarMenuSub v-show="teachersMenuOpen">
                    <SidebarMenuButton :is-active="page.url === '/admin/teachers/create'" as-child>
                      <Link href="/admin/teachers/create">
                        <span>Tạo tài khoản giáo viên</span>
                      </Link>
                    </SidebarMenuButton>
                  </SidebarMenuSub>
                <!-- </SidebarMenuSub> -->
              </SidebarMenuItem>

              <!-- Quản lý học sinh -->
              <SidebarMenuItem>
                <SidebarMenuButton :is-active="page.url.startsWith('/admin/students')" as-child>
                  <Link href="/admin/students">
                    <Users class="mr-2 h-4 w-4" />
                    <span>Quản lý học sinh</span>
                  </Link>
                </SidebarMenuButton>
              </SidebarMenuItem>
            </SidebarGroupContent>
          </SidebarGroup>
        </SidebarContent>

        <SidebarFooter>
          <SidebarMenu>
            <SidebarMenuItem>
              <DropdownMenu>
                <DropdownMenuTrigger as-child>
                  <SidebarMenuButton class="w-full justify-start">
                    <Avatar class="h-8 w-8 rounded-full mr-2">
                      <AvatarFallback class="bg-blue-600 text-white">
                        {{ getInitials(user?.name) }}
                      </AvatarFallback>
                    </Avatar>
                    <div class="flex flex-col items-start text-left">
                      <span class="text-sm font-medium">{{ user?.name }}</span>
                      <span class="text-xs text-muted-foreground">Administrator</span>
                    </div>
                  </SidebarMenuButton>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="w-56">
                  <DropdownMenuItem>
                    <User class="mr-2 h-4 w-4" />
                    <span>Profile</span>
                  </DropdownMenuItem>
                  <DropdownMenuItem>
                    <span>Settings</span>
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem as-child>
                    <Link href="/logout" method="post">
                      <LogOut class="mr-2 h-4 w-4" />
                      <span>Đăng xuất</span>
                    </Link>
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarFooter>
      </Sidebar>

      <!-- Main content area -->
      <div class="flex-1 flex flex-col">
        <header class="h-16 border-b flex items-center gap-2 px-6">
          <SidebarTrigger />
          <h1 class="text-2xl font-semibold">{{ pageTitle }}</h1>
        </header>
        
        <main class="flex-1 overflow-auto">
          <div class="p-6">
            <slot />
          </div>
        </main>
      </div>
    </div>
  </SidebarProvider>
</template>
