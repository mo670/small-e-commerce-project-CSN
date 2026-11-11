<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { CheckCircle2, AlertCircle, X, AlertTriangle } from 'lucide-vue-next';

const page = usePage();
const visible = ref(false);
const message = ref('');
const type = ref('success');
let timer = null;

const showToast = (msg, msgType = 'success') => {
    message.value = msg;
    type.value = msgType;
    visible.value = true;
    if (timer) clearTimeout(timer);
    timer = setTimeout(() => {
        visible.value = false;
    }, 4500);
};

watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        showToast(flash.success, 'success');
    } else if (flash?.error) {
        showToast(flash.error, 'error');
    } else if (flash?.warning) {
        showToast(flash.warning, 'warning');
    }
}, { deep: true, immediate: true });
</script>

<template>
    <div
        v-if="visible"
        class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-xl text-white transition-all transform animate-in slide-in-from-bottom-5 duration-200"
        :class="{
            'bg-emerald-600': type === 'success',
            'bg-rose-600': type === 'error',
            'bg-amber-600': type === 'warning'
        }"
    >
        <CheckCircle2 v-if="type === 'success'" class="w-5 h-5 flex-shrink-0" />
        <AlertCircle v-else-if="type === 'error'" class="w-5 h-5 flex-shrink-0" />
        <AlertTriangle v-else class="w-5 h-5 flex-shrink-0" />

        <span class="text-sm font-medium">{{ message }}</span>

        <button
            @click="visible = false"
            class="ml-2 p-1 text-white/80 hover:text-white rounded-lg hover:bg-white/10 transition-colors"
        >
            <X class="w-4 h-4" />
        </button>
    </div>
</template>

