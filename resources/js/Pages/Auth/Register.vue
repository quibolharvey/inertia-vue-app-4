<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Join Fitspire" />

        <div class="w-full max-w-md mx-auto px-4">
            <h1 class="text-2xl font-light text-center mb-8 tracking-wide">JOIN FITSPIRE</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <TextInput
                        id="name"
                        type="text"
                        class="block w-full border-b border-gray-300 py-2 focus:border-black focus:outline-none"
                        placeholder="Full Name"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.name" />
                </div>

                <div>
                    <TextInput
                        id="email"
                        type="email"
                        class="block w-full border-b border-gray-300 py-2 focus:border-black focus:outline-none"
                        placeholder="Email"
                        v-model="form.email"
                        required
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
                        autocomplete="new-password"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password" />
                </div>

                <div>
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="block w-full border-b border-gray-300 py-2 focus:border-black focus:outline-none"
                        placeholder="Confirm Password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                    <InputError class="mt-1 text-xs" :message="form.errors.password_confirmation" />
                </div>

                <button
                    type="submit"
                    class="w-full bg-black text-white py-3 px-4 hover:bg-gray-800 transition mt-6"
                    :disabled="form.processing"
                >
                    CREATE ACCOUNT
                </button>

                <div class="text-center text-sm text-gray-600 pt-4">
                    Already a member? 
                    <Link :href="route('login')" class="text-black hover:underline">
                        Sign In
                    </Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>