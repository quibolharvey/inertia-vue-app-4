<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const attendanceStatus = ref(null);
const checkInTime = ref(null);
const checkOutTime = ref(null);
const currentTime = ref(new Date().toLocaleTimeString());
const today = ref(new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }));
const notes = ref('');
const showNotesField = ref(false);
const attendanceRecords = ref([ 
]);

// Update time every second
setInterval(() => {
  currentTime.value = new Date().toLocaleTimeString();
}, 1000);

const checkIn = () => {
  attendanceStatus.value = 'checked-in';
  checkInTime.value = new Date().toLocaleTimeString();
  showNotesField.value = true;
};

const checkOut = () => {
  attendanceStatus.value = 'checked-out';
  checkOutTime.value = new Date().toLocaleTimeString();
  
  // Add to records
  attendanceRecords.value.unshift({
    date: new Date().toLocaleDateString(),
    checkIn: checkInTime.value,
    checkOut: checkOutTime.value,
    notes: notes.value
  });
  
  // Reset notes
  notes.value = '';
  showNotesField.value = false;
  
  // Here you would typically send this to your backend
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Welcome to Fitspire Gym
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Attendance Checker Section -->
                <div class="overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 rounded-lg shadow-xl mb-8">
                    <div class="p-8 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-3xl font-bold mb-2">Attendance Checker</h1>
                                <p class="text-lg mb-4">{{ today }} • {{ currentTime }}</p>
                            </div>
                            <div class="bg-white/20 rounded-full px-4 py-2 text-sm font-medium">
                                {{ attendanceStatus ? attendanceStatus.replace('-', ' ') : 'Not checked in' }}
                            </div>
                        </div>
                        
                        <div class="mt-6 flex space-x-4">
                            <button 
                                @click="checkIn"
                                :disabled="attendanceStatus === 'checked-in'"
                                :class="{
                                    'bg-green-500 hover:bg-green-600': attendanceStatus !== 'checked-in',
                                    'bg-gray-400 cursor-not-allowed': attendanceStatus === 'checked-in'
                                }"
                                class="text-white px-6 py-3 rounded-lg font-semibold transition flex items-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Check In
                            </button>
                            <button 
                                @click="checkOut"
                                :disabled="attendanceStatus !== 'checked-in'"
                                :class="{
                                    'bg-red-500 hover:bg-red-600': attendanceStatus === 'checked-in',
                                    'bg-gray-400 cursor-not-allowed': attendanceStatus !== 'checked-in'
                                }"
                                class="text-white px-6 py-3 rounded-lg font-semibold transition flex items-center"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Check Out
                            </button>
                        </div>

                        <!-- Notes Field (shown after check-in) -->
                        <div v-if="showNotesField" class="mt-4">
                            <label for="notes" class="block text-sm font-medium text-blue-100 mb-1">Add Notes (optional)</label>
                            <textarea
                                id="notes"
                                v-model="notes"
                                rows="2"
                                class="w-full px-3 py-2 text-gray-800 rounded-lg focus:ring-2 focus:ring-blue-300"
                                placeholder="E.g. Today's workout plan, how you're feeling, etc."
                            ></textarea>
                            <p class="text-xs text-blue-200 mt-1">These notes will be saved with your attendance record</p>
                        </div>

                        <div v-if="checkInTime || checkOutTime" class="mt-6 bg-white/10 rounded-lg p-4">
                            <h3 class="font-medium mb-2">Today's Attendance Record</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-blue-200">Check In</p>
                                    <p class="font-bold">{{ checkInTime || '--:--:--' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-blue-200">Check Out</p>
                                    <p class="font-bold">{{ checkOutTime || '--:--:--' }}</p>
                                </div>
                            </div>
                            <div v-if="notes && attendanceStatus === 'checked-out'" class="mt-3">
                                <p class="text-sm text-blue-200">Notes</p>
                                <p class="font-medium">{{ notes }}</p>
                            </div>
                        </div>
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
                        <h3 class="text-gray-500 font-medium">This Week's Attendance</h3>
                        <p class="text-3xl font-bold">4/5</p>
                        <p class="text-sm text-green-600 mt-1">80% of goal</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-purple-500">
                        <h3 class="text-gray-500 font-medium">Average Duration</h3>
                        <p class="text-3xl font-bold">1h 15m</p>
                        <p class="text-sm text-gray-600 mt-1">Last week: 1h 05m</p>
                    </div>
                </div>

                <!-- Recent Activity & Quick Actions -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-lg font-semibold mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            Recent Attendance
                        </h3>
                        <ul class="space-y-4">
                            <li v-for="(record, index) in attendanceRecords" :key="index" class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ record.date }}</p>
                                        <p class="text-sm text-gray-500">{{ record.checkIn }} - {{ record.checkOut }}</p>
                                    </div>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        {{ record.checkOut ? 'Completed' : 'In Progress' }}
                                    </span>
                                </div>
                                <div v-if="record.notes" class="mt-2 bg-gray-50 p-2 rounded text-sm text-gray-700">
                                    <p class="font-medium">Notes:</p>
                                    <p>{{ record.notes }}</p>
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
                                <span>View Stats</span>
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Attendance History</span>
                            </button>
                            <button class="bg-gray-100 hover:bg-gray-200 p-4 rounded-lg transition flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Set Reminder</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>