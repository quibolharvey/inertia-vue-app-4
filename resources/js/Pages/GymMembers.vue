<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const page = usePage();

onMounted(() => {
  if (page.props.auth.user.role !== 'admin') {
    router.visit('/dashboard');
  }
});

const { members, searchTerm } = usePage().props;
const searchQuery = ref(searchTerm || '');

// Debounced search
let searchTimeout = null;
watch(searchQuery, (newSearchTerm) => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    router.get('/gym-members', { search: newSearchTerm }, { preserveState: true, replace: true });
  }, 300);
});

const handleSearch = () => {
  clearTimeout(searchTimeout);
  router.get('/gym-members', { search: searchQuery.value }, { preserveState: true, replace: true });
};

const changePage = (url) => {
  if (url) {
    router.get(url, { search: searchQuery.value }, { preserveState: true, replace: true });
  }
};
</script>

<template>
  <Head title="Gym Members" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <h2 class="text-2xl font-bold text-gray-800">
          Gym Members
        </h2>
        <div class="mt-2 md:mt-0 text-sm text-gray-600">
          Total Members: {{ members.total }}
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl">
          <div class="p-6 sm:p-8">
            <!-- Search Section -->
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

            <!-- Members Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Member
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Contact
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="member in members.data" :key="member.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                          <span class="text-blue-600 font-medium">{{ member.full_name.charAt(0).toUpperCase() }}</span>
                        </div>
                        <div class="ml-4">
                          <div class="text-sm font-medium text-gray-900">{{ member.full_name }}</div>
                          <div class="text-sm text-gray-500">ID: {{ member.id }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm text-gray-900">{{ member.email }}</div>
                      <div class="text-sm text-gray-500">{{ member.phone || 'No phone' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Active
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>

              <!-- Empty State -->
              <div v-if="members.data.length === 0" class="text-center py-12 bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No members found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search query to find what you're looking for.</p>
              </div>
            </div>

            <!-- Pagination -->
<div v-if="members.total > members.per_page" class="mt-6 flex flex-col sm:flex-row items-center justify-between">
  <div class="text-sm text-gray-500 mb-4 sm:mb-0">
    Showing <span class="font-medium">{{ members.from }}</span> to <span class="font-medium">{{ members.to }}</span> of <span class="font-medium">{{ members.total }}</span> members
  </div>
  <div class="flex flex-wrap gap-2">
    <button
      v-for="link in members.links"
      :key="link.label"
      v-html="link.label"
      :disabled="!link.url"
      @click="() => changePage(link.url)"
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