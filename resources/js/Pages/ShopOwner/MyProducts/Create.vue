<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ShopOwnerLayout from '@/Layouts/ShopOwnerLayout.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, PlusCircle, Sparkles } from 'lucide-vue-next';

defineProps({
    categories: Array,
});

const form = useForm({
    title: '',
    category: '',
    sku: '',
    catalog_price: '',
    shop_price: '',
    description: '',
    thumbnail: '',
    stock: 10,
});

const submit = () => {
    form.post(route('shop-owner.my-products.store'));
};
</script>

<template>
    <Head title="Create New Product" />

    <ShopOwnerLayout title="Create New Product">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center gap-3">
                <Link
                    :href="route('shop-owner.my-products.index')"
                    class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors"
                >
                    <ArrowLeft class="w-5 h-5" />
                </Link>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Create New Catalog Product</h2>
                    <p class="text-xs text-slate-500">
                        This product will automatically enter the <strong class="text-violet-700">shared catalog</strong> (available for all shop owners) and be listed in your shop.
                    </p>
                </div>
            </div>

            <!-- Shared Catalog Notice Banner -->
            <div class="p-4 rounded-2xl bg-violet-50 border border-violet-100 text-violet-900 flex items-start gap-3">
                <Sparkles class="w-5 h-5 text-violet-600 flex-shrink-0 mt-0.5" />
                <div class="text-xs leading-relaxed">
                    <p class="font-bold text-violet-950">Dual Provisioning System</p>
                    <p class="text-violet-700 mt-0.5">
                        By creating this product, you contribute it to the shared product catalog for other store owners to discover, while simultaneously adding it to your own store inventory with your custom price.
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
                            placeholder="e.g. Ergonomic Bluetooth Mechanical Keyboard"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Category</label>
                        <input
                            v-model="form.category"
                            type="text"
                            list="cat-list"
                            placeholder="e.g. Electronics, Lifestyle, Fashion"
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <datalist id="cat-list">
                            <option v-for="c in categories" :key="c" :value="c" />
                        </datalist>
                        <InputError :message="form.errors.category" />
                    </div>
                </div>

                <!-- Prices: Catalog Base Price & Your Shop Custom Price -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Catalog Suggested Price ($) *</label>
                        <input
                            v-model="form.catalog_price"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            placeholder="0.00"
                            class="w-full text-sm font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <p class="text-[10px] text-slate-400">Suggested base price in the shared catalog.</p>
                        <InputError :message="form.errors.catalog_price" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Your Shop Price ($)</label>
                        <input
                            v-model="form.shop_price"
                            type="number"
                            step="0.01"
                            min="0"
                            placeholder="Defaults to catalog price"
                            class="w-full text-sm font-bold text-violet-700 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <p class="text-[10px] text-slate-400">Price customers will pay in your store.</p>
                        <InputError :message="form.errors.shop_price" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Initial Stock</label>
                        <input
                            v-model="form.stock"
                            type="number"
                            min="0"
                            required
                            class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <InputError :message="form.errors.stock" />
                    </div>
                </div>

                <!-- SKU & Description -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">SKU (Auto-generated if left blank)</label>
                    <input
                        v-model="form.sku"
                        type="text"
                        placeholder="e.g. SHOP-KEY-001"
                        class="w-full text-sm font-mono border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                    />
                    <InputError :message="form.errors.sku" />
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Product Description & Specs</label>
                    <textarea
                        v-model="form.description"
                        rows="4"
                        placeholder="Comprehensive details, specifications, features, warranty..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                    ></textarea>
                    <InputError :message="form.errors.description" />
                </div>

                <!-- Thumbnail -->
                <div class="pt-4 border-t border-slate-100">
                    <ImageUploader
                        v-model="form.thumbnail"
                        label="Product Image / Thumbnail"
                    />
                    <InputError :message="form.errors.thumbnail" />
                </div>

                <!-- Buttons -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                    <Link
                        :href="route('shop-owner.my-products.index')"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-violet-600 text-white text-xs font-bold shadow-md shadow-violet-200 hover:bg-violet-700 transition-colors disabled:opacity-50"
                    >
                        <PlusCircle class="w-4 h-4" />
                        <span>{{ form.processing ? 'Publishing...' : 'Publish to Catalog & My Shop' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </ShopOwnerLayout>
</template>
