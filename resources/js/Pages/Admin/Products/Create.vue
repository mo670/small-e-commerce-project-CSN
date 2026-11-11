<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, Save, PackagePlus } from 'lucide-vue-next';

defineProps({
    categories: Array,
});

const form = useForm({
    title: '',
    category: '',
    sku: '',
    price: '',
    description: '',
    thumbnail: '',
    is_active: true,
});

const submit = () => {
    form.post(route('admin.products.store'));
};
</script>

<template>
    <Head title="Add Product to Shared Catalog" />

    <AdminLayout title="Add Product to Shared Catalog">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-3">
                <Link
                    :href="route('admin.products.index')"
                    class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">New Catalog Item</h2>
                    <p class="text-xs text-slate-500">
                        Products added here will be immediately available in the shared catalog for all shop owners.
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 lg:p-8 space-y-6">
                <!-- Title & Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Product Title *</label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            placeholder="e.g. Wireless Noise Cancelling Headphones"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Category</label>
                        <input
                            v-model="form.category"
                            type="text"
                            list="category-suggestions"
                            placeholder="e.g. Electronics, Audio, Apparel"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <datalist id="category-suggestions">
                            <option v-for="cat in categories" :key="cat" :value="cat" />
                        </datalist>
                        <InputError :message="form.errors.category" />
                    </div>
                </div>

                <!-- Price & SKU -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Base / Catalog Price ($) *</label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            placeholder="0.00"
                            class="w-full text-sm font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <p class="text-[11px] text-slate-400">Default suggested price. Shop owners can override this in their shops.</p>
                        <InputError :message="form.errors.price" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">SKU (Auto-generated if empty)</label>
                        <input
                            v-model="form.sku"
                            type="text"
                            placeholder="e.g. PROD-10293"
                            class="w-full text-sm font-mono border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                        />
                        <InputError :message="form.errors.sku" />
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Product Description</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        placeholder="Comprehensive details, specifications, and manufacturer notes..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                    ></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Thumbnail -->
                <div class="pt-4 border-t border-slate-100">
                    <ImageUploader
                        v-model="form.thumbnail"
                        label="Default Thumbnail Image"
                        placeholder="https://... image URL or upload JPEG/PNG"
                    />
                    <InputError :message="form.errors.thumbnail" />
                </div>

                <!-- Status -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">Master Catalog Availability</span>
                        <span class="text-xs text-slate-500">Active products appear in the catalog for shop owners to select.</span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <Link
                        :href="route('admin.products.index')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors disabled:opacity-50"
                    >
                        <PackagePlus class="w-4 h-4" />
                        <span>{{ form.processing ? 'Saving...' : 'Add to Catalog' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
