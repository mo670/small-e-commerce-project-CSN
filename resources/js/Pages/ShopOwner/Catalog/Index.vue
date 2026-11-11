<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import ShopOwnerLayout from '@/Layouts/ShopOwnerLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ImageUploader from '@/Components/ImageUploader.vue';
import InputError from '@/Components/InputError.vue';
import { 
    BookOpen, 
    Search, 
    Check, 
    Plus, 
    Tag, 
    UserCheck, 
    Layers, 
    X, 
    ShoppingBag, 
    Sparkles 
} from 'lucide-vue-next';

const props = defineProps({
    catalog: Object,
    existingProductIds: Array,
    categories: Array,
    filters: Object,
    shop: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');

const applyFilters = () => {
    router.get(
        route('shop-owner.catalog.index'),
        { search: search.value, category: category.value },
        { preserveState: true, replace: true }
    );
};

// Modal state for adding product to shop
const selectedProduct = ref(null);
const isModalOpen = ref(false);

const assignForm = useForm({
    product_id: '',
    price: '',
    details: '',
    thumbnail: '',
    stock: 10,
    is_active: true,
});

const openAssignModal = (product) => {
    selectedProduct.value = product;
    assignForm.product_id = product.id;
    assignForm.price = product.price; // defaults to catalog base price
    assignForm.details = product.description || '';
    assignForm.thumbnail = product.thumbnail || '';
    assignForm.stock = 10;
    assignForm.is_active = true;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedProduct.value = null;
    assignForm.reset();
};

const submitAssign = () => {
    assignForm.post(route('shop-owner.catalog.assign'), {
        onSuccess: () => {
            closeModal();
        },
    });
};
</script>

<template>
    <Head title="Shared Product Catalog" />

    <ShopOwnerLayout title="Browse Shared Product Catalog">
        <div class="space-y-6">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Shared Master Catalog</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Select any master product created by admins or fellow merchants, set your store's custom price, and add it to your listings.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-violet-50 text-violet-700 text-xs font-bold border border-violet-100">
                        <Sparkles class="w-4 h-4 text-violet-600" />
                        {{ existingProductIds.length }} Items In Your Shop
                    </span>
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
                        placeholder="Search catalog products..."
                        class="w-full pl-9 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-500 focus:border-violet-500"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="category"
                        @change="applyFilters"
                        class="text-xs border border-slate-200 rounded-xl px-3 py-2 bg-white text-slate-700 focus:ring-2 focus:ring-violet-500"
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

            <!-- Catalog Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                <div 
                    v-for="product in catalog.data" 
                    :key="product.id"
                    class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group"
                >
                    <!-- Product Image -->
                    <div class="relative h-48 bg-slate-100 overflow-hidden">
                        <img 
                            v-if="product.thumbnail" 
                            :src="product.thumbnail" 
                            :alt="product.title" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                        />
                        <div v-else class="w-full h-full flex items-center justify-center text-slate-300">
                            <BookOpen class="w-10 h-10 stroke-1" />
                        </div>

                        <!-- Already Added Badge -->
                        <div 
                            v-if="existingProductIds.includes(product.id)" 
                            class="absolute top-3 right-3 bg-emerald-600/95 backdrop-blur-sm text-white px-2.5 py-1 rounded-lg text-[10px] font-bold shadow-md flex items-center gap-1"
                        >
                            <Check class="w-3.5 h-3.5" /> In Your Shop
                        </div>

                        <!-- Category badge -->
                        <span class="absolute bottom-3 left-3 bg-slate-900/75 backdrop-blur-sm text-white px-2.5 py-0.5 rounded-lg text-[10px] font-semibold">
                            {{ product.category || 'General' }}
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-slate-400 mb-1">
                                <span>{{ product.sku || 'N/A' }}</span>
                                <span class="text-slate-500 font-medium">By: {{ product.creator ? product.creator.name : 'System' }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm line-clamp-1" :title="product.title">
                                {{ product.title }}
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-1">
                                {{ product.description || 'No description available for this catalog item.' }}
                            </p>
                        </div>

                        <!-- Price & Action -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Suggested Base</span>
                                <span class="text-base font-extrabold text-slate-900">${{ Number(product.price).toFixed(2) }}</span>
                            </div>

                            <button
                                @click="openAssignModal(product)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all"
                                :class="existingProductIds.includes(product.id)
                                    ? 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                                    : 'bg-violet-600 text-white shadow-sm hover:bg-violet-700 hover:shadow-violet-200'"
                            >
                                <span v-if="existingProductIds.includes(product.id)">Edit Price</span>
                                <span v-else class="flex items-center gap-1"><Plus class="w-3.5 h-3.5" /> Add to Shop</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-if="catalog.data.length === 0" class="col-span-full py-16 text-center text-slate-400 bg-white rounded-2xl border border-slate-200/80">
                    <BookOpen class="w-10 h-10 mx-auto mb-2 text-slate-300 stroke-1" />
                    <p class="text-sm">No products found in the catalog matching your filters.</p>
                </div>
            </div>

            <!-- Pagination -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-3 shadow-xs">
                <Pagination :links="catalog.links" />
            </div>
        </div>

        <!-- Add / Customize Product Modal -->
        <div 
            v-if="isModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
        >
            <div class="bg-white rounded-3xl max-w-xl w-full p-6 lg:p-8 shadow-2xl relative my-8 animate-in zoom-in-95 duration-200">
                <!-- Close Button -->
                <button 
                    @click="closeModal" 
                    class="absolute top-6 right-6 p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                >
                    <X class="w-5 h-5" />
                </button>

                <div class="space-y-6">
                    <div>
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-violet-600 uppercase tracking-wider">
                            <Sparkles class="w-3.5 h-3.5" /> Shop-Specific Customization
                        </span>
                        <h3 class="text-xl font-bold text-slate-900 mt-1">Add to {{ shop.name }}</h3>
                        <p class="text-xs text-slate-500">
                            Configure how <strong class="text-slate-800">{{ selectedProduct?.title }}</strong> will appear on your customer storefront.
                        </p>
                    </div>

                    <form @submit.prevent="submitAssign" class="space-y-5">
                        <!-- Custom Price & Stock -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase">
                                    Your Shop Price ($) *
                                </label>
                                <input
                                    v-model="assignForm.price"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    required
                                    placeholder="0.00"
                                    class="w-full text-base font-extrabold text-violet-700 border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                                />
                                <p class="text-[10px] text-slate-400">
                                    Catalog base suggested price: ${{ Number(selectedProduct?.price).toFixed(2) }}
                                </p>
                                <InputError :message="assignForm.errors.price" />
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-bold text-slate-700 uppercase">Initial Stock Count</label>
                                <input
                                    v-model="assignForm.stock"
                                    type="number"
                                    min="0"
                                    required
                                    class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                                />
                                <InputError :message="assignForm.errors.stock" />
                            </div>
                        </div>

                        <!-- Custom Details -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700 uppercase">
                                Shop-Specific Details & Offer Notes
                            </label>
                            <textarea
                                v-model="assignForm.details"
                                rows="3"
                                placeholder="Add store-specific warranties, free gift notices, bundle descriptions..."
                                class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-violet-500"
                            ></textarea>
                            <InputError :message="assignForm.errors.details" />
                        </div>

                        <!-- Custom Thumbnail (optional override) -->
                        <div class="pt-2">
                            <ImageUploader
                                v-model="assignForm.thumbnail"
                                label="Custom Shop Thumbnail (Leave empty to use catalog default)"
                                placeholder="https://... image URL or upload"
                            />
                            <InputError :message="assignForm.errors.thumbnail" />
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="assignForm.processing"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-violet-600 text-white text-xs font-bold shadow-md shadow-violet-200 hover:bg-violet-700 transition-colors disabled:opacity-50"
                            >
                                <Check class="w-4 h-4" />
                                <span>{{ assignForm.processing ? 'Saving...' : 'Add to My Storefront' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </ShopOwnerLayout>
</template>

