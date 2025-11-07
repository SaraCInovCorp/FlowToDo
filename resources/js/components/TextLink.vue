<script setup lang="ts">
import { LinkComponentBaseProps, Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';

interface Props {
    href: LinkComponentBaseProps['href'];
    tabindex?: number;
    method?: Method;
    as?: string;
}

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :tabindex="tabindex"
        :method="method"
        :as="as"
        class="text-(--flowtodo-blue)] decoration-neutral-300 transition-colors duration-300 ease-out hover:underline hover:text-(--flowtodo-blue-escuro)] hover:decoration-current! dark:decoration-neutral-500"
    >
        <slot />
    </Link>
</template>
