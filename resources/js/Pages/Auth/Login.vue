<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ShieldCheck, Store, Sparkles } from 'lucide-vue-next';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const fillDemo = (email, password = 'password') => {
    form.email = email;
    form.password = password;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <!-- 1-Click Quick Demo Login Pill Box -->
        <div class="mb-6 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-100 space-y-2.5">
            <div class="flex items-center gap-1.5 text-xs font-bold text-indigo-900 uppercase tracking-wider">
                <Sparkles class="w-3.5 h-3.5 text-indigo-600" />
                <span>Quick Evaluation Logins</span>
            </div>
            <p class="text-[11px] text-indigo-700 leading-normal">
                Click any role to autofill evaluation test credentials (password: <code>password</code>):
            </p>
            <div class="flex flex-wrap gap-2 pt-1">
                <button
                    type="button"
                    @click="fillDemo('admin@example.com')"
                    class="px-2.5 py-1.5 rounded-lg bg-indigo-600 text-white text-xs font-bold shadow-xs hover:bg-indigo-700 transition-colors flex items-center gap-1"
                >
                    <ShieldCheck class="w-3.5 h-3.5" />
                    <span>Admin</span>
                </button>
                <button
                    type="button"
                    @click="fillDemo('techowner@example.com')"
                    class="px-2.5 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-bold shadow-xs hover:bg-violet-700 transition-colors flex items-center gap-1"
                >
                    <Store class="w-3.5 h-3.5" />
                    <span>Tech Shop Owner</span>
                </button>
                <button
                    type="button"
                    @click="fillDemo('fashionowner@example.com')"
                    class="px-2.5 py-1.5 rounded-lg bg-purple-600 text-white text-xs font-bold shadow-xs hover:bg-purple-700 transition-colors flex items-center gap-1"
                >
                    <Store class="w-3.5 h-3.5" />
                    <span>Fashion Shop Owner</span>
                </button>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('home')"
                    class="text-xs text-slate-500 hover:text-slate-800"
                >
                    ← Back to Storefront
                </Link>

                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
