<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
  isLifetime: false,
  amount: 500,
});

const submitForm = () => {
  if (!form.isLifetime) {
    alert("Please check the box to apply for Lifetime Membership.");
    return;
  }

  form.post('/membership-submit', {
    onSuccess: () => {
      alert('Lifetime Membership applied successfully!');
    },
  });
};
</script>

<template>
  <Head title="Lifetime Membership" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Lifetime Membership
      </h2>
    </template>

    <div class="py-8">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
          <form @submit.prevent="submitForm">
            <div class="mb-4 flex items-center">
              <input
                id="isLifetime"
                type="checkbox"
                v-model="form.isLifetime"
                class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
              />
              <label for="isLifetime" class="ml-2 block text-sm text-gray-900">
                I agree to apply for Lifetime Membership (₱500)
              </label>
            </div>

            <input type="hidden" :value="form.amount" />

            <div class="text-right">
              <button
                type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700"
              >
                Submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
