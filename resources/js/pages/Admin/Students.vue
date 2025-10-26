<template>
  <AdminLayout 
    title="Quản lý học sinh - Admin" 
    page-title="Quản lý học sinh"
    :breadcrumbs="breadcrumbs"
  >
    <div class="space-y-6">
      <!-- Header Actions -->
      <div class="flex justify-between items-center">
        <div>
          <h2 class="text-2xl font-bold">Quản lý học sinh</h2>
          <p class="text-muted-foreground">Quản lý tài khoản và thông tin học sinh</p>
        </div>
        <Button>
          <Plus class="mr-2 h-4 w-4" />
          Thêm học sinh
        </Button>
      </div>

      <!-- Search and Filter -->
      <Card>
        <CardHeader>
          <CardTitle>Tìm kiếm và lọc</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <Label>Tìm kiếm</Label>
              <Input placeholder="Tìm theo tên học sinh..." />
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
              <Label>Lớp học</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Tất cả lớp" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">Tất cả lớp</SelectItem>
                  <SelectItem value="10">Lớp 10</SelectItem>
                  <SelectItem value="11">Lớp 11</SelectItem>
                  <SelectItem value="12">Lớp 12</SelectItem>
                </SelectContent>
              </Select>
            </div>
            <div>
              <Label>Khóa học</Label>
              <Select>
                <SelectTrigger>
                  <SelectValue placeholder="Tất cả khóa học" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="all">Tất cả khóa học</SelectItem>
                  <SelectItem value="python">Python</SelectItem>
                  <SelectItem value="react">React</SelectItem>
                  <SelectItem value="ml">Machine Learning</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Students Table -->
      <Card>
        <CardHeader>
          <CardTitle>Danh sách học sinh</CardTitle>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Học sinh</TableHead>
                <TableHead>Email</TableHead>
                <TableHead>Lớp học</TableHead>
                <TableHead>Khóa học</TableHead>
                <TableHead>Trạng thái</TableHead>
                <TableHead>Ngày đăng ký</TableHead>
                <TableHead class="text-right">Thao tác</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="student in students" :key="student.id">
                <TableCell>
                  <div class="flex items-center gap-3">
                    <Avatar>
                      <AvatarImage :src="student.avatar" :alt="student.name" />
                      <AvatarFallback>{{ getInitials(student.name) }}</AvatarFallback>
                    </Avatar>
                    <div>
                      <div class="font-medium">{{ student.name }}</div>
                      <div class="text-sm text-muted-foreground">{{ student.phone }}</div>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ student.email }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ student.grade }}</div>
                </TableCell>
                <TableCell>
                  <div class="text-sm">{{ student.courses }}</div>
                </TableCell>
                <TableCell>
                  <Badge :variant="getStatusVariant(student.status)">
                    {{ student.statusText }}
                  </Badge>
                </TableCell>
                <TableCell class="text-sm text-muted-foreground">
                  {{ student.createdAt }}
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
              Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">20</span> của <span class="font-medium">156</span> kết quả
            </div>
            <div class="flex gap-2">
              <Button variant="outline" size="sm">
                <ChevronLeft class="h-4 w-4" />
              </Button>
              <Button variant="outline" size="sm">1</Button>
              <Button variant="outline" size="sm">2</Button>
              <Button variant="outline" size="sm">3</Button>
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
import { getInitials } from '@/composables/useInitials'

// Helper function to generate avatar
function generateAvatar(name, color) {
  const svg = `<svg width="40" height="40" xmlns="http://www.w3.org/2000/svg">
    <rect width="40" height="40" fill="${color}" rx="20"/>
    <text x="20" y="25" text-anchor="middle" fill="#FFFFFF" font-family="Arial, sans-serif" font-size="14" font-weight="bold">${name.charAt(0)}</text>
  </svg>`
  return 'data:image/svg+xml;base64,' + btoa(svg)
}

// Get status variant for Badge
function getStatusVariant(status) {
  return status === 'active' ? 'default' : status === 'inactive' ? 'destructive' : 'secondary'
}

// Sample data
const students = [
  {
    id: 1,
    name: 'Nguyễn Văn An',
    email: 'nguyenvanan@example.com',
    phone: '0123456789',
    avatar: generateAvatar('Nguyễn Văn An', '#3B82F6'),
    grade: 'Lớp 12',
    courses: 3,
    status: 'active',
    statusText: 'Đang hoạt động',
    createdAt: '15/01/2024'
  },
  {
    id: 2,
    name: 'Trần Thị Bình',
    email: 'tranthibinh@example.com',
    phone: '0987654321',
    avatar: generateAvatar('Trần Thị Bình', '#10B981'),
    grade: 'Lớp 11',
    courses: 2,
    status: 'active',
    statusText: 'Đang hoạt động',
    createdAt: '10/01/2024'
  },
  {
    id: 3,
    name: 'Lê Văn Cường',
    email: 'levancuong@example.com',
    phone: '0555666777',
    avatar: generateAvatar('Lê Văn Cường', '#F59E0B'),
    grade: 'Lớp 10',
    courses: 1,
    status: 'pending',
    statusText: 'Chờ duyệt',
    createdAt: '08/01/2024'
  },
  {
    id: 4,
    name: 'Phạm Thị Dung',
    email: 'phamthidung@example.com',
    phone: '0333444555',
    avatar: generateAvatar('Phạm Thị Dung', '#EF4444'),
    grade: 'Lớp 12',
    courses: 0,
    status: 'inactive',
    statusText: 'Tạm dừng',
    createdAt: '05/01/2024'
  }
]

const breadcrumbs = [
  { title: 'Admin', href: '/admin' },
  { title: 'Quản lý học sinh' }
]
</script>
