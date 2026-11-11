<script setup>
import { ref } from 'vue';
import { UploadCloud, Link as LinkIcon, Trash2, Image as ImageIcon } from 'lucide-vue-next';

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },
    label: {
        type: String,
        default: 'Image / Thumbnail',
    },
    placeholder: {
        type: String,
        default: 'https://images.unsplash.com/... or upload image',
    },
});

const emit = defineEmits(['update:modelValue']);
const uploading = ref(false);
const errorMsg = ref('');
const fileInput = ref(null);

const handleFileUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    uploading.value = true;
    errorMsg.value = '';

    const formData = new FormData();
    formData.append('image', file);

    try {
        const response = await fetch('/api/upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
            },
        });

        if (!response.ok) {
            throw new Error('Upload failed. Please check image size (<2MB) and format.');
        }

        const data = await response.json();
        emit('update:modelValue', data.url);
    } catch (err) {
        errorMsg.value = err.message || 'Image upload error';
    } finally {
        uploading.value = false;
    }
};

const clearImage = () => {
    emit('update:modelValue', '');
    if (fileInput.value) fileInput.value.value = '';
};
</script>

<template>
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">{{ label }}</label>

        <!-- Preview and Input Group -->
        <div class="flex items-start gap-4">
            <!-- Preview Box -->
            <div
                class="w-20 h-20 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden flex items-center justify-center bg-gray-50 flex-shrink-0 relative group"
            >
                <img
                    v-if="modelValue"
                    :src="modelValue"
                    alt="Preview"
                    class="w-full h-full object-cover"
                />
                <div v-else class="text-gray-400 flex flex-col items-center">
                    <ImageIcon class="w-6 h-6 stroke-1" />
                    <span class="text-[10px] mt-1">No image</span>
                </div>

                <button
                    v-if="modelValue"
                    type="button"
                    @click="clearImage"
                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white transition-opacity"
                    title="Remove Image"
                >
                    <Trash2 class="w-5 h-5 text-rose-300" />
                </button>
            </div>

            <!-- Input options: Upload file or Paste URL -->
            <div class="flex-1 space-y-2">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <LinkIcon class="w-4 h-4" />
                        </div>
                        <input
                            type="text"
                            :value="modelValue"
                            @input="$emit('update:modelValue', $event.target.value)"
                            :placeholder="placeholder"
                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>

                    <label
                        class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors flex-shrink-0"
                    >
                        <UploadCloud class="w-4 h-4 text-gray-500" />
                        <span>{{ uploading ? 'Uploading...' : 'Upload' }}</span>
                        <input
                            ref="fileInput"
                            type="file"
                            accept="image/*"
                            class="hidden"
                            @change="handleFileUpload"
                            :disabled="uploading"
                        />
                    </label>
                </div>

                <p v-if="errorMsg" class="text-xs text-rose-600 font-medium">{{ errorMsg }}</p>
                <p class="text-xs text-gray-500">Paste direct image URL or upload JPEG, PNG, WEBP (max 2MB).</p>
            </div>
        </div>
    </div>
</template>
