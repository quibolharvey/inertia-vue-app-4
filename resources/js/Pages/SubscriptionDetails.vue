<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

//new
const page = usePage();

onMounted(() => {
  if (page.props.auth.user.role !== 'admin') {
    router.visit('/dashboard'); // redirect if not admin
  }
});
defineProps({ subscriptions: Object }); // Update subscriptions to be an Object, not an Array

// Change status
const updateStatus = (id, status) => {
  router.patch(`/subscription/${id}/status`, { status }, {
    preserveScroll: true,
  });
};

// Fallback expiry computation
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

// Change page with pagination
const changePage = (url) => {
  if (url) {
    router.get(url, {}, { preserveScroll: true, preserveState: true });
  }
};
</script>

<template>
  <Head title="Subscription Details" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Subscription Details (Admin)
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            
            <!-- Pagination Controls at the top -->
            <div class="flex justify-end mb-4 space-x-2">
              <button
                v-for="link in subscriptions.links"
                :key="link.label"
                :disabled="!link.url"
                @click="() => changePage(link.url)"
                v-html="link.label"
                class="px-3 py-1 border rounded text-sm"
                :class="{
                  'bg-blue-500 text-white': link.active,
                  'text-gray-500': !link.url,
                }"
              />
            </div>

            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-4 py-2 text-left">Name</th>
                  <th class="px-4 py-2 text-left">Email</th>
                  <th class="px-4 py-2 text-left">Type</th>
                  <th class="px-4 py-2 text-left">Membership</th>
                  <th class="px-4 py-2 text-left">Amount</th>
                  <th class="px-4 py-2 text-left">Status</th>
                  <th class="px-4 py-2 text-left">Expiry</th>
                  <th class="px-4 py-2 text-left">Change Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="sub in subscriptions.data" :key="sub.id">
                  <td class="px-4 py-2">{{ sub.full_name }}</td>
                  <td class="px-4 py-2">{{ sub.email }}</td>
                  <td class="px-4 py-2 capitalize">{{ sub.subscription_type }}</td>
                  <td class="px-4 py-2">{{ sub.has_membership ? 'Yes' : 'No' }}</td>
                  <td class="px-4 py-2">₱{{ sub.amount }}</td>
                  <td class="px-4 py-2">{{ sub.status }}</td>
                  <td class="px-4 py-2">
                    {{ sub.expiry_date ?? computeExpiry(sub) }}
                  </td>
                  <td class="px-4 py-2">
                    <select
                      class="rounded border-gray-300"
                      :value="sub.status"
                      @change="e => updateStatus(sub.id, e.target.value)"
                    >
                      <option value="pending">Pending</option>
                      <option value="paid">Paid</option>
                      <option value="expired">Expired</option>
                    </select>
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
