<script setup>
import { ref } from 'vue';
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

const { users, search } = usePage().props; // users is now paginated
const searchQuery = ref(search || '');     // search query from props

// Search button clicked
const handleSearch = () => {
  router.get(route('registered-account'), { search: searchQuery.value }, { preserveState: true, replace: true });
};

// Change page clicked
const changePage = (url) => {
  if (url) {
    router.get(url, { search: searchQuery.value }, { preserveState: true, replace: true });
  }
};
</script>

<template>
  <Head title="Registered Accounts" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
          Registered Accounts
        </h2>
        <div class="mt-2 md:mt-0 text-sm text-gray-600">
          Total Users: {{ users.total }}
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6 sm:p-8">
            <!-- Search Form -->
            <div class="mb-8">
              <div class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
                <div class="flex-grow relative">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search by name or email..."
                    class="block w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    @keyup.enter="handleSearch"
                  />
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <button
                      @click="handleSearch"
                      class="px-4 py-2 bg-blue-600 text-white rounded-md shadow hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                      Search
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <!-- Table of Registered Users -->
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Registered Date
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-blue-600 font-medium">{{ user.name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ user.email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ new Date(user.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Empty State -->
              <div v-if="users.data.length === 0" class="text-center py-12 bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search query to find what you're looking for.</p>
              </div>
            </div>

            <!-- Pagination -->
<div v-if="users.links.length > 3" class="mt-6 flex items-center justify-between">
  <div class="text-sm text-gray-500">
    Showing <span class="font-medium">{{ users.from }}</span> to <span class="font-medium">{{ users.to }}</span> of <span class="font-medium">{{ users.total }}</span> results
  </div>
  <div class="flex space-x-2">
    <button
      v-for="link in users.links"
      :key="link.label"
      v-html="link.label"
      :disabled="!link.url"
      @click="() => changePage(link.url)"
      class="px-4 py-2 border rounded-md text-sm font-medium"
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