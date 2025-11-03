<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { Button } from '@/components/ui/button'

const props = defineProps<{ task?: any }>()

const form = ref({
  title: props.task.title || '',
  description: props.task.description || '',
  status: props.task.status || 'pendente',
  priority: props.task.priority || 'media',
  due_date: props.task.due_date || '',
})

watch(() => props.task, (newTask) => {
  form.value = {
    title: newTask.title,
    description: newTask.description,
    status: newTask.status,
    priority: newTask.priority,
    due_date: newTask.due_date,
  }
})

function submit() {
  router.put(`/tasks/${props.task.id}`, form.value)
}

function goBack() {
  window.history.back()
}

const breadcrumbs = [
  { title: 'Tarefas', href: '/tasks' },
  { title: 'Editar Tarefa' },
]
</script>

<template>
  <Head title="Editar Tarefa" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <form @submit.prevent="submit" class="max-w-lg mx-auto p-4 grid grid-cols-1 gap-4 md:grid-cols-2">
      <div class="md:col-span-2">
        <label class="font-medium mb-1" for="title">Título</label>
        <input v-model="form.title" id="title" type="text" class="w-full border rounded px-3 py-2" required />
      </div>

      <div class="md:col-span-2">
        <label class="font-medium mb-1" for="description">Descrição</label>
        <textarea v-model="form.description" id="description" rows="4" class="w-full border rounded px-3 py-2"></textarea>
      </div>

      <div>
        <label class="font-medium mb-1" for="status">Status</label>
        <select v-model="form.status" id="status" class="w-full border rounded px-3 py-2">
          <option value="pendente">Pendente</option>
          <option value="em_progresso">Em Progresso</option>
          <option value="concluida">Concluída</option>
          <option value="cancelada">Cancelada</option>
          <option value="arquivada">Arquivada</option>
        </select>
      </div>

      <div>
        <label class="font-medium mb-1" for="priority">Prioridade</label>
        <select v-model="form.priority" id="priority" class="w-full border rounded px-3 py-2">
          <option value="alta">Alta</option>
          <option value="media">Média</option>
          <option value="baixa">Baixa</option>
        </select>
      </div>

      <div>
        <label class="font-medium mb-1" for="due_date">Data de Vencimento</label>
        <input v-model="form.due_date" id="due_date" type="date" class="w-full border rounded px-3 py-2" />
      </div>

      <div class="md:col-span-2 flex justify-end gap-2">
        <Button type="button" variant="ghost" size="lg" @click="goBack">Cancelar</Button>
        <Button type="submit" variant="default" size="lg">Salvar</Button>
      </div>
    </form>
  </AppLayout>
</template>
