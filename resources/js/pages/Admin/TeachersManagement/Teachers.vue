<template>
  <AdminLayout 
    title="Quản lý giáo viên - Admin" 
    page-title="Quản lý giáo viên"
    :breadcrumbs="breadcrumbs"
  >
    <div class="space-y-6">
      <!-- Header Actions -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold">Danh sách giáo viên</h2>
          <p class="text-muted-foreground">Quản lý tài khoản và thông tin giáo viên</p>
        </div>
        <Link :href="'/admin/teachers/create'">
          <Button>
            <Plus class="mr-2 h-4 w-4" />
            Thêm giáo viên
          </Button>
        </Link>
      </div>

      <!-- Search and Filter -->
      <Card>
        <CardHeader>
          <CardTitle>Tìm kiếm và lọc</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <Label>Tìm kiếm</Label>
              <Input placeholder="Tìm theo tên giáo viên..." />
            </div>
            <div>
              <Label>Trạng thái</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Tất cả" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">Tất cả</SelectItem>
                  <SelectItem value="active">Đang hoạt động</SelectItem>
                  <SelectItem value="inactive">Tạm dừng</SelectItem>
                  <SelectItem value="pending">Chờ duyệt</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label>Môn học</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Tất cả môn học" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">Tất cả môn học</SelectItem>
                  <SelectItem value="math">Toán học</SelectItem>
                  <SelectItem value="physics">Vật lý</SelectItem>
                  <SelectItem value="chemistry">Hóa học</SelectItem>
                  <SelectItem value="programming">Lập trình</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Teachers Table -->
      <Card>
        <CardHeader>
          <CardTitle>Danh sách giáo viên</CardTitle>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Giáo viên</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Môn học</TableHead>
                <TableHead>Khóa học</TableHead>
                <TableHead>Trạng thái</TableHead>
                <TableHead>Ngày tạo</TableHead>
                <TableHead class="text-right">Thao tác</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="teacher in teachers_list" :key="teacher.id">
                <TableCell>
                  <div class="flex items-center gap-3">
                    <Avatar>
                      <AvatarImage :src="generateAvatar(teacher.name)" :alt="teacher.name" />
                      <AvatarFallback>{{ getInitials(teacher.name) }}</AvatarFallback>
                    </Avatar>
                    <div>
                      <div class="font-medium">{{ teacher.name }}</div>
                      <div class="text-sm text-muted-foreground">{{ teacher.phone || 'N/A' }}</div>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ teacher.email }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ teacher.subject || 'N/A' }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ teacher.courses_count || 0 }}</div>
                </TableCell>
                <TableCell>
                  <Badge variant="default">
                    Đang hoạt động
                  </Badge>
                </TableCell>
                <TableCell class="text-sm text-muted-foreground">
                  {{ formatDate(teacher.created_at) }}
                </TableCell>
                <TableCell class="text-right">
                  <div class="flex gap-2 justify-end">
                    <Button variant="ghost" size="sm">
                      <Pencil class="h-4 w-4" />
                    </Button>
                    <Button variant="ghost" size="sm">
                      <Trash2 class="h-4 w-4 text-destructive" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>

      <!-- Pagination -->
      <Card>
        <CardContent class="pt-6">
          <div class="flex items-center justify-between">
            <div class="text-sm text-muted-foreground">
              Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">12</span> kết quả
            </div>
            <div class="flex gap-2">
              <Button variant="outline" size="sm">
                <ChevronLeft class="h-4 w-4" />
              </Button>
              <Button variant="outline" size="sm">1</Button>
              <Button variant="outline" size="sm">2</Button>
              <Button variant="outline" size="sm">
                <ChevronRight class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/layouts/AdminLayout.vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Badge } from '@/components/ui/badge'
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Plus, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import { Link } from '@inertiajs/vue3'
import { getInitials } from '@/composables/useInitials'

const generateAvatar = (name, color = '#3B82F6') => {
  const initials = name.split(' ').map(n => n[0]).join('').toUpperCase()
  const svg = `<svg width="150" height="150" xmlns="http://www.w3.org/2000/svg">
    <rect width="150" height="150" fill="${color}"/>
    <text x="75" y="80" text-anchor="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-size="48" font-weight="bold">${initials}</text>
  </svg>`
  return 'data:image/svg+xml;base64,' + btoa(svg)
}

// Props từ backend
const props = defineProps({
  teachers: {
    type: Array,
    required: true,
    default: () => []
  }
})

// Sample data
const teachers_list = props.teachers.length > 0 ? props.teachers : [
  {
    id: 1,
    name: 'Nguyễn Văn A',
    email: 'nguyenvana@example.com',
    phone: '0123456789',
    avatar: generateAvatar('Nguyễn Văn A', '#3B82F6'),
    subject: 'Lập trình',
    courses_count: 5,
    status: 'active',
    statusText: 'Đang hoạt động',
    created_at: '2024-01-15'
  },
  {
    id: 2,
    name: 'Trần Thị B',
    email: 'tranthib@example.com',
    phone: '0987654321',
    avatar: generateAvatar('Trần Thị B', '#10B981'),
    subject: 'Toán học',
    courses_count: 3,
    status: 'active',
    statusText: 'Đang hoạt động',
    created_at: '2024-01-10'
  },
  {
    id: 3,
    name: 'Lê Văn C',
    email: 'levanc@example.com',
    phone: '0555666777',
    avatar: generateAvatar('Lê Văn C', '#F59E0B'),
    subject: 'Vật lý',
    courses_count: 2,
    status: 'pending',
    statusText: 'Chờ duyệt',
    created_at: '2024-01-08'
  },
  {
    id: 4,
    name: 'Phạm Thị D',
    email: 'phamthid@example.com',
    phone: '0333444555',
    avatar: generateAvatar('Phạm Thị D', '#EF4444'),
    subject: 'Hóa học',
    courses_count: 0,
    status: 'inactive',
    statusText: 'Tạm dừng',
    created_at: '2024-01-05'
  }
]

// Format date
const formatDate = (date) => {
  if (!date) return 'N/A'
  const d = new Date(date)
  return d.toLocaleDateString('vi-VN')
}

const breadcrumbs = [
  { title: 'Admin', href: '/admin' },
  { title: 'Quản lý giáo viên' }
]
</script>
