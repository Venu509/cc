<template>
  <AppLayout :title="`Resume : ${resume.name}`">
    <div class="w-full mx-auto p-4 sm:p-6">
      <div class="flex justify-between items-center mb-4">
        <button type="button" @click="goBack" class="font-bold text-gray-500 hover:text-gray-800">← Back</button>
        <div class="flex space-x-4">
          <a :href="`tel:${resume.phone}`" class="px-3 py-1 text-white bg-secondary-600 rounded-full hover:bg-secondary-800">Call</a>
        </div>
      </div>
      <PersonalInformation :personal-information="resume"/>

      <!-- Work Experience -->
      <div class="bg-secondary-50 p-6 rounded-lg shadow-lg mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Work Experience</h2>
        <WorkExperience :work-experiences="resume?.workExperiences"/>
      </div>

      <!-- Skills -->
      <div class="bg-secondary-50 p-6 rounded-lg shadow-lg mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Skills</h2>
        <p class="text-gray-600 flex flex-wrap gap-1">
          <span v-if="resume.keySkills.length > 0"
                v-for="(skill, index) in resume.keySkills"
                :key="index"
                class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400 mr-1 mt-1">
              {{ skill.title }}
            </span>
        </p>
      </div>

      <!-- Resume -->
      <AttachedResume :url="resume.userDetail?.resume"/>

      <!-- Academic Details -->
      <EducationalQualifications :educational-qualification="resume"/>
    </div>
  </AppLayout>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import AttachedResume from "@/Components/Resumes/Single/AttachedResume.vue";
import WorkExperience from "@/Components/Resumes/Single/WorkExperience.vue";
import PersonalInformation from "@/Components/Resumes/Single/PersonalInformation.vue";
import EducationalQualifications from "@/Components/Resumes/Single/EducationalQualifications.vue";
import Button from "@/Components/Button.vue";

const activeTab = ref('resume');

const props = defineProps({
  resume: {
    type: Object,
    required: true
  }
})

const tabs = [
  {value: 'resume', label: 'Attached CV', icon: 'DocumentAddIcon'},
  {value: 'personal-information', label: 'Personal Information', icon: 'UserCircleIcon'},
  {value: 'educational-qualification', label: 'Educational Qualification', icon: 'AcademicCapIcon'},
  {value: 'work-experiences', label: 'Work Experiences', icon: 'GlobeAltIcon'},
  {value: 'applied-jobs', label: 'Applied Jobs', icon: 'DocumentTextIcon'},
];

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

function goBack(){
  if (window.history.length > 1){
    window.history.back();
  }
}
</script>

<style scoped>
/* Add any custom styles if necessary */
</style>
