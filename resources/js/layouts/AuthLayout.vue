<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <AuthLayout class="bg-linear-to-br from-[#FDFDFC] to-[#e3ffee] " :title="title" :description="description">
        <div class="bg-white p-8">
            <slot />
        </div>
    </AuthLayout>
</template>
