<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useCart } from '@/Composables/useCart';
import {
    ShoppingCart,
    X,
    Trash2,
    Plus,
    Minus,
    ShoppingBag,
    CheckCircle2,
    ArrowRight,
    Store
} from 'lucide-vue-next';

const {
    cartItems,
    isCartOpen,
    toastMessage,
    cartCount,
    cartTotal,
    updateQuantity,
    removeFromCart,
    clearCart,
    closeCart,
} = useCart();

const isCheckingOut = ref(false);
const orderCompleted = ref(false);
const customerForm = ref({
    name: '',
    phone: '',
    address: '',
});

const handleCheckout = () => {
    orderCompleted.value = true;
    clearCart();
    setTimeout(() => {
        isCheckingOut.value = false;
    }, 300);
};

const resetAndClose = () => {
    orderCompleted.value = false;
    isCheckingOut.value = false;
    closeCart();
};
</script>

<template>
    <!-- Floating Cart Toast Notification -->
    <div
        v-if="toastMessage"
        class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-semibold animate-in fade-in slide-in-from-bottom-4 duration-200"
    >
        <CheckCircle2 class="w-4 h-4 text-emerald-400 flex-shrink-0" />
        <span>{{ toastMessage }}</span>
        <button
            @click="isCartOpen = true"
            class="ml-2 text-indigo-300 hover:text-white underline font-bold"
        >
            View Cart
        </button>
    </div>

    <!-- Backdrop -->
    <div
        v-if="isCartOpen"
        @click="resetAndClose"
        class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs transition-opacity"
    />

    <!-- Slide-over Drawer -->
    <aside
        v-if="isCartOpen"
        class="fixed inset-y-0 right-0 z-50 w-full max-w-md bg-white shadow-2xl flex flex-col justify-between animate-in slide-in-from-right duration-200"
    >
        <!-- Drawer Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <ShoppingCart class="w-5 h-5" />
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 text-base">Shopping Cart</h3>
                    <p class="text-xs text-slate-500">{{ cartCount }} {{ cartCount === 1 ? 'item' : 'items' }} selected</p>
                </div>
            </div>

            <button
                @click="resetAndClose"
                class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors"
            >
                <X class="w-5 h-5" />
            </button>
        </div>

        <!-- Order Success State -->
        <div v-if="orderCompleted" class="flex-1 p-8 flex flex-col items-center justify-center text-center space-y-4">
            <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                <CheckCircle2 class="w-9 h-9" />
            </div>
            <h4 class="text-xl font-extrabold text-slate-900">Order Placed Successfully!</h4>
            <p class="text-xs text-slate-500 max-w-xs leading-relaxed">
                Thank you, <strong class="text-slate-800">{{ customerForm.name || 'Valued Customer' }}</strong>! Your order has been sent to the respective shop owners for fulfillment.
            </p>
            <button
                @click="resetAndClose"
                class="mt-4 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition-colors"
            >
                Continue Shopping
            </button>
        </div>

        <!-- Checkout Form State -->
        <form
            v-else-if="isCheckingOut"
            @submit.prevent="handleCheckout"
            class="flex-1 overflow-y-auto p-6 space-y-5"
        >
            <div class="flex items-center justify-between">
                <h4 class="font-bold text-slate-900 text-sm">Delivery Details</h4>
                <button
                    type="button"
                    @click="isCheckingOut = false"
                    class="text-xs font-semibold text-indigo-600 hover:underline"
                >
                    ← Back to Cart
                </button>
            </div>

            <div class="space-y-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Full Name *</label>
                    <input
                        v-model="customerForm.name"
                        type="text"
                        required
                        placeholder="Enter your name"
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Phone Number *</label>
                    <input
                        v-model="customerForm.phone"
                        type="text"
                        required
                        placeholder="+880 1..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                    />
                </div>

                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase">Delivery Address *</label>
                    <textarea
                        v-model="customerForm.address"
                        rows="3"
                        required
                        placeholder="House, Road, Area, City..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-indigo-500"
                    ></textarea>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                <div class="flex justify-between text-slate-500">
                    <span>Items Total ({{ cartCount }})</span>
                    <span>${{ cartTotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Shipping</span>
                    <span class="text-emerald-600 font-semibold">Free</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-black text-slate-900">
                    <span>Total Payable</span>
                    <span>${{ cartTotal.toFixed(2) }}</span>
                </div>
            </div>

            <button
                type="submit"
                class="w-full py-3 rounded-xl bg-emerald-600 text-white text-sm font-bold shadow-md hover:bg-emerald-700 transition-colors"
            >
                Confirm Order (${{ cartTotal.toFixed(2) }})
            </button>
        </form>

        <!-- Cart Items List -->
        <div v-else class="flex-1 overflow-y-auto p-6">
            <div v-if="cartItems.length > 0" class="space-y-4">
                <div
                    v-for="item in cartItems"
                    :key="item.shopProductId"
                    class="p-3.5 rounded-2xl border border-slate-200/80 bg-slate-50/50 flex gap-3.5 items-center"
                >
                    <img
                        :src="item.thumbnail"
                        :alt="item.title"
                        class="w-16 h-16 rounded-xl object-cover border border-slate-200 bg-white flex-shrink-0"
                    />

                    <div class="flex-1 min-w-0">
                        <p class="text-[10px] font-bold text-indigo-600 uppercase flex items-center gap-1 truncate">
                            <Store class="w-3 h-3" /> {{ item.shopName }}
                        </p>
                        <h4 class="font-bold text-slate-900 text-xs truncate mt-0.5">{{ item.title }}</h4>
                        <p class="font-extrabold text-slate-900 text-sm mt-1">
                            ${{ (item.price * item.quantity).toFixed(2) }}
                            <span class="text-[10px] font-normal text-slate-400">(${{ item.price.toFixed(2) }} each)</span>
                        </p>

                        <!-- Quantity Controls -->
                        <div class="flex items-center justify-between mt-2">
                            <div class="inline-flex items-center rounded-lg border border-slate-200 bg-white">
                                <button
                                    type="button"
                                    @click="updateQuantity(item.shopProductId, item.quantity - 1)"
                                    class="p-1 text-slate-500 hover:text-slate-900"
                                >
                                    <Minus class="w-3.5 h-3.5" />
                                </button>
                                <span class="px-2.5 text-xs font-bold text-slate-800">{{ item.quantity }}</span>
                                <button
                                    type="button"
                                    @click="updateQuantity(item.shopProductId, item.quantity + 1)"
                                    class="p-1 text-slate-500 hover:text-slate-900"
                                    :disabled="item.quantity >= item.stock"
                                >
                                    <Plus class="w-3.5 h-3.5" />
                                </button>
                            </div>

                            <button
                                type="button"
                                @click="removeFromCart(item.shopProductId)"
                                class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors"
                                title="Remove item"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty Cart State -->
            <div v-else class="h-full flex flex-col items-center justify-center text-center py-12 text-slate-400">
                <ShoppingBag class="w-12 h-12 mb-3 text-slate-300 stroke-1" />
                <p class="text-sm font-bold text-slate-700">Your cart is empty</p>
                <p class="text-xs text-slate-400 mt-1 max-w-xs">
                    Browse any shop and click "Add to Cart" on products you love.
                </p>
            </div>
        </div>

        <!-- Drawer Footer -->
        <div v-if="cartItems.length > 0 && !isCheckingOut && !orderCompleted" class="p-6 border-t border-slate-100 bg-slate-50/60 space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase">Subtotal</span>
                <span class="text-xl font-black text-slate-900">${{ cartTotal.toFixed(2) }}</span>
            </div>

            <div class="flex gap-2.5">
                <button
                    type="button"
                    @click="clearCart"
                    class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors"
                >
                    Clear
                </button>
                <button
                    type="button"
                    @click="isCheckingOut = true"
                    class="flex-1 inline-flex items-center justify-center gap-2 py-2.5 px-5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-md shadow-indigo-100 hover:bg-indigo-700 transition-colors"
                >
                    <span>Proceed to Checkout</span>
                    <ArrowRight class="w-4 h-4" />
                </button>
            </div>
        </div>
    </aside>
</template>
