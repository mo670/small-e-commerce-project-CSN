<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, Save, Store, ExternalLink } from 'lucide-vue-next';

const props = defineProps({
    shop: Object,
    availableOwners: Array,
});

const form = useForm({
    name: props.shop.name || '',
    slug: props.shop.slug || '',
    user_id: props.shop.user_id || '',
    description: props.shop.description || '',
    logo: props.shop.logo || '',
    banner: props.shop.banner || '',
    email: props.shop.email || '',
    phone: props.shop.phone || '',
    address: props.shop.address || '',
    is_active: Boolean(props.shop.is_active),
});

const submit = () => {
    form.put(route('admin.shops.update', props.shop.id));
};
</script>

<template>
    <Head :title="`Edit ${shop.name}`" />

    <AdminLayout :title="`Edit ${shop.name}`">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.shops.index')"
                        class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                    </Link>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Edit Store Profile</h2>
                        <p class="text-xs text-slate-500">Update configuration for {{ shop.name }}.</p>
                    </div>
                </div>

                <Link
                    :href="route('storefront.shop', shop.slug)"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors"
                >
                    <ExternalLink class="w-3.5 h-3.5" /> View Live
                </Link>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 lg:p-8 space-y-6">
                <!-- Shop Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Shop Name *</label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">URL Slug *</label>
                        <input
                            v-model="form.slug"
                            type="text"
                            required
                            class="w-full text-sm font-mono border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                        <InputError :message="form.errors.slug" />
                    </div>
                </div>

                <!-- Owner Selection -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Assign Shop Owner</label>
                    <select
                        v-model="form.user_id"
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">-- No Owner (Leave Unassigned) --</option>
                        <option v-for="owner in availableOwners" :key="owner.id" :value="owner.id">
                            {{ owner.name }} ({{ owner.email }})
                        </option>
                    </select>
                    <InputError :message="form.errors.user_id" />
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Store Description</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    ></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Logo & Banner Images -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-100">
                    <ImageUploader
                        v-model="form.logo"
                        label="Store Logo"
                    />
                    <ImageUploader
                        v-model="form.banner"
                        label="Header Banner"
                    />
                </div>

                <!-- Contact & Address -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Store Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Store Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5"
                        />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Physical Address</label>
                        <input
                            v-model="form.address"
                            type="text"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5"
                        />
                        <InputError :message="form.errors.address" />
                    </div>
                </div>

                <!-- Active Status Toggle -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">Store Active Status</span>
                        <span class="text-xs text-slate-500">Active stores and their assigned products are visible to customers.</span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <Link
                        :href="route('admin.shops.index')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors disabled:opacity-50"
                    >
                        <Save class="w-4 h-4" />
                        <span>{{ form.processing ? 'Saving...' : 'Update Store' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
