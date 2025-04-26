<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const { subscriptions } = usePage().props;

// Optional: compute real-time expiry fallback
function computeExpiry(subscription) {
  const createdAt = new Date(subscription.created_at);
  let expiry = new Date(createdAt);

  switch (subscription.subscription_type) {
    case 'daily':
      expiry.setDate(createdAt.getDate() + 1);
      break;
    case 'monthly':
      expiry.setDate(createdAt.getDate() + 30);
      break;
    case 'yearly':
      expiry.setFullYear(createdAt.getFullYear() + 1);
      break;
  }

  return expiry.toLocaleDateString();
}
</script>

<template>
  <Head title="My Subscriptions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">My Subscriptions</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div v-if="subscriptions.length === 0">
              No subscriptions found.
            </div>

            <table v-else class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-4 py-2 text-left">Name</th>
                  <th class="px-4 py-2 text-left">Type</th>
                  <th class="px-4 py-2 text-left">Membership</th>
                  <th class="px-4 py-2 text-left">Amount</th>
                  <th class="px-4 py-2 text-left">Status</th>
                  <th class="px-4 py-2 text-left">Expiry Date</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="subscription in subscriptions" :key="subscription.id">
                  <td class="px-4 py-2">{{ subscription.full_name }}</td>
                  <td class="px-4 py-2 capitalize">{{ subscription.subscription_type }}</td>
                  <td class="px-4 py-2 capitalize">
                    {{ subscription.has_membership ? 'Yes' : 'No' }}
                  </td>
                  <td class="px-4 py-2">₱{{ subscription.amount }}</td>
                  <td class="px-4 py-2 capitalize">{{ subscription.status }}</td>
                  <td class="px-4 py-2">
                    {{ subscription.expiry_date ?? computeExpiry(subscription) }}
                  </td>
                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
