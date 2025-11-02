<script lang="ts" setup>
import type { ToasterProps } from "vue-sonner"
import { Toaster as Sonner } from "vue-sonner"

const props = defineProps<ToasterProps>()

// Merge richColors với props
const toasterProps = {
  ...props,
  richColors: true
}
</script>

<template>
  <Sonner
    class="toaster group custom-sonner"
    v-bind="toasterProps"
    :toast-options="{
      classes: {
        toast: 'group toast custom-toast',
        description: 'custom-description',
        actionButton: 'custom-action-button',
        cancelButton: 'custom-cancel-button',
        success: 'custom-success',
        error: 'custom-error',
        warning: 'custom-warning',
        info: 'custom-info',
      },
    }"
  />
</template>

<style scoped>
/* Custom Sonner Styles - Toast to hơn với màu xanh biển và trắng */

/* Toast container - Làm to hơn */
:deep([data-sonner-toast][data-styled='true']) {
  padding: 20px 24px !important;
  background: #ffffff !important;
  border: 2px solid #3b82f6 !important;
  color: #111827 !important;
  border-radius: 12px !important;
  box-shadow: 0px 8px 24px rgba(59, 130, 246, 0.15) !important;
  min-width: 380px !important;
  max-width: 450px !important;
  min-height: 80px !important;
  font-size: 15px !important;
  display: flex !important;
  align-items: center !important;
  gap: 12px !important;
}

/* Title - To hơn */
:deep([data-sonner-toast][data-styled='true'] [data-title]) {
  font-weight: 600 !important;
  font-size: 16px !important;
  line-height: 1.5 !important;
  color: #111827 !important;
}

/* Description - Màu xám nhẹ */
:deep([data-sonner-toast][data-styled='true'] [data-description]) {
  font-weight: 400 !important;
  font-size: 14px !important;
  line-height: 1.5 !important;
  color: #4b5563 !important;
  margin-top: 4px !important;
}

/* Icon - To hơn */
:deep([data-sonner-toast][data-styled='true'] [data-icon]) {
  height: 22px !important;
  width: 22px !important;
  flex-shrink: 0 !important;
  color: #3b82f6 !important; /* Màu mặc định xanh biển */
}

/* Icon màu theo loại toast */
:deep([data-rich-colors='true'][data-sonner-toast][data-type='success'] [data-icon]) {
  color: #10b981 !important; /* Xanh lá */
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='success'] [data-icon] svg) {
  color: #10b981 !important;
  fill: #10b981 !important;
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='error'] [data-icon]) {
  color: #ef4444 !important; /* Đỏ */
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='error'] [data-icon] svg) {
  color: #ef4444 !important;
  fill: #ef4444 !important;
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='warning'] [data-icon]) {
  color: #f59e0b !important; /* Vàng */
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='warning'] [data-icon] svg) {
  color: #f59e0b !important;
  fill: #f59e0b !important;
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='info'] [data-icon]) {
  color: #3b82f6 !important; /* Xanh biển */
}

:deep([data-rich-colors='true'][data-sonner-toast][data-type='info'] [data-icon] svg) {
  color: #3b82f6 !important;
  fill: #3b82f6 !important;
}

/* Icon mặc định cho toast không có type */
:deep([data-sonner-toast][data-styled='true'] [data-icon] svg) {
  color: #3b82f6 !important;
  fill: #3b82f6 !important;
}

/* Action Button - Xanh biển với text trắng */
:deep([data-sonner-toast][data-styled='true'] [data-button]) {
  border-radius: 8px !important;
  padding: 10px 20px !important;
  height: 38px !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  color: #ffffff !important;
  background: #3b82f6 !important;
  border: none !important;
  cursor: pointer !important;
  transition: all 200ms !important;
  margin-left: auto !important;
}

:deep([data-sonner-toast][data-styled='true'] [data-button]:hover) {
  background: #2563eb !important;
  transform: translateY(-1px) !important;
}

:deep([data-sonner-toast][data-styled='true'] [data-button]:active) {
  background: #1d4ed8 !important;
  transform: translateY(0) !important;
}

/* Cancel Button - Xám nhạt */
:deep([data-sonner-toast][data-styled='true'] [data-cancel]) {
  border-radius: 8px !important;
  padding: 10px 20px !important;
  height: 38px !important;
  font-size: 14px !important;
  font-weight: 600 !important;
  color: #374151 !important;
  background: #f3f4f6 !important;
  border: 1px solid #e5e7eb !important;
  cursor: pointer !important;
  transition: all 200ms !important;
}

:deep([data-sonner-toast][data-styled='true'] [data-cancel]:hover) {
  background: #e5e7eb !important;
  border-color: #d1d5db !important;
}

/* Success Toast - Border xanh lá */
:deep([data-rich-colors='true'][data-sonner-toast][data-type='success']) {
  background: #ffffff !important;
  border-left: 4px solid #10b981 !important;
  border-top: 2px solid #3b82f6 !important;
  border-right: 2px solid #3b82f6 !important;
  border-bottom: 2px solid #3b82f6 !important;
  color: #111827 !important;
}

/* Error Toast - Border đỏ */
:deep([data-rich-colors='true'][data-sonner-toast][data-type='error']) {
  background: #ffffff !important;
  border-left: 4px solid #ef4444 !important;
  border-top: 2px solid #3b82f6 !important;
  border-right: 2px solid #3b82f6 !important;
  border-bottom: 2px solid #3b82f6 !important;
  color: #111827 !important;
}

/* Warning Toast - Border vàng */
:deep([data-rich-colors='true'][data-sonner-toast][data-type='warning']) {
  background: #ffffff !important;
  border-left: 4px solid #f59e0b !important;
  border-top: 2px solid #3b82f6 !important;
  border-right: 2px solid #3b82f6 !important;
  border-bottom: 2px solid #3b82f6 !important;
  color: #111827 !important;
}

/* Info Toast - Border xanh biển */
:deep([data-rich-colors='true'][data-sonner-toast][data-type='info']) {
  background: #ffffff !important;
  border-left: 4px solid #3b82f6 !important;
  border-top: 2px solid #3b82f6 !important;
  border-right: 2px solid #3b82f6 !important;
  border-bottom: 2px solid #3b82f6 !important;
  color: #111827 !important;
}

/* Close Button - Xanh biển nhạt */
:deep([data-sonner-toast][data-styled='true'] [data-close-button]) {
  background: #ffffff !important;
  border: 2px solid #3b82f6 !important;
  color: #3b82f6 !important;
  height: 24px !important;
  width: 24px !important;
}

:deep([data-sonner-toast][data-styled='true'] [data-close-button]:hover) {
  background: #eff6ff !important;
  border-color: #2563eb !important;
  color: #2563eb !important;
}

/* Mobile responsive */
@media (max-width: 600px) {
  :deep([data-sonner-toast][data-styled='true']) {
    min-width: calc(100% - 32px) !important;
    max-width: calc(100% - 32px) !important;
    padding: 16px 20px !important;
  }
}
</style>
