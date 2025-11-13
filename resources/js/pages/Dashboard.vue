<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, router, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import {
  Card,
  CardTitle,
  CardContent,
  CardFooter
} from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import tasksRoutes from '@/routes/tasks'

const props = defineProps<{
  upcomingTasks: Array<any>;
  inProgressTasks: Array<any>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: dashboard().url,
  },
];

type Task = {
  id: number;
  title: string;
  description?: string;
  status: string;
  priority?: string;
  due_date?: string;
  task_type_id?: number;
}

function startTask(task: Task) {
  router.put(tasksRoutes.update(task.id).url, {
    title: task.title,
    description: task.description,
    status: 'em_progresso',
    priority: task.priority,
    due_date: task.due_date,
    task_type_id: task.task_type_id,
  });
}

function markComplete(task: Task) {
  router.put(tasksRoutes.update(task.id).url, {
    title: task.title,
    description: task.description,
    status: 'concluida',
    priority: task.priority,
    due_date: task.due_date,
    task_type_id: task.task_type_id,
  }, { onSuccess: () => router.reload() });
}

function cancelTask(task: Task) {
  router.put(tasksRoutes.update(task.id).url, {
    title: task.title,
    description: task.description,
    status: 'cancelada',
    priority: task.priority,
    due_date: task.due_date,
    task_type_id: task.task_type_id,
  }, { onSuccess: () => router.reload() });
}

function formatDateYmdToDmy(dateStr: string) {
  if (!dateStr) return '-';
  const [year, month, day] = dateStr.split('-');
  return `${day}-${month}-${year}`;
}
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">

     <div class="grid gap-4 grid-cols-[repeat(auto-fit,minmax(340px,1fr))]">

        

        <template v-if="props.inProgressTasks.length">
          <Card
            v-for="task in props.inProgressTasks"
            :key="task.id"
            class="relative min-h-[220px] overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 flex flex-col justify-between"
          >
            <div>
              <h2 class="text-lg font-semibold text-green-700 mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tarefa em Andamento
              </h2>
              <CardTitle class="text-base whitespace-normal mb-1">{{ task.title }}</CardTitle>
            </div>
            <CardFooter class="flex gap-2 items-center mt-2">

              <Button
                type="button"
                v-if="task.status === 'em_progresso'"
                variant="outline"
                @click.prevent="markComplete(task)"
                title="Concluir tarefa"
                class="flex items-center gap-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="hidden lg:inline">Concluir</span>
              </Button>

              <Button
                type="button"
                v-if="task.status !== 'cancelada' && task.status !== 'concluida'"
                variant="destructive"
                @click.prevent="cancelTask(task)"
                title="Cancelar tarefa"
                class="flex items-center gap-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="hidden lg:inline">Cancelar</span>
              </Button>

              <Button asChild variant="ghost" size="sm" title="Ver Detalhes" class="flex items-center gap-1">
                <Link :href="`/tasks/${task.id}`">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                  <span class="hidden lg:inline">Detalhes</span>
                </Link>
              </Button>
            </CardFooter>
          </Card>
        </template>

        <Card v-else class="relative min-h-[220px] overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 flex flex-col justify-between">
          <h2 class="text-green-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Nenhuma tarefa em andamento
          </h2>
          <Button asChild variant="default" size="lg">
            <Link href="/tasks/create">
              Nova Tarefa
            </Link>
          </Button>
        </Card>

        <template v-if="props.upcomingTasks.length">
          <Card
            v-for="task in props.upcomingTasks.slice(0, 3)"
            :key="task.id"
            class="relative min-h-[220px] overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 flex flex-col justify-between"
          >
            <div>
              <h2 class="text-lg font-semibold text-blue-900) mb-2 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 text-blue-900">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
                Próxima Tarefa a Vencer
              </h2>
              <CardTitle class="text-base whitespace-normal mb-1">{{ task.title }}</CardTitle>
              <CardContent class="p-0">
                <p class="text-sm">Vencimento: {{ formatDateYmdToDmy(task.due_date) }}</p>
              </CardContent>
            </div>
            <CardFooter class="flex gap-2 items-center mt-2">
              <Button asChild variant="default" size="sm" class="flex items-center gap-1">
                <Link :href="`/tasks/${task.id}/edit`">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.313l-4.5 1.125 1.125-4.5L16.862 3.487z" />
                  </svg>
                  Editar
                </Link>
              </Button>

             <Button
                type="button"
                v-if="task.status === 'pendente'"
                variant="secondary"
                @click.prevent="startTask(task)"
                title="Iniciar tarefa"
                class="flex items-center gap-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                </svg>
                <span class="hidden sm:inline">Iniciar</span>
              </Button>

            </CardFooter>
          </Card>
        </template>

        <Card v-else class="relative min-h-[220px] overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border p-4 flex flex-col justify-between">
          <h2 class="text-(--flowtodo-blue) flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 text-(--flowtodo-blue)">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
            </svg>
            Nenhuma tarefa a vencer
          </h2>
          <Button asChild variant="default" size="lg">
            <Link href="/tasks/create">
              Nova Tarefa
            </Link>
          </Button>
        </Card>
      </div>

      <div class="relative min-h-screen flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border p-4">
        <PlaceholderPattern />
      </div>
    </div>
  </AppLayout>
</template>
