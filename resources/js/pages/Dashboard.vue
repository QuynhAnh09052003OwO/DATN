<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Define User interface
interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    gender?: string;
    phone?: string;
    email_verified_at?: string;
    created_at: string;
    updated_at: string;
}

// Get user role from props
const page = usePage();
const user = page.props.auth.user as User;
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <!-- Welcome Message -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">
                    Chào mừng, {{ user.name }}!
                </h1>
                <p class="text-blue-100">
                    Bạn đang đăng nhập với vai trò: 
                    <span class="font-semibold uppercase">{{ user.role }}</span>
                </p>
            </div>

            <!-- Role-specific content -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Admin Dashboard -->
                <template v-if="user.role === 'admin'">
                    <Link href="/admin/users" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-red-500 to-pink-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Quản lý người dùng</h3>
                            <p class="text-red-100">Quản lý tài khoản admin, teacher, student</p>
                        </div>
                    </Link>
                    <Link href="/admin/courses" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-green-500 to-teal-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Quản lý khóa học</h3>
                            <p class="text-green-100">Tạo và quản lý tất cả khóa học</p>
                        </div>
                    </Link>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-purple-500 to-indigo-600 text-white p-6">
                        <h3 class="text-xl font-bold mb-2">Thống kê hệ thống</h3>
                        <p class="text-purple-100">Xem báo cáo và thống kê tổng quan</p>
                    </div>
                </template>

                <!-- Teacher Dashboard -->
                <template v-else-if="user.role === 'teacher'">
                    <Link href="/teacher/courses" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-blue-500 to-cyan-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Khóa học của tôi</h3>
                            <p class="text-blue-100">Quản lý các khóa học đang dạy</p>
                        </div>
                    </Link>
                    <Link href="/teacher/students" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-orange-500 to-red-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Học sinh</h3>
                            <p class="text-orange-100">Quản lý danh sách học sinh</p>
                        </div>
                    </Link>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-teal-500 to-green-600 text-white p-6">
                        <h3 class="text-xl font-bold mb-2">Lịch dạy</h3>
                        <p class="text-teal-100">Xem lịch dạy và lên kế hoạch</p>
                    </div>
                </template>

                <!-- Student Dashboard -->
                <template v-else-if="user.role === 'student'">
                    <Link href="/student/courses" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-indigo-500 to-purple-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Khóa học của tôi</h3>
                            <p class="text-indigo-100">Xem các khóa học đã đăng ký</p>
                        </div>
                    </Link>
                    <Link href="/student/profile" class="group">
                        <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-pink-500 to-rose-600 text-white p-6 hover:shadow-lg transition-shadow">
                            <h3 class="text-xl font-bold mb-2">Hồ sơ cá nhân</h3>
                            <p class="text-pink-100">Cập nhật thông tin cá nhân</p>
                        </div>
                    </Link>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border bg-gradient-to-br from-yellow-500 to-orange-600 text-white p-6">
                        <h3 class="text-xl font-bold mb-2">Tiến độ học tập</h3>
                        <p class="text-yellow-100">Theo dõi tiến độ và điểm số</p>
                    </div>
                </template>

                <!-- Default for other roles -->
                <template v-else>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern />
                    </div>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern />
                    </div>
                    <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                        <PlaceholderPattern />
                    </div>
                </template>
            </div>

            <!-- Additional content area -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
