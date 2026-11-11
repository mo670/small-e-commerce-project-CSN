import { ref, computed, watch } from 'vue';

const STORAGE_KEY = 'shopsphere_cart_v1';

const loadInitialCart = () => {
    if (typeof window === 'undefined') return [];
    try {
        const saved = localStorage.getItem(STORAGE_KEY);
        return saved ? JSON.parse(saved) : [];
    } catch {
        return [];
    }
};

const cartItems = ref(loadInitialCart());
const isCartOpen = ref(false);
const toastMessage = ref('');
let toastTimer = null;

if (typeof window !== 'undefined') {
    watch(
        cartItems,
        (items) => {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
            } catch {
                // ignore storage errors
            }
        },
        { deep: true }
    );
}

const triggerToast = (msg) => {
    toastMessage.value = msg;
    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toastMessage.value = '';
    }, 3000);
};

export function useCart() {
    const addToCart = (shopProduct, shop, quantity = 1) => {
        const maxStock = Number(shopProduct.stock ?? 0);
        if (maxStock <= 0) {
            triggerToast('Sorry, this item is currently out of stock.');
            return;
        }

        const existingIndex = cartItems.value.findIndex(
            (item) => item.shopProductId === shopProduct.id
        );

        const title = shopProduct.product?.title || 'Product';
        const thumbnail = shopProduct.thumbnail || shopProduct.product?.thumbnail || '';
        const price = Number(shopProduct.price || 0);

        if (existingIndex > -1) {
            const current = cartItems.value[existingIndex];
            const nextQty = Math.min(current.quantity + quantity, maxStock);
            cartItems.value[existingIndex].quantity = nextQty;
        } else {
            cartItems.value.push({
                shopProductId: shopProduct.id,
                productId: shopProduct.product_id,
                title,
                thumbnail,
                price,
                stock: maxStock,
                quantity: Math.min(quantity, maxStock),
                shopName: shop?.name || shopProduct.shop?.name || 'Store',
                shopSlug: shop?.slug || shopProduct.shop?.slug || '',
            });
        }

        triggerToast(`Added "${title}" to your cart!`);
    };

    const updateQuantity = (shopProductId, newQty) => {
        const index = cartItems.value.findIndex((i) => i.shopProductId === shopProductId);
        if (index === -1) return;

        if (newQty <= 0) {
            cartItems.value.splice(index, 1);
            return;
        }

        const maxStock = cartItems.value[index].stock || 99;
        cartItems.value[index].quantity = Math.min(newQty, maxStock);
    };

    const removeFromCart = (shopProductId) => {
        cartItems.value = cartItems.value.filter((i) => i.shopProductId !== shopProductId);
    };

    const clearCart = () => {
        cartItems.value = [];
    };

    const cartCount = computed(() =>
        cartItems.value.reduce((sum, item) => sum + item.quantity, 0)
    );

    const cartTotal = computed(() =>
        cartItems.value.reduce((sum, item) => sum + item.price * item.quantity, 0)
    );

    const openCart = () => {
        isCartOpen.value = true;
    };

    const closeCart = () => {
        isCartOpen.value = false;
    };

    return {
        cartItems,
        isCartOpen,
        toastMessage,
        cartCount,
        cartTotal,
        addToCart,
        updateQuantity,
        removeFromCart,
        clearCart,
        openCart,
        closeCart,
        triggerToast,
    };
}

