<template>
  <div class="container mx-auto cursor-grab"
       type="button"
       data-drawer-target="resume-right-drawer"
       data-drawer-show="resume-right-drawer"
       data-drawer-placement="right"
       aria-controls="resume-right-drawer"
       @click="chooseCandidate"
  >
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 bg-white p-6 rounded-lg shadow-lg mb-3">

      <div class="col-span-1 md:col-span-2 flex justify-center items-center">
        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full bg-gray-200 flex items-center justify-center">
          <img :src="resume.avatar" :alt="resume.name" class="w-full h-full rounded-full object-cover"/>
        </div>
      </div>

      <div class="col-span-1 md:col-span-7 flex flex-col justify-between">
        <div>
          <h2 class="text-2xl font-semibold">{{ resume.name }}</h2>
          <p class="text-gray-600">
            <strong>Experience: </strong> {{ totalWorkExperiences }} <br/>
            <strong>Salary: </strong> {{ resume.userDetail?.expectedSalary ?? 'Not Specified' }}<br/>
            <strong>Previous: </strong>
            <span v-if="latestCompany">
                {{ latestCompany ? latestCompany.jobTitle : 'Not Specified' }} at
                {{ latestCompany ? latestCompany.company : 'Not Specified' }}
              </span>
            <span v-else>
                Not Specified
              </span>
            <br/>
            <strong>Education: </strong> {{ resume.userDetail?.qualification ?? 'Not Specified' }}<br/>
          </p>

          <div class="mb-1">
            <div class="flex flex-wrap gap-2 my-2">
              <strong class="text-gray-600">Key Skills : </strong>
              <span v-if="resume.keySkills.length > 0"
                    v-for="(skill, index) in resume.keySkills"
                    :key="index"
                    class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">
                {{ skill.title }}
              </span>

              <span v-else
                    class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">
                Not Specified
              </span>
            </div>

            <strong class="text-gray-600">Applicant Status : </strong>

            <RoleWidget :title="resume.vacancyStatus" :color="resume.vacancyStatusColor" class="capitalize"/>
          </div>
        </div>
      </div>

      <div class="col-span-1 md:col-span-3 flex flex-col items-center justify-center bg-secondary-500/5 p-4 rounded-lg">
        <p class="text-lg font-semibold">Skill matched</p>
        <p class="text-3xl font-bold text-red-500">{{ resume.matchedPercentage ?? 0 }}%</p>
        <p class="text-lg font-semibold mt-4">Can join in</p>
        <p class="text-xl font-bold text-green-500">{{ resume.userDetail?.noticePeriod ?? 'Not Specified' }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed} from "vue";
import {Inertia} from "@inertiajs/inertia";
import RoleWidget from "@/Components/Widgets/RoleWidget.vue";

const emit = defineEmits(['choose-candidate'])

const props = defineProps({
  resume: {
    type: Object,
    required: true
  }
});

const totalWorkExperiences = computed(() => {
  const {years, months} = props.resume.totalWorkExperiences;
  let totalExperiences = '';

  if (years > 0) {
    totalExperiences += `${years} Year${years > 1 ? 's' : ''}`;
  }

  if (months > 0) {
    totalExperiences += `${years > 0 ? ' ' : ''}${months} Month${months > 1 ? 's' : ''}`;
  }

  return totalExperiences || 'Not Yet';
});

const latestCompany = computed(() => {
  return props.resume.workExperiences?.[0]
})

const preferredLocation = computed(() => {
  return props.resume.userDetail.preferredJobLocation ?? props.resume.userDetail.country;
})

function viewResume() {
  Inertia.visit(route('admin.resumes.show', {user: props.resume.id, tab: 'resume'}));
}

function chooseCandidate() {
  return emit("choose-candidate", {
    resume: props.resume,
  });
}
</script>

<style scoped>
/* Add any custom styles here */
</style>
