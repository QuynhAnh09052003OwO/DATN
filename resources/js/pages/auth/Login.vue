<script setup lang="ts">
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
// import { store } from '@/routes/login';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const props = defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
    userType?: string;
}>();

// Get user type for display
const getUserTypeDisplay = () => {
    switch (props.userType) {
        case 'student':
            return 'Học sinh';
        case 'teacher':
            return 'Giáo viên';
        case 'admin':
            return 'Quản trị viên';
        default:
            return 'Người dùng';
    }
};

// Reactive data for password visibility
const showPassword = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <Head title="Đăng nhập - DoraEdu" />
        
        <!-- Left Side - Branding and Welcome Message -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-blue-100 flex-col justify-center items-center px-12">
            <div class="max-w-4xl text-center">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <div class="w-48 h-48 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <div class="w-36 h-36 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                            <svg class="w-24 h-24 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M5 16L3 8l5.5 5L12 4l3.5 9L21 8l-2 8H5zm2.7-2h8.6l.9-4.4L14 12l-2-5.5L10 12l-3.2-2.4L7.7 14z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <!-- Brand Name -->
                <h1 class="text-xl font-bold text-gray-900 mb-8 handwritten" style="font-size: 8rem;">
                    DoraEdu
                </h1>
                
                <!-- Welcome Message -->
                <div class="text-2xl text-gray-700 leading-relaxed">
                    <p class="font-semibold">Chào mừng bạn đến với hệ</p>
                    <p class="font-semibold">thống học tập DoraEdu.</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Đăng nhập</h2>
                        <p class="mt-2 text-sm text-gray-600">
                            Đăng nhập với tài khoản <span class="font-semibold text-blue-600">{{ getUserTypeDisplay() }}</span>
                        </p>
                    </div>

                    <div
                        v-if="status"
                        class="mb-4 text-center text-sm font-medium text-green-600"
                    >
                        {{ status }}
                    </div>

                    <Form
                        method="post"
                        action="/login"
                        :reset-on-success="['password']"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-6"
                    >
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="email">Email <span class="text-red-500">*</span></Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    placeholder="Nhập email tại đây"
                                />
                                <InputError :message="errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">Mật khẩu <span class="text-red-500">*</span></Label>
                                <div class="relative">
                                    <Input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        name="password"
                                        required
                                        :tabindex="2"
                                        autocomplete="current-password"
                                        placeholder="Nhập mật khẩu tại đây"
                                        class="pr-20 text-lg"
                                    />
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                    >
                                        <svg v-if="!showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg v-else class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <InputError :message="errors.password" />
                            </div>

                            <div class="flex items-center justify-between">
                                <Label for="remember" class="flex items-center space-x-2">
                                    <Checkbox id="remember" name="remember" :tabindex="3" />
                                    <span class="text-sm">Ghi nhớ mật khẩu</span>
                                </Label>
                                <TextLink
                                    v-if="canResetPassword"
                                    href="/forgot-password"
                                    class="text-sm"
                                    :tabindex="4"
                                >
                                    Quên mật khẩu?
                                </TextLink>
                            </div>

                            <Button
                                type="submit"
                                class="mt-4 w-full bg-blue-600 hover:bg-blue-700"
                                :tabindex="5"
                                :disabled="processing"
                                data-test="login-button"
                            >
                                <LoaderCircle
                                    v-if="processing"
                                    class="h-4 w-4 animate-spin"
                                />
                                Đăng nhập
                            </Button>
                        </div>

                        <div
                            class="text-center text-sm text-muted-foreground"
                            v-if="canRegister"
                        >
                            Chưa có tài khoản?
                            <TextLink href="/register" :tabindex="6">Đăng ký ngay</TextLink>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom font for handwritten style titles */
@import url('https://fonts.googleapis.com/css2?family=Kalam:wght@400;700&display=swap');

.handwritten {
    font-family: 'Kalam', cursive;
}
</style>
