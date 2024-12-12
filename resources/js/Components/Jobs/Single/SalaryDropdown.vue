<template>
    <div class="relative max-w-sm">
        <!-- Dropdown button with arrow -->
        <button
            @click="toggleForm"
            class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <span>Select Salary Range</span>
            <svg
                :class="{'rotate-180': showForm}"
                class="w-5 h-5 transition-transform duration-200"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown menu -->
        <div v-if="showForm" class="absolute w-full mt-2 p-4 bg-white border border-gray-300 rounded-md shadow-lg z-10">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Min salary</label>
                <input
                    v-model.number="minSalary"
                    type="number"
                    placeholder="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Max salary</label>
                <input
                    v-model.number="maxSalary"
                    type="number"
                    placeholder="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />
            </div>
            <div class="flex items-center justify-between">
                <button
                    class="bg-orange-500 text-white py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-600"
                    @click="updateSalary"
                >
                    Update
                </button>
                <p v-if="salaryRange" class="text-sm text-gray-700">Selected: {{ salaryRange }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const showForm = ref(false); // To toggle the dropdown display
const minSalary = ref(null); // Model for min salary input
const maxSalary = ref(null); // Model for max salary input

// Compute the salary range to display after updating
const salaryRange = computed(() => {
    if (minSalary.value || maxSalary.value) {
        return `${minSalary.value || 0} - ${maxSalary.value || 'No limit'}`;
    }
    return '';
});

// Toggle dropdown display
const toggleForm = () => {
    showForm.value = !showForm.value;
};

// Hide dropdown after updating
const updateSalary = () => {
    showForm.value = false; // Hide dropdown after updating
};
</script>

<style scoped>
/* Additional scoped styling if needed */
</style>
