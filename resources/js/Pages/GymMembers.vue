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
const { members, searchTerm } = usePage().props;
const searchQuery = ref(searchTerm || '');

// Watch search input
watch(searchQuery, (newSearchTerm) => {
  router.get('/gym-members', { search: newSearchTerm }, { preserveState: true, replace: true });
});

// Handle Search Button Click
const handleSearch = () => {
  router.get('/gym-members', { search: searchQuery.value }, { preserveState: true, replace: true });
};

// Change page button clicked
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
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Gym Members
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

            <!-- Search Bar -->
            <div class="mb-6 flex justify-center">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Search members..."
                  class="w-72 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
                <button
                  @click="handleSearch"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700 transition"
                >
                  Search
                </button>
              </div>
            </div>

            <!-- Pagination Top -->
            <div class="flex justify-end mb-4 space-x-2">
              <button
                v-for="link in members.links"
                :key="link.label"
                v-html="link.label"
                :disabled="!link.url"
                @click="() => changePage(link.url)"
                class="px-3 py-1 border rounded text-sm"
                :class="{
                  'bg-blue-500 text-white': link.active,
                  'text-gray-500': !link.url,
                }"
              />
            </div>

            <!-- Member Table -->
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-4 py-2 text-left">Name</th>
                  <th class="px-4 py-2 text-left">Email</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="member in members.data" :key="member.id">
                  <td class="px-4 py-2">{{ member.full_name }}</td>
                  <td class="px-4 py-2">{{ member.email }}</td>
                </tr>
              </tbody>
            </table>

            <!-- No Members Found -->
            <div v-if="members.data.length === 0" class="text-center mt-4">
              <p>No members found matching the search criteria.</p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
