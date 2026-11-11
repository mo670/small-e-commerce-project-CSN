<script setup>
import { Head, Link } from '@inertiajs/vue3';
import StorefrontLayout from '@/Layouts/StorefrontLayout.vue';
import { 
    Store, 
    ArrowLeft, 
    CheckCircle2, 
    AlertCircle, 
    Truck, 
    ShieldCheck, 
    ShoppingBag, 
    Sparkles,
    Tag,
    Share2
} from 'lucide-vue-next';

const props = defineProps({
    shop: Object,
    shopProduct: Object,
    relatedProducts: Array,
});
</script>

<template>
    <Head :title="`${shopProduct.product?.title} - ${shop.name}`" />

    <StorefrontLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-500">
                <Link :href="route('home')" class="hover:text-slate-900 transition-colors">Shops</Link>
                <span>/</span>
                <Link :href="route('storefront.shop', shop.slug)" class="hover:text-slate-900 transition-colors">
                    {{ shop.name }}
                </Link>
                <span>/</span>
                <span class="text-slate-900 truncate max-w-xs">{{ shopProduct.product?.title }}</span>
            </nav>

            <!-- Main Product Display -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-10 shadow-xs grid grid-cols-1 lg:grid-cols-2 gap-10">
                <!-- Product Image Gallery / Display -->
                <div class="space-y-4">
                    <div class="h-96 sm:h-[480px] bg-slate-100 rounded-2xl overflow-hidden border border-slate-200/80 relative">
                        <img 
                            :src="shopProduct.thumbnail || shopProduct.product?.thumbnail" 
                            :alt="shopProduct.product?.title" 
                            class="w-full h-full object-cover"
                        />
                        <span class="absolute top-4 left-4 bg-slate-900/80 backdrop-blur-sm text-white px-3 py-1 rounded-full text-xs font-semibold">
                            {{ shopProduct.product?.category || 'General' }}
                        </span>
                    </div>
                </div>

                <!-- Product Purchase Info -->
                <div class="flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <!-- Merchant Header Badge -->
                        <div class="flex items-center justify-between">
                            <Link 
                                :href="route('storefront.shop', shop.slug)"
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-bold transition-colors"
                            >
                                <Store class="w-3.5 h-3.5 text-indigo-600" />
                                <span>Sold by {{ shop.name }}</span>
                            </Link>

                            <span 
                                class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full"
                                :class="shopProduct.stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                            >
                                <CheckCircle2 v-if="shopProduct.stock > 0" class="w-3.5 h-3.5" />
                                <AlertCircle v-else class="w-3.5 h-3.5" />
                                {{ shopProduct.stock > 0 ? `In Stock (${shopProduct.stock} available)` : 'Out of Stock' }}
                            </span>
                        </div>

                        <!-- Title -->
                        <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
                            {{ shopProduct.product?.title }}
                        </h1>

                        <!-- SKU and Meta -->
                        <div class="flex items-center gap-3 text-xs text-slate-400 font-mono">
                            <span>SKU: {{ shopProduct.product?.sku || 'N/A' }}</span>
                            <span>•</span>
                            <span>Assigned Store Listing</span>
                        </div>

                        <!-- Shop-Specific Price -->
                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                {{ shop.name }} Storefront Price
                            </span>
                            <div class="flex items-baseline gap-3">
                                <span class="text-3xl sm:text-4xl font-black text-slate-900">
                                    ${{ Number(shopProduct.price).toFixed(2) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500">Tax included. Shipping calculated at checkout.</p>
                        </div>

                        <!-- Shop-Specific Details & Notes -->
                        <div class="space-y-2 pt-2">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Product Information & Store Notes</h3>
                            <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-white rounded-xl p-4 border border-slate-100">
                                {{ shopProduct.details || shopProduct.product?.description || 'No detailed specifications provided for this product.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Trust Perks -->
                    <div class="pt-6 border-t border-slate-100 grid grid-cols-2 gap-4 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <Truck class="w-4 h-4 text-indigo-600 flex-shrink-0" />
                            <span>Fast Delivery by {{ shop.name }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <ShieldCheck class="w-4 h-4 text-indigo-600 flex-shrink-0" />
                            <span>Official Merchant Guarantee</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products from Same Shop -->
            <div v-if="relatedProducts.length > 0" class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">More From {{ shop.name }}</h2>
                        <p class="text-xs text-slate-500">Other curated items available in this storefront</p>
                    </div>
                    <Link
                        :href="route('storefront.shop', shop.slug)"
                        class="text-xs font-bold text-indigo-600 hover:text-indigo-800"
                    >
                        View Full Store →
                    </Link>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <Link
                        v-for="rel in relatedProducts"
                        :key="rel.id"
                        :href="route('storefront.product', [shop.slug, rel.id])"
                        class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md hover:border-indigo-300 transition-all flex flex-col justify-between group"
                    >
                        <div>
                            <div class="h-40 bg-slate-100 overflow-hidden">
                                <img
                                    :src="rel.thumbnail || rel.product?.thumbnail"
                                    :alt="rel.product?.title"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                />
                            </div>
                            <div class="p-4 space-y-1">
                                <h4 class="font-bold text-slate-900 text-sm truncate">{{ rel.product?.title }}</h4>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ rel.details || rel.product?.description }}</p>
                            </div>
                        </div>

                        <div class="p-4 pt-2 flex items-center justify-between text-xs">
                            <span class="font-black text-slate-900 text-base">${{ Number(rel.price).toFixed(2) }}</span>
                            <span class="font-bold text-indigo-600">View</span>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </StorefrontLayout>
</template>
