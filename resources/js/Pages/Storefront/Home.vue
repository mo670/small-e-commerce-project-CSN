<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    Store, 
    ArrowRight, 
    Layers, 
    Sparkles, 
    ShieldCheck, 
    ShoppingBag,
    CheckCircle2
} from 'lucide-vue-next';

defineProps({
    shops: Object,
    featuredShops: Array,
});
</script>

<template>
    <Head title="Explore Shops - ShopSphere Marketplace" />

    <StorefrontLayout>
        <div class="space-y-16 py-8">
            <!-- Hero Section -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900 text-white p-8 sm:p-12 lg:p-16 shadow-2xl">
                    <div class="relative z-10 max-w-2xl space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-semibold backdrop-blur-md">
                            <Sparkles class="w-3.5 h-3.5" />
                            Multi-Tenant Shared Catalog Platform
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                            Discover Curated Independent Stores
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                            Each store offers hand-selected products from our shared catalog with exclusive shop-specific pricing, custom bundles, and personalized service.
                        </p>
                        <div class="pt-4 flex flex-wrap gap-4 text-xs text-slate-400 font-medium">
                            <span class="flex items-center gap-1.5">
                                <CheckCircle2 class="w-4 h-4 text-emerald-400" /> Verified Merchants
                            </span>
                            <span class="flex items-center gap-1.5">
                                <CheckCircle2 class="w-4 h-4 text-emerald-400" /> Exclusive Shop Pricing
                            </span>
                            <span class="flex items-center gap-1.5">
                                <CheckCircle2 class="w-4 h-4 text-emerald-400" /> Shared Catalog Architecture
                            </span>
                        </div>
                    </div>

                    <!-- Decorative elements -->
                    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -top-20 right-1/3 w-72 h-72 bg-violet-600/10 rounded-full blur-2xl pointer-events-none"></div>
                </div>
            </div>

            <!-- Shops Directory -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 border-b border-slate-200 pb-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Merchant Directory</span>
                        <h2 class="text-2xl font-black text-slate-900 mt-1">Browse Available Stores</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Explore each shop’s custom catalogue and exclusive offerings.</p>
                    </div>

                    <div class="text-xs font-semibold text-slate-500">
                        {{ shops.total }} active {{ shops.total === 1 ? 'store' : 'stores' }} online
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="shop in shops.data"
                        :key="shop.id"
                        :href="route('storefront.shop', shop.slug)"
                        class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:border-indigo-300 transition-all flex flex-col justify-between"
                    >
                        <div>
                            <!-- Banner -->
                            <div class="h-32 bg-slate-100 overflow-hidden relative">
                                <img
                                    v-if="shop.banner"
                                    :src="shop.banner"
                                    :alt="shop.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div v-else class="w-full h-full bg-gradient-to-r from-slate-200 to-indigo-100 flex items-center justify-center text-slate-400">
                                    <Store class="w-8 h-8 opacity-40" />
                                </div>
                            </div>

                            <!-- Shop Info -->
                            <div class="p-6 relative pt-0">
                                <!-- Logo Floating -->
                                <div class="-mt-8 mb-3 flex items-end justify-between">
                                    <img
                                        v-if="shop.logo"
                                        :src="shop.logo"
                                        :alt="shop.name"
                                        class="w-16 h-16 rounded-2xl object-cover border-4 border-white shadow-md bg-white flex-shrink-0"
                                    />
                                    <div v-else class="w-16 h-16 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-xl border-4 border-white shadow-md flex-shrink-0">
                                        {{ shop.name.charAt(0) }}
                                    </div>

                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                        <Layers class="w-3.5 h-3.5" />
                                        {{ shop.shop_products_count || 0 }} Products
                                    </span>
                                </div>

                                <h3 class="font-bold text-slate-900 text-lg group-hover:text-indigo-600 transition-colors">
                                    {{ shop.name }}
                                </h3>
                                <p class="text-xs text-slate-500 line-clamp-2 mt-2 leading-relaxed">
                                    {{ shop.description || 'Welcome to our store! Browse our featured products and special pricing.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-indigo-600 group-hover:text-indigo-700">
                            <span>Visit Storefront</span>
                            <ArrowRight class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
                        </div>
                    </Link>

                    <div v-if="shops.data.length === 0" class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                        <Store class="w-12 h-12 mx-auto mb-2 text-slate-300 stroke-1" />
                        <p class="text-sm">No active stores available currently.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <Pagination :links="shops.links" />
            </div>

            <!-- Architecture Info Callout -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="p-8 rounded-3xl bg-white border border-slate-200/80 shadow-xs grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                            1
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm">Shared Master Catalog</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Admins and merchants curate a universal product catalog accessible across the entire system.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center font-bold">
                            2
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm">Shop-Specific Customization</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Shop owners override catalog prices, provide custom descriptions, and upload unique thumbnails for their stores.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                            3
                        </div>
                        <h4 class="font-bold text-slate-900 text-sm">Filtered Customer View</h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Customers only see products strictly assigned to the shop they visit. Unassigned products remain safely hidden.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </StorefrontLayout>
</template>
