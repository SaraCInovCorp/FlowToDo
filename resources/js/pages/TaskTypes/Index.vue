<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Link, Head, router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select } from '@/components/ui/select'
import { reactive, ref } from 'vue'

const props = defineProps<{ taskTypes: Array<any> }>()

const form = reactive({
  id: null as number | null,
  name: '',
  description: ''
})

const editing = ref(false)

function resetForm() {
  form.id = null
  form.name = ''
  form.description = ''
  editing.value = false
}

function editTipo(tipo: any) {
  form.id = tipo.id
  form.name = tipo.name
  form.description = tipo.description
  editing.value = true
}

function submitForm() {
  if (editing.value && form.id) {
    router.put(`/task-types/${form.id}`, { name: form.name, description: form.description }, {
      preserveScroll: true,
      onSuccess: () => resetForm()
    })
  } else {
    router.post('/task-types', { name: form.name, description: form.description }, {
      preserveScroll: true,
      onSuccess: () => resetForm()
    })
  }
}

function toggleAtivo(tipo: any) {
  fetch(`/task-types/${tipo.id}/toggle-ativo`, {
    method: 'PATCH',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')!.getAttribute('content') || '',
      'Accept': 'application/json'
    }
  })
  .then(res => {
    if (!res.ok) throw new Error(`Erro HTTP: ${res.status}`)
    return res.json()
  })
  .then(data => {
    tipo.ativo = data.ativo
  })
  .catch(err => {
    console.error('Erro ao ativar/desativar tipo:', err)
    alert('Erro ao alterar status. Tente novamente.')
  })
}

</script>

<template>
  <Head title="Tipos de Tarefa" />
  <AppLayout :breadcrumbs="[{ title: 'Tipos de Tarefa', href: '/task-types' }]">
    <div class="max-w-5xl mx-auto p-4">

      <!-- Formulário criação/edição -->
      <form @submit.prevent="submitForm" class="mb-6 flex flex-col space-y-4 max-w-md">
        <div>
          <Label for="name">Nome</Label>
          <Input id="name" v-model="form.name" placeholder="Nome do tipo" required />
        </div>
        <div>
          <Label for="description">Descrição</Label>
          <Input id="description" v-model="form.description" placeholder="Descrição (opcional)" />
        </div>
        <div class="flex space-x-2">
          <Button type="submit" variant="default" size="sm">
            {{ editing ? 'Atualizar' : 'Criar' }}
          </Button>
          <Button v-if="editing" type="button" variant="ghost" size="sm" @click="resetForm">
            Cancelar
          </Button>
        </div>
      </form>

      <!-- Lista responsiva -->
      <div class="space-y-4">
        <div
          v-for="tipo in taskTypes"
          :key="tipo.id"
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 border rounded shadow-sm hover:shadow-md transition"
        >
          <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <span class="font-semibold text-lg">{{ tipo.name }}</span>
            <span class="text-gray-600">{{ tipo.description || '-' }}</span>
          </div>

          <div class="flex items-center gap-4 mt-2 sm:mt-0">
            <label class="flex items-center space-x-2 cursor-pointer">
              <input
                type="checkbox"
                :checked="tipo.ativo"
                @change="toggleAtivo(tipo)"
                class="cursor-pointer"
              />
              <span class="select-none text-sm">Ativo</span>
            </label>

            <Button variant="link" size="sm" @click="editTipo(tipo)">
              Editar
            </Button>
          </div>
        </div>

        <div v-if="taskTypes.length === 0" class="text-center p-8 text-gray-500">
          Nenhum tipo cadastrado.
        </div>
      </div>
    </div>
  </AppLayout>
</template>
