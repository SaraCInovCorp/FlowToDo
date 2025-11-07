<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
  title?: string;
  description?: string;
}>();
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
    <header class="w-full max-w-sm">
      <Link :href="home()" class="flex flex-col items-center gap-2 font-medium">
        <div class="mb-1 flex h-20 w-20 items-center justify-center rounded-md">
          <AppLogoIcon class="h-12 w-12 m-4" />
          <span class="font-bold text-lg text-(--flowtodo-blue)">FlowToDo</span>
        </div>
        <span class="sr-only">{{ title }}</span>
      </Link>
      <div class="space-y-2 text-center">
        <h1 class="text-xl font-medium">{{ title }}</h1>
        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
      </div>
    </header>

    <main class="w-full max-w-sm">
      <slot />
    </main>
  </div>
</template>
