<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';

const appName = import.meta.env.VITE_APP_NAME || 'FlowToDo';
</script>

<template>
  <div class="flex items-center">
    <!-- Ícone -->
   <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar text-sidebar-primary-foreground border border-sidebar-border/50"
    >
        <AppLogoIcon class="size-5 fill-current" />
    </div>


    <!-- Nome dinâmico -->
    <div class="ml-1 grid flex-1 text-left text-md">
      <span class="mb-0.5 truncate leading-tight font-semibold">
        {{ appName }}
      </span>
    </div>
  </div>
</template>
