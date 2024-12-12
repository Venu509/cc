<script setup>
const props = defineProps({
  personalInformation: {
    type: Object,
    required: true
  }
});

function formatDate(date) {
  const options = {year: 'numeric', month: 'long', day: 'numeric'};
  return new Date(date).toLocaleDateString(undefined, options);
}
</script>

<template>
  <div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Personal Information</h2>
      <p class="text-gray-600 dark:text-gray-400">Basic details and professional summary.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Full Name</h3>
        <p class="text-gray-700 dark:text-gray-300">{{ personalInformation.userDetail.fullName ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Gender</h3>
        <p class="text-gray-700 dark:text-gray-300 capitalize">{{ personalInformation.userDetail.gender ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Date of Birth</h3>
        <p class="text-gray-700 dark:text-gray-300">{{ formatDate(personalInformation.userDetail.dob) }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Age</h3>
        <p class="text-gray-700 dark:text-gray-300">{{ personalInformation.userDetail.age ?? "Not Specified" }}
          years</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Marital Status</h3>
        <p class="text-gray-700 dark:text-gray-300 capitalize">{{ personalInformation.userDetail.maritalStatus ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Address</h3>
        <p class="text-gray-700 dark:text-gray-300">
          {{ personalInformation.userDetail.street }},
          {{ personalInformation.userDetail.city }},
          {{ personalInformation.userDetail.state }},
          {{ personalInformation.userDetail.postalCode }},
          {{ personalInformation.userDetail.country }}
        </p>
      </div>
    </div>
  </div>
</template>

<style>
/* Optional: Add any additional styling or overrides here */
</style>
