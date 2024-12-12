<script setup>
const props = defineProps({
  educationalQualification: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <div class="bg-secondary-50 p-6 rounded-lg shadow-lg">
    <h2 class="text-lg font-semibold text-gray-900 mb-4">Academic Details</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div>
        <p class="font-medium text-gray-800">Qualification</p>
        <p class="text-gray-700">{{ educationalQualification.userDetail.qualification ?? "Not Specified" }}</p>
      </div>
      <div>
        <p class="font-medium text-gray-800">Specialized In</p>
        <p class="text-gray-700">{{ educationalQualification.userDetail.specializedIn ?? "Not Specified" }}</p>
      </div>
      <div>
        <p class="font-medium text-gray-800">Institute name</p>
        <p class="text-gray-700">{{ educationalQualification.userDetail.university ?? "Not Specified" }}</p>
      </div>
      <div>
        <p class="font-medium text-gray-800">Graduation year</p>
        <p class="text-gray-700">{{ educationalQualification.userDetail.yearOfGraduation ?? "Not Specified" }}</p>
      </div>
    </div>
  </div>
</template>
