<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppLayout class="bg-linear-to-br from-[#FDFDFC] to-[#e3ffee] " :breadcrumbs="breadcrumbs">
            <slot />
    </AppLayout>
</template>
