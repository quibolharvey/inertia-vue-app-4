<script setup>
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';


const { subscriptions } = usePage().props;

// Enhanced expiry computation with better formatting
function computeExpiry(subscription) {
  const createdAt = new Date(subscription.created_at);
  let expiry = new Date(createdAt);

  switch (subscription.subscription_type) {
    case 'daily':
      expiry.setDate(createdAt.getDate() + 1);
      break;
    case 'monthly':
      expiry.setMonth(createdAt.getMonth() + 1);
      break;
    case 'yearly':
      expiry.setFullYear(createdAt.getFullYear() + 1);
      break;
  }

  return expiry.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
}

// Format currency with PHP symbol
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 2
  }).format(amount);
};

// Get status color class
const getStatusColor = (status) => {
  switch (status) {
    case 'active':
    case 'paid':
      return 'bg-green-100 text-green-800';
    case 'pending':
      return 'bg-yellow-100 text-yellow-800';
    case 'expired':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};
</script>

<template>
  <Head title="My Subscriptions" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-bold text-gray-800">My Subscriptions</h2>
        <div class="mt-2 md:mt-0 text-sm text-gray-600">
          {{ subscriptions.length }} subscription(s)
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6 sm:p-8">
            <!-- Empty State -->
            <div v-if="subscriptions.length === 0" class="text-center py-12">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <h3 class="mt-2 text-lg font-medium text-gray-900">No active subscriptions</h3>
              <p class="mt-1 text-sm text-gray-500">You don't have any active subscriptions yet.</p>
              <div class="mt-6">
                <Link 
  href="/subscribe" 
  class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
>
  Subscribe Now
</Link>

              </div>
            </div>

            <!-- Subscription List -->
            <div v-else class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Subscription
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Details
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Expiry
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="subscription in subscriptions" :key="subscription.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-blue-600 font-medium">
                            {{ subscription.subscription_type.charAt(0).toUpperCase() }}
                          </span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900 capitalize">
                            {{ subscription.subscription_type }} Plan
                          </div>
                          <div class="text-sm text-gray-500">
                            {{ formatCurrency(subscription.amount) }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ subscription.full_name }}</div>
                      <div class="text-sm text-gray-500">
                        Membership: 
                        <span :class="subscription.has_membership ? 'text-green-600' : 'text-gray-500'">
                          {{ subscription.has_membership ? 'Included' : 'Not included' }}
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                        :class="getStatusColor(subscription.status)">
                        {{ subscription.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ subscription.expiry_date ?? computeExpiry(subscription) }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Additional Help Section -->
            <!-- <div v-if="subscriptions.length > 0" class="mt-8 border-t border-gray-200 pt-6">
              <h3 class="text-lg font-medium text-gray-900">Need help with your subscription?</h3>
              <p class="mt-2 text-sm text-gray-600">
                Contact our support team if you have any questions about your subscription.
              </p>
              <div class="mt-4">
                <router-link 
                  to="/support" 
                  class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Contact Support
                </router-link>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>