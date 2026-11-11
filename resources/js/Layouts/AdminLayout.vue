<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    LayoutDashboard, 
    Store, 
    Users, 
    Package, 
    ExternalLink, 
    LogOut, 
    Menu, 
    X, 
    ShieldCheck, 
    ChevronRight,
    ShoppingBag
} from 'lucide-vue-next';
import Toast from '@/Components/Toast.vue';

defineProps({
    title: {
        type: String,
        default: 'Admin Panel',
    },
});

const page = usePage();
const user = page.props.auth.user;
const sidebarOpen = ref(false);

const navigation = [
    { name: 'Dashboard', href: route('admin.dashboard'), icon: LayoutDashboard, routeName: 'admin.dashboard' },
    { name: 'Shops', href: route('admin.shops.index'), icon: Store, routeName: 'admin.shops.*' },
    { name: 'Shop Owners', href: route('admin.shop-owners.index'), icon: Users, routeName: 'admin.shop-owners.*' },
    { name: 'Shared Catalog', href: route('admin.products.index'), icon: Package, routeName: 'admin.products.*' },
];
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <Toast />

        <!-- Mobile sidebar backdrop -->
        <div 
            v-if="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-gray-900/50 backdrop-blur-sm lg:hidden"
        />

        <!-- Sidebar -->
        <aside 
            class="fixed lg:static inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200/80 flex flex-col transition-transform duration-200 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo area -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-slate-100">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                        <ShoppingBag class="w-5 h-5" />
                    </div>
                    <div>
                        <span class="font-bold text-gray-900 text-lg leading-tight block">ShopSphere</span>
                        <span class="text-[11px] font-semibold text-indigo-600 tracking-wider uppercase flex items-center gap-1">
                            <ShieldCheck class="w-3 h-3" /> Admin Portal
                        </span>
                    </div>
                </Link>

                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">Main Menu</div>
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl font-medium text-sm transition-all"
                    :class="route().current(item.routeName) 
                        ? 'bg-indigo-50 text-indigo-700 font-semibold shadow-sm' 
                        : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70'"
                >
                    <div class="flex items-center gap-3">
                        <component 
                            :is="item.icon" 
                            class="w-5 h-5 transition-colors"
                            :class="route().current(item.routeName) ? 'text-indigo-600' : 'text-slate-400'"
                        />
                        <span>{{ item.name }}</span>
                    </div>
                    <ChevronRight 
                        class="w-4 h-4 opacity-0 transition-opacity"
                        :class="{ 'opacity-100 text-indigo-500': route().current(item.routeName) }"
                    />
                </Link>

                <div class="pt-6 px-3 pb-2 text-[11px] font-bold uppercase tracking-wider text-slate-400">External View</div>
                <Link
                    :href="route('home')"
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 transition-all"
                >
                    <ExternalLink class="w-5 h-5 text-slate-400" />
                    <span>Public Storefront</span>
                </Link>
            </div>

            <!-- User profile footer -->
            <div class="p-4 border-t border-slate-100">
                <div class="p-3 rounded-xl bg-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center flex-shrink-0 text-sm">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ user.name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                        </div>
                    </div>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                        title="Sign Out"
                    >
                        <LogOut class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-20 bg-white border-b border-slate-200/80 px-6 lg:px-8 flex items-center justify-between flex-shrink-0 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button 
                        @click="sidebarOpen = true" 
                        class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100"
                    >
                        <Menu class="w-6 h-6" />
                    </button>
                    <h1 class="text-xl font-bold text-slate-800 tracking-tight">{{ title }}</h1>
                </div>

                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                        Admin Mode
                    </span>
                    <Link
                        :href="route('home')"
                        target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        <ExternalLink class="w-3.5 h-3.5" /> View Store
                    </Link>
                </div>
            </header>

            <!-- Page Body -->
            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
