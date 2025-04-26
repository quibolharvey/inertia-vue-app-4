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
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Registered Accounts
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

            <!-- Search Form -->
            <div class="mb-6 flex justify-center">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Search by name..."
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
                v-for="link in users.links"
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

            <!-- Table of Registered Users -->
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th class="px-4 py-2 text-left">Name</th>
                  <th class="px-4 py-2 text-left">Email</th>
                  <th class="px-4 py-2 text-left">Created At</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="user in users.data" :key="user.id">
                  <td class="px-4 py-2">{{ user.name }}</td>
                  <td class="px-4 py-2">{{ user.email }}</td>
                  <td class="px-4 py-2">{{ user.created_at }}</td>
                </tr>
              </tbody>
            </table>

            <!-- If no users -->
            <div v-if="users.data.length === 0" class="text-center mt-4">
              <p>No registered users found.</p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
