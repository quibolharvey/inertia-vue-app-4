<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">My Subscription</h2>
        </template>

        <div class="py-12">
            <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Info</h3>
                <ul class="space-y-2 text-gray-700">
                    <li><strong>Full Name:</strong> {{ subscription.full_name }}</li>
                    <li><strong>Email:</strong> {{ subscription.email }}</li>
                    <li><strong>Type:</strong> {{ subscription.subscription_type }}</li>
                    <li><strong>Lifetime:</strong> {{ subscription.lifetime ? 'Yes' : 'No' }}</li>
                    <li><strong>Price:</strong> ₱{{ subscription.price }}</li>
                    <li><strong>Expires:</strong> {{ subscription.expiry_date }}</li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
defineProps({ subscription: Object })
</script>
