<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { buttonVariants } from '@/components/ui/button'

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Bem-vindo | FlowToDo">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="min-h-screen bg-linear-to-br from-[#FDFDFC] to-[#e3ffee] flex flex-col items-center justify-center p-6">
        <header class="w-full max-w-6xl flex justify-end gap-4 mb-6">
            <nav class="flex items-center gap-4">
                <Link
                    v-if="$page.props.auth?.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >Dashboard</Link>
                <template v-else>
                    <Link
                        :href="login()"
                        :class="buttonVariants({ variant: 'ghost', size: 'default' })"
                        >
                        Entrar
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        :class="buttonVariants({ variant: 'default', size: 'default' })"
                        >
                        Registo
                    </Link>
                </template>
            </nav>
        </header>
        <!-- Grid principal responsivo -->
        <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-8 w-full max-w-6xl bg-white/70 p-10 rounded-2xl shadow-xl">
            <!-- Bloco 1: Logo + Slogan -->
            <div class="flex flex-col items-center justify-center pb-6">
                <img src="/logo.png" alt="Logo FlowToDo" class="w-24 h-24 mb-2 drop-shadow-lg" />
                <h1 class="text-3xl font-extrabold text-(--flowtodo-blue) flex items-center gap-2 mb-1">
                    FlowToDo
                    <span class="inline-flex items-center justify-center w-7 h-7 bg-(--flowtodo-green) rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" style="stroke: var(--flowtodo-blue-escuro);" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </span>
                </h1>
                <span class="font-semibold text-(--flowtodo-green) text-lg text-center mb-2">Organize sua vida, conquiste seus dias</span>
            </div>
            <!-- Bloco 2: Imagem principal -->
            <div class="flex items-center justify-center pb-6">
                <img src="/image1.png" alt="Organização FlowToDo" class="w-full max-w-xs rounded-xl shadow-xl" />
            </div>
            <!-- Bloco 3: Texto institucional + lista -->
            <div class="flex flex-col justify-center gap-4 pt-6">
                <p class="text-base text-[#434343] mb-2">
                    Descubra um novo jeito de se manter produtivo e organizado. O FlowToDo foi criado para ajudar você a lidar com muitos compromissos sem perder o controle, trazendo leveza e confiança para o seu dia a dia.<br>
                    Gerencie tarefas, defina prioridades e aproveite a sensação de alívio de estar sempre em dia — tudo em uma experiência ágil, bonita e intuitiva.<br>
                    Comece agora e sinta a diferença de ter uma vida organizada ao alcance das mãos!
                </p>
                <ul class="mt-1 space-y-2">
                    <li class="flex items-center gap-2 text-(--flowtodo-green) text-base">
                        <svg width="20" height="20" fill="none"><path d="M20 6L9 17l-5-5" style="stroke: var(--flowtodo-green);" stroke-width="2"/></svg>
                        Organize tarefas em segundos
                    </li>
                    <li class="flex items-center gap-2 text-(--flowtodo-blue) text-base">
                        <svg width="20" height="20" fill="none"><path d="M20 6L9 17l-5-5" style="stroke: var(--flowtodo-blue);" stroke-width="2"/></svg>
                        Interface clara e intuitiva
                    </li>
                    <li class="flex items-center gap-2 text-(--flowtodo-green) text-base">
                        <svg width="20" height="20" fill="none"><path d="M20 6L9 17l-5-5" style="stroke: var(--flowtodo-green);" stroke-width="2"/></svg>
                        Sinta o alívio de estar sempre em dia
                    </li>
                </ul>
                <div class="flex gap-4 mt-4">
                    <Link
                        :href="login()"
                        :class="buttonVariants({ variant: 'ghost', size: 'default' })"
                        >
                        Entrar
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        :class="buttonVariants({ variant: 'default', size: 'default' })"
                        >
                        Registo
                    </Link>

                </div>
            </div>
            <!-- Bloco 4: Imagem extra, ilustração ou mockup -->
            <div class="flex items-center justify-center pt-6">
                <img src="/image2.png" alt="Visual extra FlowToDo" class="w-full max-w-xs rounded-xl shadow-xl" />
            </div>
        </section>
        <footer class="mt-10 text-xs text-(--flowtodo-green) text-center font-medium">
            FlowToDo © 2025 &ndash; Organize suas metas, vença seus dias!
        </footer>
    </div>
</template>
