<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },      
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Fitspire Gym" />
    <div class="min-h-screen bg-gradient-to-br from-gray-300 to-white flex flex-col items-center justify-center p-4">
        <main class="text-center max-w-xs w-full">
            <h1 class="text-2xl font-light text-gray-800 mb-8 tracking-wide">FITSPIRE</h1>
            
            <div class="space-y-3">
                <template v-if="canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="block w-full bg-black text-white font-normal py-2 px-4 hover:bg-gray-800 transition-colors"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="block w-full bg-black text-white font-normal py-2 px-4 hover:bg-gray-800 transition-colors"
                        >
                            Join Now
                        </Link>
                        
                        <Link
                            v-if="canLogin"
                            :href="route('login')"
                            class="block w-full border border-black text-black font-normal py-2 px-4 hover:bg-gray-300 transition-colors"
                        >
                            Sign In
                        </Link>
                    </template>
                </template>
            </div>
        </main>
    </div>
</template>