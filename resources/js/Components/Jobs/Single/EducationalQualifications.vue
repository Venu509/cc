<script setup>
const props = defineProps({
  educationalQualification: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Educational Qualifications</h2>
      <p class="text-gray-600 dark:text-gray-400">Basic eduacational qualification summary.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">College</h3>
        <p class="text-gray-700 dark:text-gray-300 capitalize">
          {{ educationalQualification.userDetail.qualification ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Specialized In</h3>
        <p class="text-gray-700 dark:text-gray-300">
          {{ educationalQualification.userDetail.specializedIn ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">University</h3>
        <p class="text-gray-700 dark:text-gray-300">{{
            educationalQualification.userDetail.university ?? "Not Specified"
          }}</p>
      </div>
      <div class="col-span-1">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Year of Graduation</h3>
        <p class="text-gray-700 dark:text-gray-300 capitalize">
          {{ educationalQualification.userDetail.yearOfGraduation ?? "Not Specified" }}</p>
      </div>
      <div class="col-span-1" v-if="educationalQualification.userDetail.additionalQualification">
        <h3 class="text-lg font-semibold text-secondary-700 dark:text-secondary-300">Additional Qualification</h3>
        <p class="text-gray-700 dark:text-gray-300">
          {{ educationalQualification.userDetail.additionalQualification ?? "Not Specified" }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* No additional scoped styles needed; Tailwind CSS handles all styling */
</style>
