<template>
  <AdminLayout 
    title="Quản lý người dùng - Admin" 
    page-title="Quản lý người dùng"
    :breadcrumbs="breadcrumbs"
  >
    <Card>
      <CardHeader>
        <CardTitle>Quản lý người dùng</CardTitle>
        <CardDescription>
          Quản lý tất cả người dùng trong hệ thống
        </CardDescription>
      </CardHeader>
      <CardContent>
        <p class="text-muted-foreground">Danh sách người dùng sẽ được hiển thị ở đây</p>
      </CardContent>
    </Card>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Head } from '@inertiajs/vue3'

const breadcrumbs = [
  { title: 'Admin', href: '/admin' },
  { title: 'Quản lý người dùng' }
]
</script>
