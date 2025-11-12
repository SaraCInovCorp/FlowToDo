<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head, router } from '@inertiajs/vue3'
import tasksRoutes from '@/routes/tasks'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select } from '@/components/ui/select'
import {
  Card,
  CardTitle,
  CardDescription,
  CardContent,
  CardFooter
} from '@/components/ui/card'
import { reactive } from 'vue'

const props = defineProps<{ tasks: { data: Array<any>, links: Array<any> }, taskTypes: Array<any> }>()

function resumeDescription(desc: string): string {
  if (!desc) return 'Sem descrição'
  return desc.length > 70 ? desc.substring(0, 70) + '...' : desc
}

function startTask(task: any) {
  router.put(tasksRoutes.update(task.id).url, { ...task, status: 'em_progresso' })
}
function markComplete(task: any) {
  router.put(tasksRoutes.update(task.id).url, { ...task, status: 'concluida' })
}
function cancelTask(task: any) {
  router.put(tasksRoutes.update(task.id).url, { ...task, status: 'cancelada' })
}
function undoComplete(task: any) {
  router.put(tasksRoutes.update(task.id).url, { ...task, status: 'pendente' })
}
function undoCancel(task: any) {
  router.put(tasksRoutes.update(task.id).url, { ...task, status: 'pendente' })
}

function formatDateYmdToDmy(dateStr: string): string {
  if (!dateStr) return '-'
  const [year, month, day] = dateStr.split('-')
  if (!year || !month || !day) return dateStr
  return `${day}-${month}-${year}`
}

const filters = reactive({
  name: '',
  status: 'todas',
  priority: 'todas',
  due_date: '',
  task_type_id: 'todas',
})

function resetFilters() {
  filters.name = ''
  filters.status = 'todas'
  filters.priority = 'todas'
  filters.due_date = ''
  filters.task_type_id = 'todas'
  router.get(tasksRoutes.index().url, {}, { preserveState: true })
}

function applyFilters() {
  router.get(tasksRoutes.index().url, {
    name: filters.name || undefined,
    status: filters.status !== 'todas' ? filters.status : undefined,
    priority: filters.priority !== 'todas' ? filters.priority : undefined,
    due_date: filters.due_date || undefined,
    task_type_id: filters.task_type_id !== 'todas' ? filters.task_type_id : undefined,
  }, { preserveState: true })
}

function sanitizePaginationLabel(label: string) {
  if (label.includes('pagination.previous')) {
    return '&laquo; Back'
  } else if (label.includes('pagination.next')) {
    return 'Next &raquo;'
  }
  return label
}
</script>

<template>
  <Head title="Minhas Tarefas" />
  <AppLayout :breadcrumbs="[{ title: 'Minhas Tarefas', href: tasksRoutes.index().url }]">
    <div class="max-w-6xl mx-auto p-4">
      <div class="flex justify-between mb-6 items-center">
        <h2 class="font-semibold">Pesquisar tarefas</h2>
        <Button asChild variant="default" size="lg">
          <Link :href="tasksRoutes.create().url">
            Nova Tarefa
          </Link>
        </Button>
      </div>

      <form @submit.prevent="applyFilters" class="mb-4 flex flex-wrap gap-4 items-center">
        <div class="flex flex-col">
          <Label for="filter-name">Nome</Label>
          <Input id="filter-name" v-model="filters.name" placeholder="Filtrar por nome" />
        </div>

        <div class="flex flex-col">
          <Label for="filter-status">Status</Label>
          <Select id="filter-status" v-model="filters.status">
            <option value="todas">Todas</option>
            <option value="pendente">Pendente</option>
            <option value="em_progresso">Em progresso</option>
            <option value="concluida">Concluída</option>
            <option value="cancelada">Cancelada</option>
            <option value="arquivada">Arquivada</option>
          </Select>
        </div>

        <div class="flex flex-col">
          <Label for="filter-priority">Prioridade</Label>
          <Select id="filter-priority" v-model="filters.priority">
            <option value="todas">Todas</option>
            <option value="alta">Alta</option>
            <option value="media">Média</option>
            <option value="baixa">Baixa</option>
          </Select>
        </div>

        <div class="flex flex-col">
          <Label for="filter-due_date">Data de Vencimento</Label>
          <Input id="filter-due_date" type="date" v-model="filters.due_date" />
        </div>

        <div class="flex flex-col">
          <Label for="filter-task-type">Tipo de Tarefa</Label>
          <Select id="filter-task-type" v-model="filters.task_type_id">
            <option value="todas">Todas</option>
            <option v-for="type in props.taskTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
          </Select>
        </div>


        <Button type="submit" variant="default" size="sm" class="self-end">
          Filtrar
        </Button>
        <Button type="button" variant="ghost" size="sm" class="self-end" @click="resetFilters">
          Limpar filtros
        </Button>
      </form>

      <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
        <Card v-for="task in props.tasks.data" :key="task.id" class="p-4 h-full flex flex-col justify-between">
          <div class="mb-2 min-w-0">
            <CardTitle class="text-base whitespace-normal">{{ task.title }}</CardTitle>
            <CardDescription>{{ resumeDescription(task.description) }}</CardDescription>
          </div>

          <CardContent class="flex flex-col gap-1 pt-1 px-2">
            <span class="text-xs text-gray-500"><span class="font-bold">Status:</span> {{ task.status }}</span>
            <span class="text-xs text-gray-500"><span class="font-bold">Prioridade:</span> {{ task.priority }}</span>
            <span class="text-xs text-gray-500"><span class="font-bold">Vencimento:</span> {{ formatDateYmdToDmy(task.due_date) }}</span>
            <span class="text-xs text-gray-500">
            <span class="font-bold">Tipo:</span> {{ task.task_type?.name || '-' }}</span>
          </CardContent>

          <CardFooter class="mt-3 flex justify-between items-center gap-2">
            <div class="flex gap-2 items-center">
              <Button v-if="task.status !== 'concluida'" asChild variant="default" size="sm">
                <Link :href="tasksRoutes.edit(task.id).url">
                  Editar
                </Link>
              </Button>

              <Link
                v-if="task.status === 'pendente'"
                @click.prevent="startTask(task)"
                title="Iniciar tarefa"
                class="flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.91 11.672a.375.375 0 0 1 0 .656l-5.603 3.113a.375.375 0 0 1-.557-.328V8.887c0-.286.307-.466.557-.327l5.603 3.112Z" />
                </svg>
                <span class="sr-only">Iniciar</span>
              </Link>

              <Link
                v-if="task.status === 'em_progresso'"
                @click.prevent="markComplete(task)"
                title="Concluir tarefa"
                class="flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="sr-only">Concluir</span>
              </Link>

              <Link
                v-if="task.status === 'concluida'"
                @click.prevent="undoComplete(task)"
                title="Desfazer conclusão"
                class="flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-900">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="sr-only">Desfazer conclusão</span>
              </Link>

              <Link
                v-if="task.status !== 'cancelada' && task.status !== 'concluida'"
                @click.prevent="cancelTask(task)"
                title="Cancelar tarefa"
                class="flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-orange-400">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="sr-only">Cancelar</span>
              </Link>

              <Link
                v-if="task.status === 'cancelada'"
                @click.prevent="undoCancel(task)"
                title="Remover cancelamento"
                class="flex items-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="sr-only">Remover cancelamento</span>
              </Link>
            </div>
            <Button asChild variant="ghost" size="sm">
              <Link :href="tasksRoutes.show(task.id).url">Ver detalhes</Link>
            </Button>
          </CardFooter>
        </Card>
      </div>

      <nav class="flex justify-center mt-8" v-if="props.tasks.links.length > 3">
        <Link
          v-for="(link, i) in props.tasks.links"
          :key="i"
          :href="link.url || ''"
          class="mx-1 px-2 py-1 rounded"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-500 hover:bg-gray-100': !link.active,
            'pointer-events-none opacity-50': !link.url
          }"
          preserve-scroll
        >
          {{ sanitizePaginationLabel(link.label) }}
        </Link>
      </nav>
    </div>
  </AppLayout>
</template>
