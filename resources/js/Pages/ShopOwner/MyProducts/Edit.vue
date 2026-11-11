<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ShopOwnerLayout from '@/Layouts/ShopOwnerLayout.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import InputError from '@/Components/InputError.vue';
import { ArrowLeft, Save, Sparkles, ExternalLink } from 'lucide-vue-next';

const props = defineProps({
    shopProduct: Object,
});

const form = useForm({
    price: props.shopProduct.price || '',
    details: props.shopProduct.details || '',
    thumbnail: props.shopProduct.thumbnail || '',
    stock: props.shopProduct.stock ?? 10,
    is_active: Boolean(props.shopProduct.is_active),
});

const submit = () => {
    form.put(route('shop-owner.my-products.update', props.shopProduct.id));
};
</script>

<template>
    <Head :title="`Edit ${shopProduct.product?.title}`" />

    <ShopOwnerLayout :title="`Customize Shop Product`">
        <div class="max-w-4xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('shop-owner.my-products.index')"
                        class="p-2 text-slate-400 hover:text-slate-700 rounded-xl hover:bg-slate-100 transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5" />
                    </Link>
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Customize Listing Details</h2>
                        <p class="text-xs text-slate-500">
                            Set your shop-specific price, custom product details, and custom thumbnail for <strong>{{ shopProduct.product?.title }}</strong>.
                        </p>
                    </div>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                    <Sparkles class="w-3.5 h-3.5" /> Shop Override
                </span>
            </div>

            <!-- Master Catalog Reference Card -->
            <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start gap-4">
                <img 
                    :src="shopProduct.product?.thumbnail" 
                    :alt="shopProduct.product?.title" 
                    class="w-20 h-20 rounded-xl object-cover border border-slate-200 flex-shrink-0"
                />
                <div class="space-y-1 text-xs">
                    <span class="text-slate-400 uppercase font-bold text-[10px]">Master Catalog Reference</span>
                    <h4 class="font-bold text-slate-900 text-sm">{{ shopProduct.product?.title }}</h4>
                    <p class="text-slate-500">
                        Category: <strong class="text-slate-700">{{ shopProduct.product?.category }}</strong> • SKU: <span class="font-mono">{{ shopProduct.product?.sku }}</span>
                    </p>
                    <p class="text-slate-500">
                        Suggested Catalog Base Price: <strong class="text-slate-900">${{ Number(shopProduct.product?.price).toFixed(2) }}</strong>
                    </p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 lg:p-8 space-y-6">
                <!-- Shop Specific Price & Stock -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">
                            Your Shop Listing Price ($) *
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            step="0.01"
                            min="0"
                            required
                            class="w-full text-base font-extrabold text-violet-700 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <p class="text-[11px] text-slate-400">
                            Overrides base price for customers browsing your store.
                        </p>
                        <InputError :message="form.errors.price" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-slate-700 uppercase">Current Inventory Stock *</label>
                        <input
                            v-model="form.stock"
                            type="number"
                            min="0"
                            required
                            class="w-full text-sm font-semibold border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                        />
                        <InputError :message="form.errors.stock" />
                    </div>
                </div>

                <!-- Custom Details -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        Shop-Specific Details & Description
                    </label>
                    <textarea
                        v-model="form.details"
                        rows="4"
                        placeholder="Customize what customers in your shop see (promotions, store warranty, package inclusions)..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                    ></textarea>
                    <p class="text-[11px] text-slate-400">
                        Leave blank if you prefer falling back to the master catalog description.
                    </p>
                    <InputError :message="form.errors.details" />
                </div>

                <!-- Custom Thumbnail -->
                <div class="pt-4 border-t border-slate-100">
                    <ImageUploader
                        v-model="form.thumbnail"
                        label="Shop-Specific Thumbnail Image (Overrides catalog image)"
                        placeholder="https://... custom photo URL or upload"
                    />
                    <InputError :message="form.errors.thumbnail" />
                </div>

                <!-- Visibility Toggle -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="block text-sm font-bold text-slate-800">Storefront Visibility</span>
                        <span class="text-xs text-slate-500">
                            When active, this product appears to customers visiting your shop.
                        </span>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" v-model="form.is_active" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    </label>
                </div>

                <!-- Submit Button -->
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
                        <Save class="w-4 h-4" />
                        <span>{{ form.processing ? 'Saving...' : 'Update Shop Listing' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </ShopOwnerLayout>
</template>

