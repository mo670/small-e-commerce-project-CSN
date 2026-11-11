<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    ShoppingBag, 
    Store, 
    Search, 
    User, 
    LayoutDashboard, 
    Menu, 
    X, 
    ExternalLink,
    ShieldCheck
} from 'lucide-vue-next';
import Toast from '@/Components/Toast.vue';

const page = usePage();
const user = page.props.auth?.user;
const mobileMenuOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col font-sans text-slate-800">
        <Toast />

        <!-- Notification Banner -->
        <div class="bg-slate-900 text-white text-xs py-2 px-4 text-center font-medium">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Multi-Tenant E-Commerce Management System • Powered by Laravel & Vue.js
            </span>
        </div>

        <!-- Main Storefront Header -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Brand -->
                    <div class="flex items-center gap-8">
                        <Link :href="route('home')" class="flex items-center gap-3 group">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 flex items-center justify-center text-white shadow-md shadow-indigo-100 group-hover:scale-105 transition-transform">
                                <ShoppingBag class="w-6 h-6" />
                            </div>
                            <div>
                                <span class="font-extrabold text-xl tracking-tight text-slate-900">ShopSphere</span>
                                <span class="block text-[11px] font-semibold text-indigo-600 tracking-wider uppercase -mt-1">Marketplace</span>
                            </div>
                        </Link>

                        <!-- Nav items (Desktop) -->
                        <nav class="hidden md:flex items-center gap-6">
                            <Link 
                                :href="route('home')" 
                                class="text-sm font-semibold transition-colors"
                                :class="route().current('home') ? 'text-indigo-600' : 'text-slate-600 hover:text-slate-900'"
                            >
                                All Shops
                            </Link>
                        </nav>
                    </div>

                    <!-- Right Controls (Desktop) -->
                    <div class="hidden md:flex items-center gap-4">
                        <template v-if="user">
                            <Link
                                v-if="user.role === 'admin'"
                                :href="route('admin.dashboard')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700 transition-colors"
                            >
                                <ShieldCheck class="w-4 h-4" />
                                <span>Admin Dashboard</span>
                            </Link>
                            <Link
                                v-else-if="user.role === 'shop_owner'"
                                :href="route('shop-owner.dashboard')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-violet-600 text-white text-sm font-semibold shadow-sm hover:bg-violet-700 transition-colors"
                            >
                                <Store class="w-4 h-4" />
                                <span>Shop Owner Panel</span>
                            </Link>
                            <Link
                                v-else
                                :href="route('dashboard')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-sm hover:bg-slate-800 transition-colors"
                            >
                                <LayoutDashboard class="w-4 h-4" />
                                <span>Dashboard</span>
                            </Link>
                        </template>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-sm font-semibold text-slate-700 hover:text-indigo-600 px-3 py-2 transition-colors"
                            >
                                Log in
                            </Link>
                            <Link
                                :href="route('register')"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700 transition-colors"
                            >
                                Register
                            </Link>
                        </template>
                    </div>

                    <!-- Mobile Menu Trigger -->
                    <div class="flex md:hidden">
                        <button
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                        >
                            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
                            <X v-else class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileMenuOpen" class="md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-6 space-y-3">
                <Link
                    :href="route('home')"
                    class="block px-3 py-2 rounded-lg text-base font-medium text-slate-700 hover:bg-slate-50"
                >
                    All Shops Directory
                </Link>

                <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                    <template v-if="user">
                        <Link
                            :href="route('dashboard')"
                            class="w-full text-center py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                        >
                            Go to Dashboard ({{ user.role }})
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="w-full text-center py-2 rounded-xl border border-slate-200 font-semibold text-sm text-slate-700"
                        >
                            Log in
                        </Link>
                        <Link
                            :href="route('register')"
                            class="w-full text-center py-2 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                        >
                            Register
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Main Slot -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-sm">
                            <ShoppingBag class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 text-base">ShopSphere E-Commerce</span>
                            <p class="text-xs text-slate-500">Shared Catalog & Multi-Shop Management System</p>
                        </div>
                    </div>

                    <div class="text-xs text-slate-500 text-center md:text-right">
                        <p>© 2026 ShopSphere System. Designed for Enterprise Evaluation.</p>
                        <p class="mt-1">Candidate Task: Small E-commerce Management</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
