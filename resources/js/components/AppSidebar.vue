<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
  Sidebar,
  SidebarContent,
  SidebarFooter,
  SidebarHeader,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, CheckSquare, CalendarPlus, List } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

interface AuthUser {
  id: number;
  name: string;
  email: string;
  is_admin?: boolean;
  [key: string]: any;
}

const page = usePage();
const url = page.url;
const { props } = usePage();
const user = props.auth?.user as AuthUser;

// Função para extrair a URL da rota dashboard
function getDashboardUrl(): string {
  const route = dashboard();
  if (typeof route === 'string') {
    return route;
  }
  // ajuste aqui conforme a estrutura do objeto retornado do dashboard(), pode ser .url ou .path
  return (route as any).url || '';
}

const dashboardUrl = getDashboardUrl();

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: dashboardUrl,
    icon: LayoutGrid,
  },
  {
    title: 'Tarefas',
    href: '/tasks',
    icon: CheckSquare,
  },
  {
    title: 'Nova Tarefa',
    href: '/tasks/create',
    icon: CalendarPlus,
  },
];

if (user?.is_admin) {
  mainNavItems.push({
    title: 'Logs',
    href: '/admin/activity-logs',
    icon: List,
  });
}

const isDashboardActive = computed(() => url.startsWith(dashboardUrl));

const filteredNavItems = computed(() =>
  mainNavItems.filter(item => !(item.href === dashboardUrl && isDashboardActive.value))
);
// const footerNavItems: NavItem[] = [
//     {
//         title: 'Logs',
//         href: '/admin/activity-logs', 
//         icon: List,
//     },

// ];


</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="filteredNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
