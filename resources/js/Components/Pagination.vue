<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links && links.length > 3" class="flex flex-wrap items-center justify-center gap-1.5 my-4">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 rounded-lg select-none"
                v-html="link.label"
            />
            <Link
                v-else
                :href="link.url"
                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors"
                :class="[
                    link.active
                        ? 'bg-indigo-600 text-white shadow-sm'
                        : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                ]"
                v-html="link.label"
            />
        </template>
    </div>
</template>
