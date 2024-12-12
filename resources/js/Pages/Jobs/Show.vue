<template>
  <AppLayout title="Job">
    <div class="flex justify-between items-center cursor-pointer">
      <div class="text-lg flex space-x-2 text-gray-700" @click="goBack">
        <ArrowLeftIcon class="w-6 h-6 text-black hover:text-gray-500 mt-1"/>
        <p>Back</p>
      </div>

      <div class="flex space-x-2">
        <div class="w-12 h-12 ml-1.5 rounded-full  bg-white flex items-center justify-center cursor-pointer"
             @click="addToSave">
          <filledBookmarkIcon v-if="vacancy.data.isSaved === false" class="w-8 h-6 text-gray-400 hover:text-gray-500"/>
          <filledBookmarkIcon v-if="vacancy.data.isSaved"
                              class="w-8 h-6 ml-1.5 cursor-pointer text-green-400 hover:text-green-500"/>
        </div>
        <div class="w-12 h-12 ml-1.5 rounded-full  bg-white flex items-center justify-center cursor-pointer">
          <ShareIcon class="w-8 h-6 text-secondary-700 hover:text-secondary-800"/>
        </div>
      </div>
    </div>

    <div class="mt-1">
      <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
          <div class="bg-gray-300 h-12 w-12 rounded-full"></div>
          <div>
            <h2 class="text-lg font-semibold capitalize">{{ vacancy.data.title }}</h2>
            <p class="text-secondary-700 capitalize">{{ vacancy.data.company.name }}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-4 rounded-lg grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
      <div class="flex flex-col items-center justify-center text-center">
        <SparklesIcon class="w-6 h-6 text-indigo-600"/>
        <span class="text-gray-700 text-sm">{{ vacancy.data.yearsOfExperiences ?? '0' }} Years Of Experience</span>
      </div>

      <div class="flex flex-col items-center justify-center text-center">
        <CurrencyRupeeIcon class="w-6 h-6 text-indigo-600"/>
        <span class="text-gray-700 text-sm">{{ vacancy.data.salary }} {{ vacancy.data.salaryFrequency }} </span>
      </div>

      <div class="flex flex-col items-center justify-center text-center">
        <LocationMarkerIcon class="w-6 h-6 text-indigo-600"/>
        <span class="text-gray-700 text-sm">
                    <span v-for="(location, index) in vacancy.data.locations" :key="index">
                      {{ location }}<span>, locations </span>
                    </span>
                </span>
      </div>

      <div class="flex flex-col items-center justify-center text-center">
        <LightBulbIcon class="w-6 h-6 text-indigo-600"/>
        <span class="text-gray-700 text-sm">
                    <span v-for="(keySkill, index) in vacancy.data.keySkills" :key="index">
                     {{ keySkill.title }}<span>, skills </span>
                    </span>
                </span>
      </div>
    </div>

    <div class="flex justify-between items-center mt-2 flex-wrap">
      <div class="text-gray-400 flex items-center">
        apply before
      </div>
      <div class="">
        1 day ago
      </div>
    </div>

    <div class="w-full bg-white shadow-md rounded-lg p-6 mt-5">
      <div class="flex space-x-2">
        <button
            @click="activeTab = 'description'"
            :class="{
                      'text-white bg-blue-800': activeTab === 'description',
                      'text-gray-500 bg-gray-100': activeTab !== 'description'
                    }"
            class="px-4 py-4 flex-1 text-center rounded-xl"
        >
          Description
        </button>
        <button
            @click="activeTab = 'jobBrief'"
            :class="{
                      'text-white bg-blue-800': activeTab === 'jobBrief',
                      'text-gray-500 bg-gray-100': activeTab !== 'jobBrief'
                    }"
            class="px-4 py-4 flex-1 text-center rounded-xl"
        >
          Job brief
        </button>
        <button
            @click="activeTab = 'contactDetails'"
            :class="{
                      'text-white bg-blue-800': activeTab === 'contactDetails',
                      'text-gray-500 bg-gray-100': activeTab !== 'contactDetails'
                    }"
            class="px-4 py-4 flex-1 text-center rounded-xl"
        >
          Contact details
        </button>
        <button
            v-if="vacancy.data.isApplied === true"
            @click="activeTab = 'timeLine'"
            :class="{
                      'text-white bg-blue-800': activeTab === 'timeLine',
                      'text-gray-500 bg-gray-100': activeTab !== 'timeLine'
                    }"
            class="px-4 py-4 flex-1 text-center rounded-xl"
        >
          Application Status
        </button>
      </div>

      <div class="p-4">
        <div v-if="activeTab === 'description'" class="text-gray-700">
          <p v-html="vacancy.data.description"></p>
        </div>
        <div v-if="activeTab === 'jobBrief'">
          <div class="w-full p-6">
            <div class="space-y-4">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <LightningBoltIcon class="w-5 h-5 text-blue-600"/>
                  </div>
                </div>
                <div>
                  <h3 class="font-semibold text-black">Job category</h3>
                  <p class="text-gray-600">{{ vacancy.data.category.parent.title }} - {{
                      vacancy.data.category.title
                    }}</p>
                </div>
              </div>

              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <AcademicCapIcon class="w-5 h-5 text-blue-600"/>
                  </div>
                </div>
                <div>
                  <h3 class="font-semibold text-black">Required qualifications</h3>
                  <p class="text-gray-600"><span v-for="qualification in vacancy.data.qualifications">{{
                      qualification
                    }}, </span></p>
                </div>
              </div>

              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <PuzzleIcon class="w-5 h-5 text-blue-600"/>
                  </div>
                </div>
                <div>
                  <h3 class="font-semibold text-black">Work modes</h3>
                  <p class="text-gray-600"><span v-for="workMode in vacancy.data.workModes">{{ workMode }}, </span></p>
                </div>
              </div>

              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <LocationMarkerIcon class="w-5 h-5 text-blue-600"/>
                  </div>
                </div>
                <div>
                  <h3 class="font-semibold text-black">Locations</h3>
                  <p class="text-gray-600"><span v-for="location in vacancy.data.locations">{{ location }}, </span></p>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div v-if="activeTab === 'contactDetails'" class="text-gray-700">
          <div class="w-full p-6">
            <div class="space-y-4">
              <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                  <div class="bg-blue-100 p-2 rounded-full">
                    <MailIcon class="w-5 h-5 text-blue-600"/>
                  </div>
                </div>
                <div>
                  <h3 class="font-semibold text-black">Email</h3>
                  <p class="text-gray-600">{{ vacancy.data.company.email }} </p>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div v-if="activeTab === 'timeLine'" class="text-gray-700">

          <div class="bg-gray-50 p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Application Status</h2>
            <div class="space-y-8">
              <div v-for="(statusList, date) in vacancy.data.applicantTracking.history" :key="date">
                <h3 class="text-sm font-semibold text-gray-600 mb-3 border-b pb-1">{{ date }}</h3>

                <div class="space-y-4">
                  <div
                      v-for="(statusItem, index) in statusList"
                      :key="index"
                      class="flex items-start space-x-4 p-4 bg-white rounded-lg shadow-sm border border-gray-200"
                  >
                    <img
                        :src="statusItem.accessedBy.avatar"
                        :alt="statusItem.accessedBy.name"
                        class="w-12 h-12 rounded-full border-2 border-blue-500 shadow-sm"
                    />
                    <div class="flex-1">
                      <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                          <div
                              class="w-2.5 h-2.5 rounded-full"
                              :class="statusItem.status === 'shortlisted' ? 'bg-green-500' : statusItem.status === 'rejected' ? 'bg-red-500' : 'bg-gray-400'"
                          ></div>
                          <p class="text-base font-semibold text-gray-800 capitalize">
                            {{ statusItem.status }}
                          </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ statusItem.time }}</span>
                      </div>
                      <p class="text-sm text-gray-600 mt-1">{{ statusItem.remarks }}</p>
                      <p class="text-xs text-gray-400 mt-1">Accessed by: <span class="font-medium capitalize">{{ statusItem.accessedBy.name }}</span></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="text-right mt-4">
        <Button :disabled="vacancy.data.isApplied"
                type="button"
                btn-size="xm"
                btn-color="blue"
                class="flex"
                @click="save()">
          <span>{{ vacancy.data.isApplied ? 'Applied' : 'Apply' }}</span>
          <ExternalLinkIcon v-if="vacancy.data.applicationMethod === 'external' && !vacancy.data.isApplied"
                            class="h-4 w-4"/>
        </Button>
      </div>
    </div>

    <JetDialogModal :show="isModelOpen" @close="closeForm">
      <template #title> {{ modalTitle }}</template>
      <template #content>
        <QuestionsForm :job="job" @submitAnswer="saveAppliedJob"/>
      </template>
    </JetDialogModal>
  </AppLayout>
</template>


<script setup>
import {ref} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Button from "@/Components/Button.vue";
import {
  AcademicCapIcon,
  ArrowLeftIcon,
  BookmarkIcon as filledBookmarkIcon,
  CurrencyRupeeIcon,
  ExternalLinkIcon,
  LightBulbIcon,
  LightningBoltIcon,
  LocationMarkerIcon,
  MailIcon,
  PuzzleIcon,
  ShareIcon,
  SparklesIcon
} from "@heroicons/vue/solid";
import {Inertia} from "@inertiajs/inertia";
import QuestionsForm from "@/Components/Jobs/QuestionsForm.vue";
import JetDialogModal from "@/Components/DialogModal.vue";
import {usePage} from "@inertiajs/inertia-vue3";

const props = defineProps({
  vacancy: {
    type: Object,
    required: true
  }
})

let pageData = usePage().props.value;

let isModelOpen = ref(false);
let activeTab = ref('description');
let modalTitle = ref('Apply Job');
let job = ref({});

function closeForm() {
  isModelOpen.value = false
}

function addToSave() {
  window.dispatchEvent(new Event("start"));
  Inertia.post(route("admin.saved-jobs.store"), {
    vacancyId: props.vacancy.data.id,
    role: pageData.role.name,
    candidate: pageData.authUser.id,
  }, {
    onError: () => {
      inertia.refresh;
    },
    onSuccess: () => {
      inertia.refresh;
    },
  });
}

function save() {
  if (props.vacancy.data.applicationMethod === 'external') {
    window.open(props.vacancy.data.externalLink, '_blank')
  } else {
    if (props.vacancy.data.questions.length !== 0) {
      job.value = props.vacancy.data
      isModelOpen.value = true
    } else {
      saveAppliedJob(props.vacancy.data)
    }
  }
}

function saveAppliedJob(jobData) {
  isModelOpen.value = false;
  window.dispatchEvent(new Event("start"));

  const data = {
    vacancyId: jobData.id,
    role: pageData.role.name,
    candidate: pageData.authUser.id,
    ...(jobData.answers ? {answers: jobData.answers} : {})
  };

  Inertia.post(route("admin.jobs.store"), data, {
    onError: () => {
      inertia.refresh;
    },
    onSuccess: () => {
      inertia.refresh;
    },
  });
}

function goBack() {
  if (window.history.length > 1) {
    window.history.back();
  }
}
</script>

<style scoped>
/* Add any custom styles if necessary */
</style>
