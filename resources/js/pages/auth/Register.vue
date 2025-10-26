<script setup lang="ts">
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import AuthBase from '@/layouts/AuthLayout.vue';
// import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

// Reactive data for password visibility
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

// Form validation
const formData = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    gender: ''
});

// Email validation
const emailError = ref('');
const isValidEmail = (email: string) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
};

// Check if form is valid
const isFormValid = ref(false);

const validateForm = () => {
    const { name, email, password, password_confirmation, gender } = formData.value;
    
    // Check required fields
    const hasRequiredFields = name.trim() !== '' && 
                             email.trim() !== '' && 
                             password.trim() !== '' && 
                             password_confirmation.trim() !== '' &&
                             gender !== '';
    
    // Check email format
    const isEmailValid = isValidEmail(email);
    
    // Check password match
    const isPasswordMatch = password === password_confirmation && password.length >= 8;
    
    // Check name length
    const isNameValid = name.trim().length >= 2;
    
    isFormValid.value = hasRequiredFields && isEmailValid && isPasswordMatch && isNameValid;
    
    return isFormValid.value;
};

// Watch form data changes
const updateFormData = (field: string, value: string) => {
    formData.value[field] = value;
    validateForm();
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <Head title="Đăng ký - DoraEdu" />
        
        <!-- Left Side - Branding and Welcome Message -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-50 to-indigo-100 flex-col justify-center items-center px-12">
            <div class="max-w-4xl text-center">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-lg">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
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

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-md">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900">Đăng ký</h2>
                    </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid gap-2">
                        <Label for="name">Họ và tên <span class="text-red-500">*</span></Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autofocus
                            :tabindex="1"
                            autocomplete="name"
                            name="name"
                            placeholder="Họ và tên"
                        />
                        <InputError :message="errors.name" />
                    </div>
                    
                    <div class="grid gap-2">
                        <Label for="gender">Giới tính</Label>
                        <select 
                            name="gender" 
                            id="gender"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            :tabindex="2"
                        >
                            <option value="">Chọn giới tính</option>
                            <option value="male">Nam</option>
                            <option value="female">Nữ</option>
                            <option value="other">Khác</option>
                        </select>
                        <InputError :message="errors.gender" />
                    </div>
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email <span class="text-red-500">*</span></Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="3"
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone">Số điện thoại <span class="text-gray-500 text-sm">(không bắt buộc)</span></Label>
                    <Input
                        id="phone"
                        type="tel"
                        :tabindex="4"
                        autocomplete="tel"
                        name="phone"
                        placeholder="Số điện thoại"
                    />
                    <InputError :message="errors.phone" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Mật khẩu <span class="text-red-500">*</span></Label>
                    <div class="relative">
                        <Input
                            id="password"
                            :type="showPassword ? 'text' : 'password'"
                            required
                            :tabindex="5"
                            autocomplete="new-password"
                            name="password"
                            placeholder="Nhập mật khẩu"
                            class="pr-10"
                        />
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg v-if="!showPassword" class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Xác nhận mật khẩu <span class="text-red-500">*</span></Label>
                    <div class="relative">
                        <Input
                            id="password_confirmation"
                            :type="showPasswordConfirmation ? 'text' : 'password'"
                            required
                            :tabindex="6"
                            autocomplete="new-password"
                            name="password_confirmation"
                            placeholder="Nhập lại mật khẩu"
                            class="pr-10"
                        />
                        <button
                            type="button"
                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg v-if="!showPasswordConfirmation" class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <svg v-else class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                        </button>
                    </div>
                    <InputError :message="errors.password_confirmation" />
                </div>

                <!-- Hidden input để tự động set role = student -->
                <input type="hidden" name="role" value="student" />
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Bạn đang đăng ký tài khoản học sinh.
                            </h3>
                            <div class="mt-1 text-sm text-blue-700">
                                <p>Tài khoản giáo viên chỉ được cấp bởi DoraEdu.</p>
                            </div>
                        </div>
                    </div>
                </div>

                    <Button
                        type="submit"
                        class="mt-2 w-full"
                        tabindex="7"
                        :disabled="processing"
                        data-test="register-user-button"
                    >
                    <LoaderCircle
                        v-if="processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Đăng ký
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Đã có tài khoản?
                <TextLink
                    href="/login"
                    class="underline underline-offset-4"
                    :tabindex="8"
                    >Đăng nhập</TextLink
                >
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
