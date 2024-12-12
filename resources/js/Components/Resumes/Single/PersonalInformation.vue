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
  <div class="bg-secondary-50 p-6 rounded-lg shadow-lg mb-6">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Personal details</h2>
    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-6">
      <img class="w-24 h-24 rounded-full"  :src="personalInformation.avatar" alt="Profile Image">

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
          <p class="font-medium text-gray-800">Full name</p>
          <p class="text-gray-700">{{ personalInformation.name }}</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">DOB</p>
          <p class="text-gray-700">{{ formatDate(personalInformation.userDetail.dob) }}</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Phone number</p>
          <p class="text-gray-700"><a :href="`tel:${personalInformation.phone}`" class="text-blue-500">{{ personalInformation.phone }}</a></p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Gender</p>
          <p class="text-gray-700 capitalize">{{ personalInformation.userDetail.gender ?? "Not Specified" }}</p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Email</p>
          <p class="text-gray-700"> <a :href="`mailto:${personalInformation.email}`" class="text-blue-500">{{ personalInformation.email }}</a></p>
        </div>
        <div>
          <p class="font-medium text-gray-800">Address</p>
          <p class="text-gray-700">
            {{ personalInformation.userDetail.street }},
            {{ personalInformation.userDetail.city }},
            {{ personalInformation.userDetail.state }},
            {{ personalInformation.userDetail.postalCode }},
            {{ personalInformation.userDetail.country }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
/* Optional: Add any additional styling or overrides here */
</style>
