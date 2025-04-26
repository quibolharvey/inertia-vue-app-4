<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Welcome to PowerFit Gym
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Hero Welcome Section -->
                <div class="overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-xl mb-8">
                    <div class="p-8 text-white">
                        <h1 class="text-3xl font-bold mb-2">Welcome back, Champion!</h1>
                        <p class="text-lg mb-4">Ready for today's workout? Let's crush your fitness goals together!</p>
                        <button class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition">
                            Start Today's Workout
                        </button>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-orange-500">
                        <h3 class="text-gray-500 font-medium">Current Streak</h3>
                        <p class="text-3xl font-bold">7 days</p>
                        <p class="text-sm text-green-600 mt-1">+2 from last week</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-blue-500">
                        <h3 class="text-gray-500 font-medium">Workouts This Week</h3>
                        <p class="text-3xl font-bold">4/5</p>
                        <p class="text-sm text-green-600 mt-1">80% of goal</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                        <h3 class="text-gray-500 font-medium">Next Class</h3>
                        <p class="text-3xl font-bold">HIIT @ 6pm</p>
                        <p class="text-sm text-gray-600 mt-1">Today in 3 hours</p>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Recent Activity
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-start">
                                <div class="bg-green-100 p-2 rounded-full mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium">Completed: Upper Body Workout</p>
                                    <p class="text-sm text-gray-500">Yesterday at 5:30 PM • 45 mins</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <div class="bg-blue-100 p-2 rounded-full mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium">Joined: Yoga Class</p>
                                    <p class="text-sm text-gray-500">2 days ago • 12 participants</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                            </svg>
                            Quick Actions
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>Book Class</span>
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                <span>Track Workout</span>
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>My Progress</span>
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Messages</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>