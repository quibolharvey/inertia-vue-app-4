<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const page = usePage();
const subscriptions = ref(page.props.subscriptions);

const restoreSubscription = (id) => {
  router.post(route('subscriptions.restore', id), { 
    preserveScroll: true,
    onSuccess: () => {
      subscriptions.value = subscriptions.value.filter(s => s.id !== id);
      // Optionally notify or update state elsewhere
    }
  });
};

const permanentlyDelete = (id) => {
  if (confirm('Are you sure you want to permanently delete this subscription? This action cannot be undone.')) {
    router.delete(route('subscriptions.force-delete', id), {
      preserveScroll: true,
      onSuccess: () => {
        subscriptions.value = subscriptions.value.filter(s => s.id !== id);
      }
    });
  }
};

onMounted(() => {
  if (page.props.auth.user.role !== 'admin') {
    router.visit('/dashboard');
  }
});
</script>

<template>
  <Head title="Trashed Subscriptions" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
          Trashed Subscriptions
        </h2>
        <div class="mt-2 md:mt-0 text-sm text-gray-600">
          Total Trashed: {{ subscriptions.length }}
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6 sm:p-8">

            <!-- Subscription Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscription</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="sub in subscriptions" :key="sub.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-blue-600 font-medium">{{ sub.user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ sub.user.name }}</div>
                          <div class="text-sm text-gray-500">{{ sub.user.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900 capitalize">{{ sub.subscription_type }}</div>
                      <div class="text-sm text-gray-500">
                        Created: {{ new Date(sub.created_at).toLocaleDateString() }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(sub.deleted_at).toLocaleDateString() }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                      <button
                        @click="restoreSubscription(sub.id)"
                        class="px-3 py-1 text-sm rounded bg-blue-50 text-blue-600 hover:bg-blue-100"
                      >
                        Restore
                      </button>
                      <button
                        @click="permanentlyDelete(sub.id)"
                        class="px-3 py-1 text-sm rounded bg-red-50 text-red-600 hover:bg-red-100"
                      >
                        Delete Permanently
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Empty State -->
              <div v-if="subscriptions.length === 0" class="text-center py-12 bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No trashed subscriptions</h3>
                <p class="mt-1 text-sm text-gray-500">There are currently no subscriptions in the trash.</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
