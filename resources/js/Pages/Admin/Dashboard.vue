<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { 
    Store, 
    Users, 
    Package, 
    Layers, 
    Plus, 
    ExternalLink, 
    ArrowRight,
    CheckCircle2,
    XCircle
} from 'lucide-vue-next';

defineProps({
    stats: Object,
    recentShops: Array,
    recentProducts: Array,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout title="System Overview">
        <div class="space-y-8">
            <!-- Welcome Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-950 p-8 text-white shadow-xl shadow-indigo-950/10">
                <div class="relative z-10 max-w-2xl space-y-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-indigo-200 backdrop-blur-md">
                        Master Administrator Console
                    </span>
                    <h2 class="text-3xl font-extrabold tracking-tight">E-Commerce Management Dashboard</h2>
                    <p class="text-indigo-200 text-sm leading-relaxed">
                        Manage all platform stores, assign shop owners, curate the shared product catalog, and monitor real-time multi-tenant operations.
                    </p>
                    <div class="pt-2 flex flex-wrap gap-3">
                        <Link
                            :href="route('admin.shops.create')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-indigo-950 text-xs font-bold shadow-md hover:bg-indigo-50 transition-colors"
                        >
                            <Plus class="w-4 h-4 text-indigo-600" /> Create Shop
                        </Link>
                        <Link
                            :href="route('admin.products.create')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-700/60 border border-white/20 text-white text-xs font-bold hover:bg-indigo-700 transition-colors"
                        >
                            <Plus class="w-4 h-4" /> Add Catalog Product
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard 
                    title="Total Shops" 
                    :value="stats.total_shops" 
                    :subtitle="`${stats.active_shops} Active Stores`"
                    color-class="bg-indigo-100 text-indigo-600"
                >
                    <template #icon><Store class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Shop Owners" 
                    :value="stats.shop_owners" 
                    subtitle="Registered Merchant Accounts"
                    color-class="bg-violet-100 text-violet-600"
                >
                    <template #icon><Users class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Shared Catalog" 
                    :value="stats.catalog_products" 
                    subtitle="Master Products Available"
                    color-class="bg-sky-100 text-sky-600"
                >
                    <template #icon><Package class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Assigned Listings" 
                    :value="stats.total_assignments" 
                    subtitle="Active In-Shop Products"
                    color-class="bg-emerald-100 text-emerald-600"
                >
                    <template #icon><Layers class="w-6 h-6" /></template>
                </StatCard>
            </div>

            <!-- Two Column Overview: Recent Shops & Recent Products -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Shops -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Registered Shops</h3>
                                <p class="text-xs text-slate-500">Recently onboarded tenant stores</p>
                            </div>
                            <Link 
                                :href="route('admin.shops.index')" 
                                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1"
                            >
                                View All <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>

                        <div class="divide-y divide-slate-100 mt-2">
                            <div 
                                v-for="shop in recentShops" 
                                :key="shop.id"
                                class="py-3.5 flex items-center justify-between gap-4"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <img 
                                        v-if="shop.logo" 
                                        :src="shop.logo" 
                                        :alt="shop.name" 
                                        class="w-10 h-10 rounded-xl object-cover border border-slate-200 flex-shrink-0" 
                                    />
                                    <div v-else class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                                        <Store class="w-5 h-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ shop.name }}</p>
                                        <p class="text-xs text-slate-500 truncate">
                                            Owner: {{ shop.owner?.name || 'Unassigned' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span 
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold"
                                        :class="shop.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                    >
                                        {{ shop.is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <Link 
                                        :href="route('storefront.shop', shop.slug)"
                                        target="_blank"
                                        class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-50"
                                        title="View Storefront"
                                    >
                                        <ExternalLink class="w-4 h-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-4">
                        <Link 
                            :href="route('admin.shops.create')"
                            class="w-full py-2.5 rounded-xl border border-slate-200 text-center block text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            + Add New Store
                        </Link>
                    </div>
                </div>

                <!-- Recent Products in Catalog -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                            <div>
                                <h3 class="font-bold text-slate-900 text-base">Master Product Catalog</h3>
                                <p class="text-xs text-slate-500">Shared catalog products available for all stores</p>
                            </div>
                            <Link 
                                :href="route('admin.products.index')" 
                                class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1"
                            >
                                View All <ArrowRight class="w-3.5 h-3.5" />
                            </Link>
                        </div>

                        <div class="divide-y divide-slate-100 mt-2">
                            <div 
                                v-for="product in recentProducts" 
                                :key="product.id"
                                class="py-3.5 flex items-center justify-between gap-4"
                            >
                                <div class="flex items-center gap-3 min-w-0">
                                    <img 
                                        v-if="product.thumbnail" 
                                        :src="product.thumbnail" 
                                        :alt="product.title" 
                                        class="w-10 h-10 rounded-xl object-cover border border-slate-200 flex-shrink-0" 
                                    />
                                    <div v-else class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 flex-shrink-0">
                                        <Package class="w-5 h-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm truncate">{{ product.title }}</p>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ product.category || 'General' }} • By: {{ product.creator?.name || 'Admin' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <p class="text-sm font-bold text-slate-900">${{ Number(product.price).toFixed(2) }}</p>
                                    <p class="text-[11px] text-slate-400">Base Catalog Price</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 mt-4">
                        <Link 
                            :href="route('admin.products.create')"
                            class="w-full py-2.5 rounded-xl border border-slate-200 text-center block text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            + Add Product to Shared Catalog
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
