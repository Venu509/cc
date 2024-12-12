<template>
  <div
      ref="rightDrawer"
      id="resume-right-drawer"
      class="overflow-auto fixed top-0 right-0 z-40 h-screen w-96 p-6 bg-white shadow-xl"
  >
    <div class="flex justify-between items-center mb-6">
      <div
          class="bg-gradient-to-r from-secondary-100 via-secondary-200 to-secondary-300 text-xl font-bold px-2 rounded">
        <h3 class="text-gray-800">Applied for {{ vacancy.data.title }}</h3>
      </div>

      <button type="button"
              @click="handleClickOutside"
              data-drawer-hide="resume-right-drawer"
              aria-controls="resume-right-drawer"
              class="fixed text-white bg-red-600 hover:bg-red-500 hover:text-gray-300 rounded-lg text-sm w-8 h-8 top-4 end-2.5 inline-flex items-center justify-center dark:hover:bg-red-600 dark:hover:text-white">
        <XIcon class="w-4 h-4"/>
        <span class="sr-only">Close menu</span>
      </button>
    </div>

    <section class="mb-8">
      <h4 class="font-semibold text-lg text-gray-700">Personal Details</h4>
      <ul class="mt-2 space-y-1">
        <li><strong>Full Name:</strong> {{ resume?.userDetail.fullName }}</li>
        <li><strong>DOB:</strong> {{ resume?.userDetail.dob }}</li>
        <li><strong>Age:</strong> {{ resume?.userDetail.age }}</li>
        <li><strong>Gender:</strong> {{ resume?.userDetail.gender }}</li>
        <li><strong>Phone:</strong> {{ resume?.phone }}</li>
        <li><strong>Email:</strong> {{ resume?.email }}</li>
        <li><strong>Address:</strong> {{ resume?.userDetail.address }}</li>
      </ul>
    </section>

    <section class="mb-8">
      <h4 class="font-semibold text-lg text-gray-700">Work Experience</h4>
      <div class="my-2 items-center p-4 border border-gray-400 rounded-lg"
           v-if="resume?.workExperiences.length > 0" v-for="(experience, index) in resume?.workExperiences"
           :key="index">
        <strong>Job Title:</strong> {{ experience.jobTitle }}<br/>
        <strong>Company:</strong> {{ experience.company }} <br/>
        <strong>Period:</strong> {{ formatDate(experience.startDate) }} -
        {{ experience.isStillWorking ? 'Present' : formatDate(experience.endDate) }}
      </div>

      <div class="my-2" v-else>
        <p>No Related Work Experiences added.</p>
      </div>
    </section>

    <section class="mb-8">
      <h4 class="font-semibold text-lg text-gray-700 mb-2">Skills</h4>
      <div class="flex flex-wrap gap-2 my-2">
        <span v-if="resume?.keySkills.length > 0"
              v-for="(skill, index) in resume?.keySkills"
              :key="index"
              class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">
                {{ skill.title }}
              </span>

        <span v-else
              class="rounded-full bg-indigo-100 text-indigo-800 text-xs font-medium px-1.5 py-0.5 dark:bg-gray-700 dark:text-indigo-400 border border-indigo-400">
            Not Specified
          </span>
      </div>
    </section>

    <section class="mb-8">
      <h4 class="font-semibold text-lg text-gray-700">Resume</h4>

      <div class="flex mt-2">
        <div class="w-20 h-20 relative overflow-hidden">
          <div class="absolute inset-0 flex items-center justify-center">
            <div v-if="fileUrl">
              <div v-if="isPDF">
                <iframe :src="fileUrl" class="w-full h-full object-cover" frameborder="0"></iframe>
              </div>
              <div v-else>
                <div v-html="docxContent" class="w-full h-full overflow-auto"></div>
              </div>
            </div>
            <div v-else>
              <p class="text-xs text-gray-500">No CV attached.</p>
            </div>
          </div>
        </div>

        <div class="flex-shrink-0 ml-4">
          <a :href="fileUrl" target="_blank" download class="text-blue-600 cursor-pointer">
            <CloudDownloadIcon class="w-5 h-5"/>
          </a>
        </div>
      </div>
    </section>

    <section class="mb-8">
      <h4 class="font-semibold text-lg text-gray-700">Academic Details</h4>
      <p class="mt-2">
        <strong>Collage:</strong> {{ resume?.userDetail.qualification ?? "Not Specified" }} <br/>
        <strong>Specialized In:</strong> {{ resume?.userDetail.specializedIn ?? "Not Specified" }} <br/>
        <strong>University:</strong> {{ resume?.userDetail.university ?? "Not Specified" }} <br/>
        <strong>Graduation Year:</strong> {{ resume?.userDetail.yearOfGraduation ?? "Not Specified" }}
      </p>
    </section>

    <section class="mb-8" v-show="resume?.questions">
      <h4 class="font-semibold text-lg text-gray-700 mb-2">Answered Questions</h4>

    <AnsweredQuestions :answered-questions="resume?.questions"/>

    </section>

    <section class="mb-8" v-show="resume?.applicantTracking">
      <h4 class="font-semibold text-lg text-gray-700 mb-2">Application Tracking</h4>

      <Tracking :histories="resume?.applicantTracking"/>
    </section>

    <div class="flex space-x-4 mt-auto">
      <Button
          v-if="applicationState !== 'rejected' "
          @click="changeApplicantStatus('rejected')"
          theme="block"
          class="flex flex-1"
          btn-size="xxm"
          btn-color="red">
        Reject
      </Button>

      <Button
          v-if="applicationState !== 'shortlisted' "
          @click="changeApplicantStatus('shortlisted')"
          theme="block"
          class="flex flex-1"
          btn-size="xxm"
          btn-color="green">
        Shortlist
      </Button>

      <Button
          v-if="applicationState === 'shortlisted' || applicationState === 'rejected' "
          @click="changeApplicantStatus('viewed')"
          theme="block"
          class="flex flex-1"
          btn-size="xxm"
          btn-color="yellow">
        Move to Pending
      </Button>
    </div>

    <div class="flex space-x-4 mt-auto">
      <Button
          @click="viewProfile"
          class="flex flex-1 mt-2"
          btn-size="xxm"
          btn-color="dark">
        View Full Profile
      </Button>
    </div>
  </div>
</template>

<script setup>
import {computed, onBeforeUnmount, onMounted, ref, watch} from "vue";
import mammoth from "mammoth";
import {CloudDownloadIcon, XIcon, ArrowDownIcon} from "@heroicons/vue/solid";
import Button from "@/Components/Button.vue";
import Tracking from "@/Components/Vacancies/Candidates/Tracking.vue";
import AnsweredQuestions from "@/Components/Vacancies/Partials/AnsweredQuestions.vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
  resume: {
    type: Object,
    required: true,
  },
  vacancy: {
    type: Object,
    required: true,
  },
  drawerVisible: {
    type: Boolean,
    default: false,
  },
});

let applicationState = computed(() => {
  return props.resume ? props.resume.vacancyStatus : 'applied';
})

let emit = defineEmits(['change-applicant-status', 'close'])

function changeApplicantStatus(status) {
  emit('change-applicant-status', {
    status: status,
    resume: props.resume,
    vacancy: props.vacancy
  })
}

function formatDate(date) {
  const options = {year: 'numeric', month: 'short'};
  return new Date(date).toLocaleDateString(undefined, options);
}

const fileUrl = ref(null);
const docxContent = ref(null);
const isPDF = ref(false);
const rightDrawer = ref(null);
const isDropdownOpen = ref(false);

watch(() => props.resume, async (newValue) => {
  let resumeUrl = newValue?.userDetail?.resume;
  if (resumeUrl) {
    const fileExtension = resumeUrl.split('.').pop().toLowerCase();
    if (fileExtension === 'pdf') {
      isPDF.value = true;
      fileUrl.value = resumeUrl;
    } else if (fileExtension === 'docx') {
      isPDF.value = false;
      try {
        const response = await fetch(resumeUrl);
        const arrayBuffer = await response.arrayBuffer();
        const result = await mammoth.convertToHtml({arrayBuffer});
        docxContent.value = result.value;
      } catch (error) {
        console.error("Error fetching or converting DOCX:", error);
      }
    }
  }
}, {deep: true, immediate: true});

const handleClickOutside = (event) => {
  if (rightDrawer.value && !rightDrawer.value.contains(event.target)) {
    isDropdownOpen.value = false;
  }
  // closeDrawer()
};

function viewProfile() {
  Inertia.visit(route('admin.resumes.show', { user: props.resume.id, tab: 'resume' }));
}

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

function openDrawer() {
  rightDrawer.value.classList.remove('translate-x-full');
  rightDrawer.value.classList.add('translate-x-0');
}

function closeDrawer() {
  rightDrawer.value.classList.remove('translate-x-0');
  rightDrawer.value.classList.add('translate-x-full');
  emit('close')
}

watch(() => rightDrawer, (newVal) => {
  if (newVal) openDrawer();
});

watch(props.drawerVisible, () => {
  if(props.drawerVisible) {
    openDrawer()
  }
}, {immediate: true, deep: true});
</script>
