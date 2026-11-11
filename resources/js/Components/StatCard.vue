<script setup>
defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [Number, String],
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    colorClass: {
        type: String,
        default: 'bg-indigo-50 text-indigo-600',
    },
});
</script>

<template>
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center justify-between">
        <div class="space-y-1">
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">{{ title }}</p>
            <p class="text-2xl font-bold text-gray-900 tracking-tight">{{ value }}</p>
            <p v-if="subtitle" class="text-xs text-gray-400">{{ subtitle }}</p>
        </div>

        <div class="p-3 rounded-xl flex items-center justify-center" :class="colorClass">
            <slot name="icon" />
        </div>
    </div>
</template>

