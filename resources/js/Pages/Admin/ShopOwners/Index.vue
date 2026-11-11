<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { 
    Plus, 
    Search, 
    Users, 
    Edit, 
    Trash2, 
    Store,
    Phone,
    Mail
} from 'lucide-vue-next';

const props = defineProps({
    owners: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');

const applySearch = () => {
    router.get(
        route('admin.shop-owners.index'),
        { search: search.value },
        { preserveState: true, replace: true }
    );
};

const deleteOwner = (owner) => {
    if (confirm(`Delete owner account for "${owner.name}"?`)) {
        router.delete(route('admin.shop-owners.destroy', owner.id));
    }
};
</script>

<template>
    <Head title="Shop Owner Management" />

    <AdminLayout title="Shop Owner Accounts">
        <div class="space-y-6">
            <!-- Header Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Registered Shop Owners</h2>
                    <p class="text-xs text-slate-500 mt-1">Create merchant logins, link stores, and manage account statuses.</p>
                </div>
                <Link
                    :href="route('admin.shop-owners.create')"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors"
                >
                    <Plus class="w-4 h-4" /> Create Shop Owner
                </Link>
            </div>

            <!-- Search Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div class="relative w-full sm:w-80">
                    <Search class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
                    <input
                        v-model="search"
                        @keyup.enter="applySearch"
                        type="text"
                        placeholder="Search owners by name, email, phone..."
                        class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    />
                </div>
                <button
                    @click="applySearch"
                    class="px-4 py-2 rounded-xl bg-slate-100 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition-colors"
                >
                    Search
                </button>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50/75 text-slate-500 uppercase tracking-wider font-semibold">
                                <th class="py-3.5 px-5">Owner</th>
                                <th class="py-3.5 px-5">Assigned Shop</th>
                                <th class="py-3.5 px-5">Phone</th>
                                <th class="py-3.5 px-5">Account Status</th>
                                <th class="py-3.5 px-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="owner in owners.data" :key="owner.id" class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 font-bold flex items-center justify-center flex-shrink-0 text-sm">
                                            {{ owner.name.charAt(0) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ owner.name }}</p>
                                            <p class="text-[11px] text-slate-400">{{ owner.email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-5">
                                    <div v-if="owner.shop" class="flex items-center gap-2">
                                        <Store class="w-4 h-4 text-indigo-500 flex-shrink-0" />
                                        <Link 
                                            :href="route('admin.shops.edit', owner.shop.id)"
                                            class="font-semibold text-indigo-600 hover:underline"
                                        >
                                            {{ owner.shop.name }}
                                        </Link>
                                    </div>
                                    <span v-else class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-semibold bg-amber-50 text-amber-700">
                                        No Shop Assigned
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-slate-600">
                                    {{ owner.phone || 'N/A' }}
                                </td>

                                <td class="py-4 px-5">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold"
                                        :class="owner.status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5" :class="owner.status === 'active' ? 'bg-emerald-500' : 'bg-rose-500'"></span>
                                        {{ owner.status === 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('admin.shop-owners.edit', owner.id)"
                                            class="p-2 text-slate-400 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors"
                                            title="Edit Owner"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            @click="deleteOwner(owner)"
                                            class="p-2 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                                            title="Delete Owner"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="owners.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-slate-400">
                                    <Users class="w-8 h-8 mx-auto mb-2 text-slate-300 stroke-1" />
                                    <p>No shop owners found.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="px-5 py-3 border-t border-slate-100">
                    <Pagination :links="owners.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

