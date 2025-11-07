<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
</script>

<template>
    <AuthBase
        title="Criar uma conta"
        description="Insira seus dados abaixo para criar sua conta"
    >
        <Head title="Registo" />

        <Form
            v-bind="store.form()"
            enctype="multipart/form-data"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        
                        autocomplete="name"
                        name="name"
                        placeholder="Full name"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        
                        autocomplete="email"
                        name="email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                <Label for="bio">Bio</Label>
                <Input id="bio" type="text" name="bio" placeholder="Pequena biografia" />
                <InputError :message="errors.bio" />
            </div>

            <div class="grid gap-2">
                <Label for="birthday">Data de nascimento</Label>
                <Input id="birthday" type="date" name="birthday" />
                <InputError :message="errors.birthday" />
            </div>

            <div class="grid gap-2">
                <Label for="profile_photo">Foto de Perfil</Label>
                <Input
                    id="profile_photo"
                    type="file"
                    name="profile_photo"
                    accept="image/*"
                />
                <InputError :message="errors.profile_photo" />
            </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        
                        autocomplete="new-password"
                        name="password"
                        placeholder="Password"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirme Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <LoaderCircle
                        v-if="processing"
                        class="h-4 w-4 animate-spin"
                    />
                    Criar uma conta
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                Já tem uma conta?
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                    
                    >Entrar</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
