<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'

const { task } = defineProps<{ task?: Record<string, any> }>()

const breadcrumbs = [
  { title: 'Tarefas', href: '/tasks' },
  { title: 'Visualizar Tarefa' },
]

function formatDateYmdToDmy(dateStr: string): string {
  if (!dateStr) return '-'
  const [year, month, day] = dateStr.split('-')
  if (!year || !month || !day) return dateStr
  return `${day}-${month}-${year}`
}

function goBack() {
  window.history.back()
}
</script>

<template>
  <Head title="Visualizar Tarefa" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="max-w-lg mx-auto p-4 space-y-4 border rounded shadow-md">
      <h2 class="text-2xl font-bold">{{ task?.title || '-' }}</h2>
      <p>{{ task?.description || 'Nenhuma descrição' }}</p>

      <p><strong>Status:</strong> {{ task?.status || '-' }}</p>
      <p><strong>Prioridade:</strong> {{ task?.priority || '-' }}</p>
      <p><strong>Vencimento:</strong> {{ formatDateYmdToDmy(task?.due_date) }}</p>
      <p><strong>Tipo de Tarefa:</strong> {{ task?.task_type?.name || '-' }}</p>
      <p v-if="task?.task_type?.description">
        <strong>Descrição do Tipo:</strong> {{ task.task_type.description }}
      </p>

      <div class="flex justify-end gap-4 mt-6">
        <Button variant="ghost" size="lg" type="button" @click="goBack">
          Voltar
        </Button>
        <Button asChild variant="default" size="lg">
          <Link :href="`/tasks/${task?.id}/edit`">
            Editar
          </Link>
        </Button>
      </div>
    </div>
  </AppLayout>
</template>
