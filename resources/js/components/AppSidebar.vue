<script setup lang="ts">
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import NavFooter from '@/components/NavFooter.vue';
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
import { LayoutGrid, CheckSquare, CalendarPlus, List, Tag, BookmarkCheck } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

interface AuthUser {
  id: number;
  name: string;
  email: string;
  is_admin?: boolean;
  [key: string]: any;
}

interface TaskType {
  id: number;
  name: string;
  ativo: boolean;
  [key: string]: any;
}


const page = usePage();
const url = page.url;
const { props } = usePage();
const user = props.auth?.user as AuthUser;
const taskTypes = (props.taskTypes ?? []) as TaskType[];

function getDashboardUrl(): string {
  const route = dashboard();
  if (typeof route === 'string') {
    return route;
  }
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
    title: 'Novo Tipo Tarefa',
    href: 'task-types',
    icon: BookmarkCheck,
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
const footerNavItems: NavItem[] = taskTypes
  .filter(tipo => tipo.ativo) 
  .map(tipo => ({
    title: tipo.name,
    href: `/tasks?type=${tipo.id}`,
    icon: Tag,
  }));



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
            <NavFooter :items="footerNavItems" /> 
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>