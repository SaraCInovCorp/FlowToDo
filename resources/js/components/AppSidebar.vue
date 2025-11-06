<script setup lang="ts">
// import NavFooter from '@/components/NavFooter.vue';
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
import { LayoutGrid, CheckSquare, CalendarPlus, List   } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

interface AuthUser {
  id: number
  name: string
  email: string
  is_admin?: boolean
  [key: string]: any
}

const { props } = usePage();
const user = props.auth?.user as AuthUser;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
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

console.log(user);

if (user?.is_admin) {
    mainNavItems.push({
        title: 'Logs',
        href: '/admin/activity-logs', 
        icon: List,
    });
}

if (user?.is_admin) {

const footerNavItems: NavItem[] = [
    {
        title: 'Logs',
        href: '/admin/activity-logs', 
        icon: List,
    },

];

}
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
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
