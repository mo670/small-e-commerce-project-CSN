<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    Plus, 
    Search, 
    Package, 
    Edit, 
    Trash2, 
    Layers, 
    Tag,
    UserCheck
} from 'lucide-vue-next';

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const applyFilters = () => {
    router.get(
        route('admin.products.index'),
        { search: search.value, category: category.value },
        { preserveState: true, replace: true }
    );
};

const deleteProduct = (product) => {
    if (confirm(`Remove "${product.title}" from the master shared catalog? It will also be unlisted from any shops.`)) {
        router.delete(route('admin.products.destroy', product.id));
    }
};
</script>

<template>
    <Head title="Master Shared Catalog" />

    <AdminLayout title="Master Product Catalog">
        <div class="space-y-6">
            <!-- Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Shared Catalog Directory</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Central product repository. Products added here or by any shop owner are available for all stores to list.
                    </p>
                </div>
                <Link
                    :href="route('admin.products.create')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors"
                >
                    <Plus class="w-4 h-4" /> Add Master Product
                </Link>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
                <div class="relative w-full sm:w-80">
                    <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Search title, SKU, category..."
                        class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
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

                    <button
                        @click="applyFilters"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                    >
                        Filter
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 uppercase tracking-wider font-semibold">
                                <th class="py-3.5 px-5">Product Details</th>
                                <th class="py-3.5 px-5">Category / SKU</th>
                                <th class="py-3.5 px-5">Base Price</th>
                                <th class="py-3.5 px-5">Created By</th>
                                <th class="py-3.5 px-5">Shops Listing</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="product in products.data" :key="product.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <img
                                            v-if="product.thumbnail"
                                            :src="product.thumbnail"
                                            :alt="product.title"
                                            class="w-12 h-12 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                        />
                                        <div v-else class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center flex-shrink-0">
                                            <Package class="w-6 h-6 stroke-1" />
                                        </div>
                                        <div class="max-w-xs">
                                            <p class="font-bold text-slate-900 text-sm truncate">{{ product.title }}</p>
                                            <p class="text-[11px] text-slate-500 line-clamp-1">{{ product.description || 'No description' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                                        <Tag class="w-3 h-3 text-slate-400" />
                                        {{ product.category || 'General' }}
                                    </span>
                                    <p class="text-[10px] text-slate-400 font-mono mt-1">{{ product.sku || 'No SKU' }}</p>
                                </td>

                                <td class="py-4 px-5">
                                    <p class="font-bold text-slate-900 text-sm">${{ Number(product.price).toFixed(2) }}</p>
                                </td>

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-1.5">
                                        <UserCheck class="w-3.5 h-3.5 text-slate-400" />
                                        <span class="text-slate-700 font-medium">
                                            {{ product.creator ? product.creator.name : 'System Admin' }}
                                        </span>
                                    </div>
                                    <span class="text-[10px] text-slate-400 block ml-5">
                                        {{ product.creator?.role === 'shop_owner' ? 'Shop Owner' : 'Admin' }}
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold"
                                        :class="product.shop_products_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-amber-50 text-amber-700'"
                                    >
                                        <Layers class="w-3.5 h-3.5" />
                                        {{ product.shop_products_count }} {{ product.shop_products_count === 1 ? 'store' : 'stores' }}
                                    </span>
                                    <p v-if="product.shop_products_count === 0" class="text-[10px] text-amber-600 mt-0.5">
                                        (Unassigned - hidden from customers)
                                    </p>
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('admin.products.edit', product.id)"
                                            class="p-2 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Edit Catalog Product"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="deleteProduct(product)"
                                            class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                                            title="Delete Catalog Product"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="products.data.length === 0">
                                <td colspan="6" class="py-12 text-center text-slate-400">
                                    <Package class="w-8 h-8 mx-auto mb-2 text-slate-300 stroke-1" />
                                    <p>No products found in the catalog.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-slate-100">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

