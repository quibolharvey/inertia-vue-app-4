<script setup>
import { ref, watch } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const page = usePage();

onMounted(() => {
  if (page.props.auth.user.role !== 'admin') {
    router.visit('/dashboard'); // redirect if not admin
  }
});

</script>

<template>
  <Head title="Subscription Details" />

  <AuthenticatedLayout>
    <h1>yawa</h1>
  </AuthenticatedLayout>
</template>
