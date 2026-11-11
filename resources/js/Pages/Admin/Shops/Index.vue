<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    Plus, 
    Search, 
    Store, 
    Edit, 
    Trash2, 
    ExternalLink, 
    Layers, 
    CheckCircle2, 
    XCircle 
} from 'lucide-vue-next';

const props = defineProps({
    shops: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const isActiveFilter = ref(props.filters.is_active ?? '');

const applyFilters = () => {
    router.get(
        route('admin.shops.index'),
        { search: search.value, is_active: isActiveFilter.value },
        { preserveState: true, replace: true }
    );
};

const deleteShop = (shop) => {
    if (confirm(`Are you sure you want to delete shop "${shop.name}"? All assigned products will be unlinked.`)) {
        router.delete(route('admin.shops.destroy', shop.id));
    }
};
</script>

<template>
    <Head title="Shop Management" />

    <AdminLayout title="Shop Management">
        <div class="space-y-6">
            <!-- Header bar with Action -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">All Stores & Tenancies</h2>
                    <p class="text-xs text-slate-500 mt-1">Manage tenant stores, configure domain slugs, and assign merchant owners.</p>
                </div>
                <Link
                    :href="route('admin.shops.create')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors"
                >
                    <Plus class="w-4 h-4" /> Add New Shop
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
                        placeholder="Search by name, email, phone..."
                        class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="isActiveFilter"
                        @change="applyFilters"
                        class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">All Statuses</option>
                        <option value="1">Active Only</option>
                        <option value="0">Inactive Only</option>
                    </select>

                    <button
                        @click="applyFilters"
                        class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                    >
                        Filter
                    </button>
                </div>
            </div>

            <!-- Shops Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 uppercase tracking-wider font-semibold">
                                <th class="py-3.5 px-5">Shop Info</th>
                                <th class="py-3.5 px-5">Assigned Owner</th>
                                <th class="py-3.5 px-5">Products Listed</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="shop in shops.data" :key="shop.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <img
                                            v-if="shop.logo"
                                            :src="shop.logo"
                                            :alt="shop.name"
                                            class="w-11 h-11 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                                        />
                                        <div v-else class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0 font-bold">
                                            <Store class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ shop.name }}</p>
                                            <p class="text-[11px] text-slate-400 font-mono">/shop/{{ shop.slug }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <div v-if="shop.owner">
                                        <p class="font-semibold text-slate-800">{{ shop.owner.name }}</p>
                                        <p class="text-[11px] text-slate-500">{{ shop.owner.email }}</p>
                                    </div>
                                    <span v-else class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700">
                                        Unassigned
                                    </span>
                                </td>

                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-1.5 font-semibold text-slate-700">
                                        <Layers class="w-4 h-4 text-slate-400" />
                                        <span>{{ shop.shop_products_count || 0 }} items</span>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                        :class="shop.is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="shop.is_active ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                                        {{ shop.is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('storefront.shop', shop.slug)"
                                            target="_blank"
                                            class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="View Storefront"
                                        >
                                            <ExternalLink class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            :href="route('admin.shops.edit', shop.id)"
                                            class="p-2 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Edit Shop"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="deleteShop(shop)"
                                            class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                                            title="Delete Shop"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="shops.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <Store class="w-8 h-8 mx-auto mb-2 text-slate-300 stroke-1" />
                                    <p>No shops found matching your search criteria.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-5 py-3 border-t border-slate-100">
                    <Pagination :links="shops.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
