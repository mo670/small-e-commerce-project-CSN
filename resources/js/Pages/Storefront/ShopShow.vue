<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    Store, 
    Search, 
    Layers, 
    MapPin, 
    Phone, 
    Mail, 
    ArrowLeft, 
    ShoppingBag, 
    Tag,
    ChevronDown,
    ShieldCheck
} from 'lucide-vue-next';

const props = defineProps({
    shop: Object,
    shopProducts: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const sort = ref(props.filters.sort || 'latest');

const applyFilters = () => {
    router.get(
        route('storefront.shop', props.shop.slug),
        { search: search.value, category: category.value, sort: sort.value },
        { preserveState: true, replace: true }
    );
};
</script>

<template>
    <Head :title="`${shop.name} - Storefront`" />

    <StorefrontLayout>
        <div class="space-y-8 py-6">
            <!-- Shop Banner & Header -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Link -->
                <div class="mb-4">
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors"
                    >
                        <ArrowLeft class="w-4 h-4" /> Back to All Shops
                    </Link>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs">
                    <!-- Banner -->
                    <div class="h-44 sm:h-64 bg-slate-100 overflow-hidden relative">
                        <img
                            v-if="shop.banner"
                            :src="shop.banner"
                            :alt="shop.name"
                            class="w-full h-full object-cover"
                        />
                        <div v-else class="w-full h-full bg-gradient-to-r from-slate-800 to-indigo-900 flex items-center justify-center text-white/30">
                            <Store class="w-16 h-16" />
                        </div>
                    </div>

                    <!-- Shop Info Bar -->
                    <div class="p-6 sm:p-8 relative pt-0">
                        <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-6 -mt-12 sm:-mt-16 mb-6">
                            <div class="flex items-end gap-4">
                                <img
                                    v-if="shop.logo"
                                    :src="shop.logo"
                                    :alt="shop.name"
                                    class="w-24 h-24 rounded-2xl object-cover border-4 border-white shadow-lg bg-white flex-shrink-0"
                                />
                                <div v-else class="w-24 h-24 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold text-3xl border-4 border-white shadow-lg flex-shrink-0">
                                    {{ shop.name.charAt(0) }}
                                </div>

                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ shop.name }}</h1>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            Verified Store
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-500 max-w-xl line-clamp-2">
                                        {{ shop.description || 'Welcome to our shop! Check out our assigned catalog products.' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Contact Meta -->
                            <div class="flex flex-wrap gap-4 text-xs text-slate-500 font-medium">
                                <span v-if="shop.address" class="flex items-center gap-1.5">
                                    <MapPin class="w-4 h-4 text-slate-400" /> {{ shop.address }}
                                </span>
                                <span v-if="shop.phone" class="flex items-center gap-1.5">
                                    <Phone class="w-4 h-4 text-slate-400" /> {{ shop.phone }}
                                </span>
                                <span v-if="shop.email" class="flex items-center gap-1.5">
                                    <Mail class="w-4 h-4 text-slate-400" /> {{ shop.email }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shop Inventory & Filters -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <!-- Search & Filters -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-3 items-center justify-between">
                    <div class="relative w-full md:w-80">
                        <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                        <input
                            v-model="search"
                            @keyup.enter="applyFilters"
                            type="text"
                            :placeholder="`Search products in ${shop.name}...`"
                            class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5 w-full md:w-auto">
                        <!-- Category Filter -->
                        <select
                            v-model="category"
                            @change="applyFilters"
                            class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="">All Categories</option>
                            <option v-for="cat in categories" :key="cat" :value="cat">
                                {{ cat }}
                            </option>
                        </select>

                        <!-- Sort Filter -->
                        <select
                            v-model="sort"
                            @change="applyFilters"
                            class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="latest">Sort: Newest</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>

                        <button
                            @click="applyFilters"
                            class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                        >
                            Apply
                        </button>
                    </div>
                </div>

                <!-- Product Grid (Assigned to THIS shop only!) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <Link
                        v-for="item in shopProducts.data"
                        :key="item.id"
                        :href="route('storefront.product', [shop.slug, item.id])"
                        class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-lg hover:border-indigo-300 transition-all flex flex-col justify-between group"
                    >
                        <div>
                            <!-- Thumbnail (Shop specific override or fallback) -->
                            <div class="relative h-48 bg-slate-100 overflow-hidden">
                                <img
                                    :src="item.thumbnail || item.product?.thumbnail"
                                    :alt="item.product?.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                />
                                <span class="absolute top-3 left-3 bg-slate-900/75 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg text-[10px] font-semibold">
                                    {{ item.product?.category || 'General' }}
                                </span>

                                <span v-if="item.stock <= 0" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center text-white text-xs font-bold">
                                    Out of Stock
                                </span>
                            </div>

                            <!-- Info -->
                            <div class="p-4 space-y-2">
                                <h3 class="font-bold text-slate-900 text-sm group-hover:text-indigo-600 transition-colors line-clamp-1" :title="item.product?.title">
                                    {{ item.product?.title }}
                                </h3>

                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ item.details || item.product?.description || 'No description provided.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Card Pricing Footer -->
                        <div class="p-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-semibold uppercase">Shop Price</span>
                                <span class="text-lg font-black text-slate-900">${{ Number(item.price).toFixed(2) }}</span>
                            </div>

                            <span class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 group-hover:text-indigo-700">
                                View Item →
                            </span>
                        </div>
                    </Link>

                    <div v-if="shopProducts.data.length === 0" class="col-span-full py-16 text-center text-slate-400 bg-white rounded-3xl border border-slate-200">
                        <ShoppingBag class="w-10 h-10 mx-auto mb-2 text-slate-300 stroke-1" />
                        <p class="text-sm font-semibold text-slate-700">No products found in this store matching your filters.</p>
                        <p class="text-xs text-slate-400 mt-1">This store only displays products assigned by the shop owner.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <Pagination :links="shopProducts.links" />
            </div>
        </div>
    </StorefrontLayout>
</template>
