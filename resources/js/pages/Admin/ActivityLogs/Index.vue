<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select } from '@/components/ui/select'
import {
  Card,
  CardHeader,
  CardTitle,
  CardContent,
  CardFooter
} from '@/components/ui/card'
import { reactive } from 'vue'
import { route } from 'ziggy-js'


const props = defineProps<{
  logs: { data: Array<any>; links: Array<any> }
  filters: { log_name?: string; event?: string; causer_id?: string }
}>()

const filters = reactive({
  log_name: props.filters.log_name || '',
  event: props.filters.event || '',
  causer_id: props.filters.causer_id || ''
})

function resetFilters() {
  filters.log_name = ''
  filters.event = ''
  filters.causer_id = ''
  router.get(route('admin.activity-logs.index'), {}, { preserveState: true })
}

function applyFilters() {
  router.get(
    route('admin.activity-logs.index'),
    {
      log_name: filters.log_name || undefined,
      event: filters.event || undefined,
      causer_id: filters.causer_id || undefined
    },
    { preserveState: true }
  )
}

function sanitizePaginationLabel(label: string) {
  if (label.includes('pagination.previous')) {
    return '&laquo; Anterior'
  } else if (label.includes('pagination.next')) {
    return 'Próximo &raquo;'
  }
  return label
}
</script>

<template>
  <Head title="Logs de Atividade" />
  <AppLayout :breadcrumbs="[{ title: 'Logs de Atividade', href: route('admin.activity-logs.index') }]">
    <div class="max-w-7xl mx-auto p-4">
      <h2 class="font-semibold m-4">Filtrar logs</h2>
      <form @submit.prevent="applyFilters" class="mb-6 flex flex-wrap gap-4 items-end">
        <div class="flex flex-col">
          <Label for="filter-logname">Nome do Log</Label>
          <Input
            id="filter-logname"
            type="text"
            v-model="filters.log_name"
            placeholder="Filtrar por log_name"
          />
        </div>

        <div class="flex flex-col">
          <Label for="filter-event">Evento</Label>
          <Select id="filter-event" v-model="filters.event">
            <option value="">Todos</option>
            <option value="created">created</option>
            <option value="updated">updated</option>
            <option value="deleted">deleted</option>
            <!-- adicione outros eventos conforme necessário -->
          </Select>
        </div>

        <div class="flex flex-col">
          <Label for="filter-causer">ID Usuário</Label>
          <Input
            id="filter-causer"
            type="text"
            v-model="filters.causer_id"
            placeholder="Filtrar por ID do usuário"
          />
        </div>

        <Button type="submit" variant="default" size="sm">Filtrar</Button>
        <Button type="button" variant="ghost" size="sm" @click="resetFilters">Limpar Filtros</Button>
      </form>

      <div class="grid gap-4 grid-cols-1 sm:grid-cols-2 md:grid-cols-3">
        <Card v-for="log in props.logs.data" :key="log.id" class="p-4 flex flex-col justify-between">
            <CardHeader>
                <CardTitle class="text-sm font-medium break-all">{{ log.log_name || 'Sem nome' }}</CardTitle>
                <div class="text-xs text-gray-500">{{ log.event || 'N/A' }}</div>
            </CardHeader>

            <CardContent class="2 grow text-xs whitespace-pre-wrap">
                <p>{{ log.description }}</p>
                <div v-if="log.properties">
                  <p><strong>IP:</strong> {{ log.properties.ip }}</p>
                  <p><strong>URL:</strong> {{ log.properties.url }}</p>
                  <p><strong>Método:</strong> {{ log.properties.method }}</p>
                  <p><strong>User Agent:</strong> {{ log.properties.user_agent }}</p>
                </div>
            </CardContent>

            <CardFooter class="mt-3 text-gray-600 text-xs flex justify-between">
                <span>Usuário: {{ log.causer ? log.causer.name : 'Sistema' }}</span>
                <span>Data: {{ new Date(log.created_at).toLocaleString() }}</span>
            </CardFooter>
        </Card>

      </div>

      <nav class="flex justify-center mt-8" v-if="props.logs.links.length > 3">
        <Link
          v-for="(link, i) in props.logs.links"
          :key="i"
          :href="link.url || ''"
          v-html="sanitizePaginationLabel(link.label)"
          class="mx-1 px-3 py-1 rounded cursor-pointer"
          :class="{
            'bg-blue-600 text-white': link.active,
            'text-gray-500 hover:bg-gray-100': !link.active,
            'pointer-events-none opacity-50': !link.url
          }"
          preserve-scroll
        />
      </nav>
    </div>
  </AppLayout>
</template>
