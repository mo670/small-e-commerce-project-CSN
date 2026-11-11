<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ShopOwnerLayout from '@/Layouts/ShopOwnerLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import { 
    Layers, 
    BookOpen, 
    AlertTriangle, 
    PlusCircle, 
    ExternalLink, 
    ArrowRight,
    Store,
    CheckCircle2,
    Eye
} from 'lucide-vue-next';

defineProps({
    shop: Object,
    stats: Object,
    recentListings: Array,
});
</script>

<template>
    <Head :title="`${shop.name} - Dashboard`" />

    <ShopOwnerLayout :title="`Shop Dashboard`">
        <div class="space-y-8">
            <!-- Shop Banner Welcome Card -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-violet-900 via-indigo-900 to-slate-900 p-8 text-white shadow-xl">
                <div class="relative z-10 max-w-2xl space-y-3">
                    <div class="flex items-center gap-3">
                        <img 
                            v-if="shop.logo" 
                            :src="shop.logo" 
                            :alt="shop.name" 
                            class="w-12 h-12 rounded-2xl object-cover border-2 border-white/20 shadow-md"
                        />
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/10 text-violet-200">
                                Merchant Portal
                            </span>
                            <h2 class="text-2xl font-extrabold tracking-tight mt-0.5">{{ shop.name }}</h2>
                        </div>
                    </div>

                    <p class="text-slate-300 text-xs leading-relaxed max-w-xl">
                        {{ shop.description || 'Manage your active store listings, browse the shared product catalog to discover items, and customize your shop-specific prices and imagery.' }}
                    </p>

                    <div class="pt-3 flex flex-wrap gap-3">
                        <Link
                            :href="route('shop-owner.catalog.index')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-violet-950 text-xs font-bold shadow-md hover:bg-violet-50 transition-colors"
                        >
                            <BookOpen class="w-4 h-4 text-violet-600" /> Browse Shared Catalog
                        </Link>
                        <Link
                            :href="route('shop-owner.my-products.create')"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-700/60 border border-white/20 text-white text-xs font-bold hover:bg-violet-700 transition-colors"
                        >
                            <PlusCircle class="w-4 h-4" /> Create New Product
                        </Link>
                        <Link
                            :href="route('storefront.shop', shop.slug)"
                            target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/80 border border-white/10 text-white text-xs font-bold hover:bg-slate-800 transition-colors"
                        >
                            <ExternalLink class="w-4 h-4" /> Live Storefront
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <StatCard 
                    title="Products in Shop" 
                    :value="stats.listed_products" 
                    subtitle="Assigned to your storefront"
                    color-class="bg-violet-100 text-violet-600"
                >
                    <template #icon><Layers class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Active Store Listings" 
                    :value="stats.active_listings" 
                    subtitle="Currently visible to customers"
                    color-class="bg-emerald-100 text-emerald-600"
                >
                    <template #icon><CheckCircle2 class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Shared Catalog Ready" 
                    :value="stats.available_in_catalog" 
                    subtitle="Items ready to be added"
                    color-class="bg-sky-100 text-sky-600"
                >
                    <template #icon><BookOpen class="w-6 h-6" /></template>
                </StatCard>

                <StatCard 
                    title="Low Stock Items" 
                    :value="stats.low_stock_count" 
                    subtitle="5 or fewer units left"
                    color-class="bg-amber-100 text-amber-600"
                >
                    <template #icon><AlertTriangle class="w-6 h-6" /></template>
                </StatCard>
            </div>

            <!-- Recent Listings in this Shop -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-xs">
                <div class="flex items-center justify-between pb-5 border-b border-slate-100">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Your Active Store Listings</h3>
                        <p class="text-xs text-slate-500">Products assigned and customized specifically for your storefront</p>
                    </div>
                    <Link 
                        :href="route('shop-owner.my-products.index')" 
                        class="text-xs font-semibold text-violet-600 hover:text-violet-800 flex items-center gap-1"
                    >
                        Manage All <ArrowRight class="w-3.5 h-3.5" />
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                    <div 
                        v-for="item in recentListings" 
                        :key="item.id"
                        class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:border-violet-200 hover:shadow-sm transition-all flex flex-col justify-between"
                    >
                        <div class="flex gap-3">
                            <img 
                                :src="item.thumbnail || item.product?.thumbnail" 
                                :alt="item.product?.title" 
                                class="w-14 h-14 rounded-lg object-cover border border-slate-200 flex-shrink-0" 
                            />
                            <div class="min-w-0">
                                <p class="font-bold text-slate-900 text-sm truncate">{{ item.product?.title }}</p>
                                <p class="text-xs text-slate-500">{{ item.product?.category || 'General' }}</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs font-extrabold text-violet-700">${{ Number(item.price).toFixed(2) }}</span>
                                    <span v-if="Number(item.price) !== Number(item.product?.price)" class="text-[10px] text-slate-400 line-through">
                                        ${{ Number(item.product?.price).toFixed(2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-200/60 flex items-center justify-between text-xs">
                            <span class="text-[11px] font-medium text-slate-500">
                                Stock: <strong class="text-slate-800">{{ item.stock }}</strong>
                            </span>
                            <Link 
                                :href="route('shop-owner.my-products.edit', item.id)"
                                class="text-violet-600 font-semibold hover:underline"
                            >
                                Edit Custom Pricing →
                            </Link>
                        </div>
                    </div>

                    <div v-if="recentListings.length === 0" class="col-span-full py-8 text-center text-slate-400">
                        <Layers class="w-8 h-8 mx-auto mb-2 text-slate-300" />
                        <p class="text-sm">You haven't listed any products yet.</p>
                        <Link 
                            :href="route('shop-owner.catalog.index')" 
                            class="inline-block mt-3 px-4 py-2 bg-violet-600 text-white rounded-xl text-xs font-bold"
                        >
                            Browse Shared Catalog
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </ShopOwnerLayout>
</template>

