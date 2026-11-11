<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, Save, UserPlus } from 'lucide-vue-next';

defineProps({
    availableShops: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    phone: '',
    status: 'active',
    shop_id: '',
});

const submit = () => {
    form.post(route('admin.shop-owners.store'));
};
</script>

<template>
    <Head title="Create Shop Owner" />

    <AdminLayout title="Create Shop Owner">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex items-center gap-3">
                <Link
                    :href="route('admin.shop-owners.index')"
                    class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">New Shop Owner Account</h2>
                    <p class="text-xs text-slate-500">Create login credentials and optionally link to an unassigned shop.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 lg:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Full Name *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            placeholder="e.g. Rahim Chowdhury"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Email Address *</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            placeholder="merchant@example.com"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.email" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Password *</label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            placeholder="Minimum 8 characters"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.password" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Phone Number</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            placeholder="+880 1..."
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Account Status</label>
                        <select
                            v-model="form.status"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white"
                        >
                            <option value="active">Active (Can log in)</option>
                            <option value="inactive">Inactive (Suspended)</option>
                        </select>
                        <InputError :message="form.errors.status" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Assign to Shop (Optional)</label>
                        <select
                            v-model="form.shop_id"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white"
                        >
                            <option value="">-- No Shop Right Now --</option>
                            <option v-for="shop in availableShops" :key="shop.id" :value="shop.id">
                                {{ shop.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.shop_id" />
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <Link
                        :href="route('admin.shop-owners.index')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors disabled:opacity-50"
                    >
                        <UserPlus class="w-4 h-4" />
                        <span>{{ form.processing ? 'Saving...' : 'Create Account' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

