<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ShopOwnerLayout from '@/Layouts/ShopOwnerLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    PlusCircle, 
    Search, 
    Layers, 
    Edit, 
    Trash2, 
    ExternalLink, 
    BookOpen,
    Eye,
    Tag,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    shopProducts: Object,
    filters: Object,
    shop: Object,
});

const search = ref(props.filters.search || '');
const status = ref(props.filters.status ?? '');

const applyFilters = () => {
    router.get(
        route('shop-owner.my-products.index'),
        { search: search.value, status: status.value },
        { preserveState: true, replace: true }
    );
};

const removeProduct = (item) => {
    if (confirm(`Remove "${item.product?.title}" from your shop storefront? (It will remain in the shared master catalog).`)) {
        router.delete(route('shop-owner.my-products.destroy', item.id));
    }
};
</script>

<template>
    <Head title="My Shop Listings" />

    <ShopOwnerLayout title="My Shop Listings">
        <div class="space-y-6">
            <!-- Header bar with Action buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Current Store Inventory</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Manage your shop-specific product pricing, custom descriptions, stock levels, and thumbnails.
                    </p>
                </div>

                <div class="flex items-center gap-2.5">
                    <Link
                        :href="route('shop-owner.catalog.index')"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-bold hover:bg-slate-50 transition-colors"
                    >
                        <BookOpen class="w-4 h-4 text-violet-600" /> Browse Shared Catalog
                    </Link>
                    <Link
                        :href="route('shop-owner.my-products.create')"
                        class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-violet-600 text-white text-xs font-bold shadow-md shadow-violet-100 hover:bg-violet-700 transition-colors"
                    >
                        <PlusCircle class="w-4 h-4" /> Create New Product
                    </Link>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="relative w-full sm:w-80">
                    <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search your listings..."
                        class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="status"
                        @change="applyFilters"
                        class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-violet-500"
                    >
                        <option value="">All Visibility</option>
                        <option value="1">Active in Store</option>
                        <option value="0">Hidden / Draft</option>
                    </select>

                    <button
                        @click="applyFilters"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                    >
                        Filter
                    </button>
                </div>
            </div>

            <!-- Table of Shop Products -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 uppercase tracking-wider font-semibold">
                                <th class="py-3.5 px-5">Listing Product & Details</th>
                                <th class="py-3.5 px-5">Catalog Price</th>
                                <th class="py-3.5 px-5">Your Shop Price</th>
                                <th class="py-3.5 px-5">Stock</th>
                                <th class="py-3.5 px-5">Customer Visibility</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="item in shopProducts.data" :key="item.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-start gap-3">
                                        <img
                                            :src="item.thumbnail || item.product?.thumbnail"
                                            :alt="item.product?.title"
                                            class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                        />
                                        <div class="max-w-xs">
                                            <p class="font-bold text-slate-900 text-sm truncate">{{ item.product?.title }}</p>
                                            <p class="text-[11px] text-violet-600 font-medium">
                                                {{ item.product?.category || 'General' }} • {{ item.product?.sku || 'No SKU' }}
                                            </p>
                                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">
                                                {{ item.details || item.product?.description || 'No custom description' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5 text-slate-500">
                                    ${{ Number(item.product?.price).toFixed(2) }}
                                </td>

                                <td class="py-4 px-5">
                                    <span class="font-extrabold text-sm text-violet-700">
                                        ${{ Number(item.price).toFixed(2) }}
                                    </span>
                                    <span 
                                        v-if="Number(item.price) !== Number(item.product?.price)"
                                        class="block text-[10px] text-emerald-600 font-semibold"
                                    >
                                        Customized
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <span 
                                        class="inline-flex px-2 py-0.5 rounded-md text-[11px] font-bold"
                                        :class="item.stock <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-700'"
                                    >
                                        {{ item.stock }} units
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                        :class="item.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="item.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ item.is_active ? 'Visible in Shop' : 'Hidden from Shop' }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('storefront.product', [shop.slug, item.id])"
                                            target="_blank"
                                            class="p-2 text-slate-400 hover:text-violet-600 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="View in Customer Storefront"
                                        >
                                            <ExternalLink class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            :href="route('shop-owner.my-products.edit', item.id)"
                                            class="p-2 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Edit Custom Price & Details"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="removeProduct(item)"
                                            class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                                            title="Remove from Shop"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="shopProducts.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <Layers class="w-8 h-8 mx-auto mb-2 text-slate-300 stroke-1" />
                                    <p class="text-sm">No products listed in your shop yet.</p>
                                    <Link
                                        :href="route('shop-owner.catalog.index')"
                                        class="inline-block mt-3 px-4 py-2 rounded-xl bg-violet-600 text-white text-xs font-bold"
                                    >
                                        Browse Shared Catalog
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-slate-100">
                    <Pagination :links="shopProducts.links" />
                </div>
            </div>
        </div>
    </ShopOwnerLayout>
</template>
