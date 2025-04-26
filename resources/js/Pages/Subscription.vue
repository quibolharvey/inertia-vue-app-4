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

    return expiry.toLocaleDateString(); // or use `toDateString()`
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
        expiry_date: expiryDate.value, // Include in payload
    });
};
</script>

<template>
    <Head title="Subscription" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Subscription</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="handleSubmit">
                            <!-- Full Name -->
                            <div class="mb-4">
                                <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input
                                    type="text"
                                    id="fullName"
                                    v-model="fullName"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required
                                />
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input
                                    type="email"
                                    id="email"
                                    v-model="email"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required
                                />
                            </div>

                            <!-- Subscription Type -->
                            <div class="mb-4">
                                <label for="subscriptionType" class="block text-sm font-medium text-gray-700">Subscription Type</label>
                                <select
                                    id="subscriptionType"
                                    v-model="subscriptionType"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    required
                                >
                                    <option value="" disabled>Select Subscription Type</option>
                                    <option value="daily">Daily - ₱150</option>
                                    <option value="monthly">Monthly - ₱900</option>
                                    <option value="yearly">Yearly - ₱9000</option>
                                </select>
                            </div>

                            <!-- Membership Checkbox -->
                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input
                                        type="checkbox"
                                        v-model="applyMembership"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                    />
                                    <span class="ml-2 text-sm text-gray-700">Apply Membership? ₱400</span>
                                </label>
                            </div>

                            <!-- Expiry Display -->
                            <div v-if="expiryDate" class="mb-4">
                                <p><strong>Expiry Date:</strong> {{ expiryDate }}</p>
                            </div>

                            <!-- Submit Button -->
                            <div class="mb-4">
                                <button
                                    type="submit"
                                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600"
                                >
                                    Subscribe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
