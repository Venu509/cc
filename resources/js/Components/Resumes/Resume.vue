<template>
<!--  <div-->
<!--      class="bg-white shadow-md rounded-lg p-6 mb-5 flex flex-wrap lg:flex-nowrap justify-between items-start space-x-0 lg:space-x-4 space-y-4 lg:space-y-0 transition duration-300 delay-150 hover:delay-300 ease-in-out">-->

<!--    <div class="w-full lg:w-4/5">-->
<!--      <h2 class="text-lg font-semibold text-gray-800 mb-2">{{ resume.name }}</h2>-->

<!--      <div class="flex flex-wrap space-x-2 text-sm text-gray-600 mb-2">-->
<!--        <p><span class="font-bold">Experience:</span> <span class="font-medium">{{ totalWorkExperiences }}</span></p>-->
<!--        <p><span class="font-bold">Salary:</span> <span class="font-medium">{{ resume.userDetail?.expectedSalary ?? 'Not Specified' }}</span></p>-->
<!--      </div>-->

<!--      <div class="text-sm text-gray-600 mb-4">-->
<!--        <div class="flex items-center space-x-2 mb-2">-->
<!--          <LocationMarkerIcon class="w-5 h-5 text-red-500"/>-->
<!--          <p>{{ preferredLocation ?? 'Not Specified' }}</p>-->
<!--        </div>-->
<!--        <p class="mb-1"><span class="font-bold">Previous Company:</span> <span class="font-medium">{{ latestCompany ? latestCompany.company : 'Not Specified' }}</span></p>-->
<!--        <p class="mb-1"><span class="font-bold">Education:</span> <span class="font-medium">{{ resume.userDetail?.qualification ?? 'Not Specified' }}</span></p>-->

<!--        <div class="mb-1">-->
<!--          <div class="flex flex-wrap gap-2 mt-2">-->
<!--            <span class="font-bold">Key Skills: </span>-->
<!--            <span v-if="resume.keySkills.length > 0"-->
<!--                  v-for="(skill, index) in resume.keySkills"-->
<!--                  :key="index"-->
<!--                  class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">-->
<!--              {{ skill.title }}-->
<!--            </span>-->
<!--            <span v-else-->
<!--                  class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">-->
<!--              Not Specified-->
<!--            </span>-->
<!--          </div>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->

<!--    <div class="w-full lg:w-1/5 flex flex-col items-center space-y-2 text-center">-->
<!--      <div>-->
<!--        <p class="text-sm text-gray-600">Can join in</p>-->
<!--        <p class="text-lg font-semibold text-gray-800">-->
<!--          {{ resume.userDetail?.noticePeriod ?? 'Not Specified' }}</p>-->
<!--      </div>-->

<!--      <div>-->
<!--        <img :src="resume.avatar" alt="Profile Image" class="w-20 h-20 rounded-full object-cover mb-2"/>-->
<!--        <p class="text-sm text-gray-600">{{ latestCompany ? latestCompany.jobTitle : 'Not Specified' }}</p>-->
<!--      </div>-->

<!--      <Button btn-size="xxm" btn-color="blue" @click="viewResume">-->
<!--        View Profile-->
<!--      </Button>-->
<!--    </div>-->
<!--  </div>-->

  <div class="w-full mx-auto p-4 bg-secondary-50 shadow-md rounded-lg flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6 mb-4  transform transition duration-300 hover:shadow-lg">
    <!-- Profile Image -->
    <img class="w-20 h-20 sm:w-24 sm:h-24 rounded-full" :src="resume.avatar" :alt="resume.name">

    <!-- User Details -->
    <div class="flex-1">
      <h3 class="text-xl font-semibold text-gray-800">{{ resume.name }}</h3>
      <p class="text-sm text-gray-600">Experience : &nbsp; {{ resume.userDetail?.noOfExperiences}}</p>
      <p class="text-sm text-gray-600 flex flex-wrap ">
        Skills : &nbsp;
        <span v-if="resume.keySkills.length > 0"
              v-for="(skill, index) in resume.keySkills"
              :key="index"
              class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400 mr-1 mt-1">
              {{ skill.title }}
            </span>
      </p>
      <p class="text-sm text-gray-600">Education : &nbsp; {{ resume.userDetail?.qualification ?? 'Not Specified' }}</p>
    </div>

    <div>
      <Button btn-size="xm" btn-color="blue" @click="viewResume">
        View
      </Button>
    </div>
  </div>

</template>

<script setup>
import { computed } from "vue";
import Button from "@/Components/Button.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  resume: {
    type: Object,
    required: true
  },
  routeName: {
    type: String,
    default: null
  }
});

const computedRouteName = computed(() => {
  return props.routeName || route('admin.resumes.show', { user: props.resume.id });
});

const latestCompany = computed(() => {
  return props.resume.workExperiences?.[0];
});

const preferredLocation = computed(() => {
  return props.resume.userDetail.preferredJobLocation ?? props.resume.userDetail.country;
});

function viewResume() {
  Inertia.visit(computedRouteName.value);
}
</script>
