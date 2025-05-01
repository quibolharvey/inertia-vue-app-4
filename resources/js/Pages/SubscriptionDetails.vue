<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const trashSubscription = (id) => {
  if (confirm('Are you sure you want to move this subscription to trash?')) {
    router.delete(route('subscription.destroy', id), {
      preserveScroll: true,
    });
  }
};


const page = usePage();

// Ensure admin is authenticated
onMounted(() => {
  if (page.props.auth.user.role !== 'admin') {
    router.visit('/dashboard');
  }
});

defineProps({ subscriptions: Object });

// Search
const search = ref('');
watch(search, (val) => {
  router.get(route('subscription-details'), { search: val }, {
    preserveScroll: true,
    preserveState: true,
    replace: true
  });
});

// Change status with loading state
const loadingStatus = ref(null);
const updateStatus = async (id, status) => {
  loadingStatus.value = id;
  try {
    await router.patch(`/subscription/${id}/status`, { status }, {
      preserveScroll: true,
    });
  } finally {
    loadingStatus.value = null;
  }
};

// Enhanced expiry computation with formatting
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

// Change page with pagination
const changePage = (url) => {
  if (url) {
    router.get(url, { search: search.value }, {
      preserveScroll: true,
      preserveState: true
    });
  }
};

// Format currency
const formatCurrency = (amount) => {
  return new Intl.NumberFormat('en-PH', {
    style: 'currency',
    currency: 'PHP',
    minimumFractionDigits: 2
  }).format(amount);
};
</script>

<template>
  <Head title="Subscription Details" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
          Subscription Management
        </h2>
        <!-- <div class="mt-2 md:mt-0 text-sm text-gray-600">
          Showing {{ subscriptions.from }}-{{ subscriptions.to }} of {{ subscriptions.total }} subscriptions
        </div> -->
        <div class="mt-2 md:mt-0 text-sm text-gray-600">
          Total Subscription: {{ subscriptions.total }}
        </div>
        
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6 sm:p-8">

            <!-- Search Bar -->
            <div class="mb-6">
              <input
                type="text"
                v-model="search"
                placeholder="Search by name or email..."
                class="w-full sm:max-w-sm px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <!-- Pagination Info -->
            <!-- <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
              <div class="text-sm text-gray-500">
                Page {{ subscriptions.current_page }} of {{ subscriptions.last_page }}
              </div>
            </div> -->

            <!-- Subscription Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="sub in subscriptions.data" :key="sub.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-blue-600 font-medium">{{ sub.full_name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ sub.full_name }}</div>
                          <div class="text-sm text-gray-500">{{ sub.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900 capitalize">{{ sub.subscription_type }}</div>
                      <div class="text-sm text-gray-500">
                        Membership:
                        <span :class="sub.has_membership ? 'text-green-600' : 'text-gray-500'">
                          {{ sub.has_membership ? 'Yes' : 'No' }}
                        </span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      {{ formatCurrency(sub.amount) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full capitalize"
                        :class="{
                          'bg-yellow-100 text-yellow-800': sub.status === 'pending',
                          'bg-green-100 text-green-800': sub.status === 'paid',
                          'bg-red-100 text-red-800': sub.status === 'expired'
                        }">
                        {{ sub.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ sub.expiry_date ?? computeExpiry(sub) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <div class="relative" :class="{ 'opacity-50': loadingStatus === sub.id }">
                        <select
                          class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                          :value="sub.status"
                          @change="e => updateStatus(sub.id, e.target.value)"
                          :disabled="loadingStatus === sub.id"
                        >
                          <option value="pending">Pending</option>
                          <option value="paid">Paid</option>
                          <option value="expired">Expired</option>
                        </select>
                         <!-- Trash Button -->
                          <button
                            @click="trashSubscription(sub.id)"
                            class="text-red-600 hover:text-red-800"
                            title="Move to Trash"
                          >
                            🗑️
                          </button>
                        <span v-if="loadingStatus === sub.id" class="absolute right-3 top-2">
                          <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                          </svg>
                        </span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Empty State -->
              <div v-if="subscriptions.data.length === 0" class="text-center py-12 bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No subscriptions found</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no subscription records available.</p>
              </div>
            </div>

            <!-- Pagination Controls -->
            <div v-if="subscriptions.last_page > 1" class="flex flex-col sm:flex-row items-center justify-between mt-8 mb-6 gap-4">
              <div class="text-sm text-gray-500">
                Page {{ subscriptions.current_page }} of {{ subscriptions.last_page }}
              </div>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="link in subscriptions.links"
                  :key="link.label"
                  :disabled="!link.url"
                  @click="() => changePage(link.url)"
                  v-html="link.label"
                  class="px-4 py-2 border rounded-md text-sm font-medium min-w-[40px] text-center"
                  :class="{
                    'bg-blue-600 text-white border-blue-600': link.active,
                    'text-gray-500 bg-gray-100 cursor-not-allowed': !link.url,
                    'text-gray-700 bg-white hover:bg-gray-50 border-gray-300': link.url && !link.active,
                  }"
                />
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
