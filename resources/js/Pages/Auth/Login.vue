<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

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

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Sign In | Fitspire" />

        <div class="w-full max-w-md mx-auto">
            <h1 class="text-2xl font-light text-center mb-8 tracking-wide">FITSPIRE</h1>

            <div v-if="status" class="mb-4 text-sm text-center text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full border-b border-gray-300 py-2 focus:border-black focus:outline-none"
                        placeholder="Email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.email" />
                </div>

                <div>
                    <TextInput
                        id="password"
                        type="password"
                        class="block w-full border-b border-gray-300 py-2 focus:border-black focus:outline-none"
                        placeholder="Password"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm text-gray-600 hover:text-black"
                    >
                        Forgot password?
                    </Link>
                </div>

                <button
                    type="submit"
                    class="w-full bg-black text-white py-2 px-4 hover:bg-gray-800 transition"
                    :disabled="form.processing"
                >
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Don't have an account? 
                <Link :href="route('register')" class="text-black hover:underline">
                    Join Now
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>