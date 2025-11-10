<script setup lang="ts">
import { edit, update } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage, useForm } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { ref, reactive } from 'vue';

interface User {
  name: string;
  email: string;
  bio?: string;
  birthday?: string;
  profile_photo_path?: string | null;
  is_admin?: boolean;
  email_verified_at?: string | null;
}

interface ProfileForm {
  name: string;
  email: string;
  bio?: string;
  birthday?: string;
  profile_photo: File | null;
  remove_photo: boolean;
  _method: string;
}

const page = usePage();
const userReactive = reactive<{ user: User }>({
  user: (page.props.auth as { user: User }).user
});

interface Props {
  mustVerifyEmail: boolean;
  status?: string;
}
const props = defineProps<Props>();

const profileForm = useForm<ProfileForm>({
  name: userReactive.user.name || '',
  email: userReactive.user.email || '',
  bio: userReactive.user.bio || '',
  birthday: userReactive.user.birthday || '',
  profile_photo: null,
  remove_photo: false,
  _method: 'PATCH',
});

const previewPhoto = ref<string | null>(null);
const fileInputRef = ref<HTMLInputElement | null>(null);

const breadcrumbItems: BreadcrumbItem[] = [
  {
    title: 'Profile settings',
    href: edit().url,
  },
];

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  if (target.files && target.files.length > 0) {
    const file = target.files[0];
    profileForm.profile_photo = file;
    profileForm.remove_photo = false;

    previewPhoto.value = URL.createObjectURL(file);
  }
}

function removeProfilePhoto() {
  profileForm.profile_photo = null;
  profileForm.remove_photo = true;
  previewPhoto.value = null;
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }

  userReactive.user.profile_photo_path = null;
}

function submit() {
  if (profileForm.remove_photo) {
    profileForm.profile_photo = null;
  }

  profileForm.post(update().url, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: (page) => {
      if (page.props.auth?.user) {
        userReactive.user = { ...page.props.auth.user };
      }
      previewPhoto.value = null;
      profileForm.remove_photo = false;
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
}
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
    <Head title="Configurações de perfil" />

    <SettingsLayout>
      <div class="flex flex-col space-y-6">
        <HeadingSmall
          title="Informações do perfil"
          description="Atualize seu nome e endereço de e-mail"
        />

        <Form
          @submit.prevent="submit"
          enctype="multipart/form-data"
          :disabled="profileForm.processing"
          v-slot="{ errors, recentlySuccessful }"
          class="space-y-6"
        >
          <div class="grid gap-2">
            <Label for="profile_photo">Foto de Perfil</Label>

            <div class="flex items-center gap-4">
              <img
                :src="previewPhoto
                  ? previewPhoto
                  : userReactive.user.profile_photo_path
                    ? `/storage/${userReactive.user.profile_photo_path}`
                    : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userReactive.user.name)"
                alt="Foto de perfil"
                class="h-16 w-16 rounded-full object-cover border"
              />

              <div class="flex flex-col gap-2">
                <Input
                  id="profile_photo"
                  ref="fileInputRef"
                  type="file"
                  name="profile_photo"
                  accept="image/*"
                  @change="onFileChange"
                />

                <Button
                  v-if="userReactive.user.profile_photo_path || profileForm.profile_photo"
                  type="button"
                  variant="ghost"
                  @click="removeProfilePhoto"
                >
                  Remover foto
                </Button>
              </div>
            </div>

            <InputError :message="errors.profile_photo" />
          </div>

          <!-- Outros campos do formulário -->
          <div class="grid gap-2">
            <Label for="name">Name</Label>
            <Input
              id="name"
              class="mt-1 block w-full"
              name="name"
              v-model="profileForm.name"
              required
              autocomplete="name"
              placeholder="Full name"
            />
            <InputError class="mt-2" :message="errors.name" />
          </div>

          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input
              id="email"
              type="email"
              class="mt-1 block w-full"
              name="email"
              v-model="profileForm.email"
              required
              autocomplete="username"
              placeholder="Email address"
            />
            <InputError class="mt-2" :message="errors.email" />
          </div>

          <div class="grid gap-2">
            <Label for="bio">Bio</Label>
            <Input
              id="bio"
              type="text"
              name="bio"
              v-model="profileForm.bio"
              placeholder="Pequena biografia"
            />
            <InputError :message="errors.bio" />
          </div>

          <div class="grid gap-2">
            <Label for="birthday">Data de nascimento</Label>
            <Input
              id="birthday"
              type="date"
              name="birthday"
              v-model="profileForm.birthday"
            />
            <InputError :message="errors.birthday" />
          </div>

          <div v-if="props.mustVerifyEmail && !userReactive.user.email_verified_at">
            <p class="-mt-4 text-sm text-muted-foreground">
              Seu endereço de e-mail não foi verificado.
              <Link
                :href="send()"
                as="button"
                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
              >
                Clique aqui para reenviar o e-mail de verificação.
              </Link>
            </p>

            <div
              v-if="status === 'verification-link-sent'"
              class="mt-2 text-sm font-medium text-green-600"
            >
              Um novo link de verificação foi enviado para o seu endereço de e-mail.
            </div>
          </div>

          <div class="flex items-center gap-4">
            <Button
              :disabled="profileForm.processing"
              type="button"
              @click="submit"
            >
              Salve
            </Button>

            <Transition
              enter-active-class="transition ease-in-out"
              enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out"
              leave-to-class="opacity-0"
            >
              <p
                v-show="recentlySuccessful"
                class="text-sm text-neutral-600"
              >
                Saved.
              </p>
            </Transition>
          </div>
        </Form>
      </div>

      <DeleteUser />
    </SettingsLayout>
  </AppLayout>
</template>
