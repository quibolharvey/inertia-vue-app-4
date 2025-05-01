<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const fullName = ref('');
const email = ref('');
const subscriptionType = ref('');
const applyMembership = ref(false);

// Realtime expiry date logic
const expiryDate = computed(() => {
    const now = new Date();
    let expiry;

    switch (subscriptionType.value) {
        case 'daily':
            expiry = new Date(now.setDate(now.getDate() + 1));
            break;
        case 'monthly':
            expiry = new Date(now.setDate(now.getDate() + 30));
            break;
        case 'yearly':
            expiry = new Date(now.setFullYear(now.getFullYear() + 1));
            break;
        default:
            return '';
    }

    return expiry.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
});

const handleSubmit = () => {
    let basePrice = 0;
    switch (subscriptionType.value) {
        case 'daily':
            basePrice = 150;
            break;
        case 'monthly':
            basePrice = 900;
            break;
        case 'yearly':
            basePrice = 9000;
            break;
    }

    const membershipFee = applyMembership.value ? 400 : 0;
    const total = basePrice + membershipFee;

    router.post('/subscription', {
        full_name: fullName.value,
        email: email.value,
        subscription_type: subscriptionType.value,
        has_membership: applyMembership.value,
        amount: total,
        expiry_date: expiryDate.value,
    });
};
</script>

<template>
    <Head title="Subscription" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col space-y-1">
                <h2 class="text-2xl font-bold text-gray-900">Gym Subscription</h2>
                <p class="text-sm text-gray-500">Choose your preferred membership plan</p>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-md sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-2xl">
                    <div class="px-8 py-10">
                        <form @submit.prevent="handleSubmit" class="space-y-6">
                            <!-- Full Name -->
                            <div>
                                <label for="fullName" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input
                                    type="text"
                                    id="fullName"
                                    v-model="fullName"
                                    class="block w-full rounded-lg border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150"
                                    placeholder="Enter your full name"
                                    required
                                />
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="email"
                                    class="block w-full rounded-lg border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150"
                                    placeholder="your@email.com"
                                    required
                                />
                            </div>

                            <!-- Subscription Type -->
                            <div>
                                <label for="subscriptionType" class="block text-sm font-medium text-gray-700 mb-1">Subscription Plan</label>
                                <select
                                    id="subscriptionType"
                                    v-model="subscriptionType"
                                    class="block w-full rounded-lg border-gray-300 py-3 px-4 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition duration-150 appearance-none bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0IiBmaWxsPSJub25lIiBzdHJva2U9IiAjdjEwMTcyNCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxwb2x5bGluZSBwb2ludHM9IjYgOSAxMiAxNSAxOCA5Ij48L3BvbHlsaW5lPjwvc3ZnPg==')] bg-no-repeat bg-[center_right_1rem]"
                                    required
                                >
                                    <option value="" disabled selected>Select your plan</option>
                                    <option value="daily" class="py-2">Daily - ₱150</option>
                                    <option value="monthly" class="py-2">Monthly - ₱900</option>
                                    <option value="yearly" class="py-2">Yearly - ₱9,000</option>
                                </select>
                            </div>

                            <!-- Membership Checkbox -->
                            <div class="relative flex items-start">
                                <div class="flex h-5 items-center">
                                    <input
                                        id="membership"
                                        type="checkbox"
                                        v-model="applyMembership"
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="membership" class="font-medium text-gray-700">Premium Membership</label>
                                    <p class="text-gray-500">Includes access to all facilities (+₱400)</p>
                                </div>
                            </div>

                            <!-- Expiry Display -->
                            <div v-if="expiryDate" class="rounded-lg bg-blue-50 p-4 border border-blue-100">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Subscription Details</h3>
                                        <div class="mt-1 text-sm text-blue-700">
                                            <p>Your access will be valid until: <span class="font-semibold">{{ expiryDate }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button
                                    type="submit"
                                    class="flex w-full justify-center rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 py-3 px-4 text-sm font-semibold text-white shadow-sm hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 transform hover:-translate-y-0.5"
                                >
                                    Complete Subscription
                                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>