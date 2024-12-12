<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Edit Profile" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="overflow-x-auto">
        <div class="flex-nowrap">
          <div class="border-b border-gray-200 dark:border-gray-700">
            <Tabs
                :tabs="enhancedTabs"
                :activeTab="activeTab"
                :isDisabled="isDisabled"
                @update:activeTab="setActiveTab"
            />
          </div>
        </div>
      </div>


      <div v-if="!isSaving">
        <PersonalDetails id="personal-details" v-if="activeTab === 'personal-details'" :form="form" :errors="errors"
                         :validate-field="validateField" :countries="countries" :max-date="maxDate" :is-editing="isEditing"/>

        <ProfessionalInformation id="professional-information" v-if="activeTab === 'professional-information'"
                                 :form="form" :noOfExperiences="noOfExperiences" :noticePeriods="noticePeriods"
                                 :errors="errors" :validate-field="validateField"/>

        <EducationalBackground id="educational-background" v-if="activeTab === 'educational-background'" :form="form"
                               :qualifications="qualifications" :errors="errors" :validate-field="validateField"/>

        <WorkExperiences id="work-experiences" v-if="activeTab === 'work-experiences'" :form="form"
                         :is-editing="isEditing" :errors="errors" :validate-field="validateField" :max-date="maxDate"/>

        <SkillAndCertificates id="skill-and-certificates" v-if="activeTab === 'skill-and-certificates'" :form="form"
                              :key-skills="keySkills" :errors="errors" :validate-field="validateField"/>

        <ResumeAndPortfolio id="resume-and-portfolio" v-if="activeTab === 'resume-and-portfolio'" :form="form"
                            :errors="errors" :validate-field="validateField"/>

        <JobPreferences id="job-preferences" v-if="activeTab === 'job-preferences'" :form="form" :job-types="jobTypes"
                        :industries="industries" :employment-status="employmentStatus" :errors="errors"
                        :validate-field="validateField"/>

        <AdditionalInformation id="additional-information" v-if="activeTab === 'additional-information'" :form="form"
                               :errors="errors" :validate-field="validateField"/>
      </div>

      <Saving v-else/>
    </div>
  </div>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {usePage} from "@inertiajs/inertia-vue3";
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import Tabs from "@/Components/Candidates/Partials/Tabs.vue";
import PersonalDetails from "@/Components/Candidates/Partials/PersonalDetails.vue";
import ProfessionalInformation from "@/Components/Candidates/Partials/ProfessionalInformation.vue";
import EducationalBackground from "@/Components/Candidates/Partials/EducationalBackground.vue";
import WorkExperiences from "@/Components/Candidates/Partials/WorkExperiences.vue";
import SkillAndCertificates from "@/Components/Candidates/Partials/SkillAndCertificates.vue";
import ResumeAndPortfolio from "@/Components/Candidates/Partials/ResumeAndPortfolio.vue";
import JobPreferences from "@/Components/Candidates/Partials/JobPreferences.vue";
import AdditionalInformation from "@/Components/Candidates/Partials/AdditionalInformation.vue";
import {errors, fetchRules, maxDate, validateField} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import axios from "axios";
import FormButtonAndHeaderSection from "@/Components/Widgets/FormButtonAndHeaderSection.vue";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
  noticePeriods: {
    type: Object,
    required: true,
  },
  noOfExperiences: {
    type: Object,
    required: true,
  },
  qualifications: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
  industries: {
    type: Object,
    required: true,
  },
  jobTypes: {
    type: Object,
    required: true,
  },
  employmentStatus: {
    type: Object,
    required: true,
  },
  isMyAccount: {
    type: Boolean,
    default: false,
  },
  countries: {
    type: Object,
    required: true,
  },
});

const form = useForm({...props.modelValue});
let urlSearchParams = new URLSearchParams(window.location.search);
const activeTab = ref(urlSearchParams.get('tab') || 'personal-details');
let page = usePage().props.value;
let intendedRoute = page.intendedRoute;
let profileCompletionStatus = ref({});

const tabs = [
  {value: 'personal-details', label: 'Personal Details', icon: 'UserCircleIcon'},
  {value: 'professional-information', label: 'Professional Information', icon: 'GlobeAltIcon'},
  {value: 'educational-background', label: 'Educational Background', icon: 'AcademicCapIcon'},
  {value: 'work-experiences', label: 'Work Experiences', icon: 'TrendingUpIcon'},
  {value: 'skill-and-certificates', label: 'Skill & Certificates', icon: 'TranslateIcon'},
  {value: 'resume-and-portfolio', label: 'Portfolio', icon: 'ShareIcon'},
  {value: 'job-preferences', label: 'Desire Job Preferences', icon: 'RssIcon'},
  {value: 'additional-information', label: 'Additional Information', icon: 'DotsHorizontalIcon'},
];
function updateProfileCompletionStatus() {
  if (props.isEditing) {
    profileCompletionStatus.value = form.getCompletionStatus;
  }
}

const enhancedTabs = computed(() => {
  return tabs.map(tab => {
    return {
      ...tab,
      isCompleted: !!profileCompletionStatus.value[tab.value]
    };
  });
});

const setActiveTab = (tab) => {
  if (!isDisabled(tab)) {
    activeTab.value = tab;
    window.history.pushState({}, '', `?tab=${tab}`);
  }
};

const isDisabled = (tab) => {
  return intendedRoute === 'admin.my-accounts.create' && tab !== activeTab.value;
};

watch(() => activeTab.value, (newTab) => {
  if (newTab) {
    form.tab = newTab;
  }
}, {immediate: true});

const watchUrl = () => {
  const params = new URLSearchParams(window.location.search);
  const newTab = params.get('tab');

  if (newTab && newTab !== activeTab.value) {
    activeTab.value = newTab;
    form.tab = newTab;
  }
};

const setupUrlWatcher = () => {
  window.addEventListener('popstate', watchUrl);
  const interval = setInterval(() => {
    watchUrl();
  }, 1000);

  onUnmounted(() => {
    clearInterval(interval);
    window.removeEventListener('popstate', watchUrl);
  });
};

onMounted(() => {
  updateProfileCompletionStatus()
  watchUrl();
  setupUrlWatcher();
  fetchRules(route("admin.my-accounts.rules", props.modelValue.id));
});

let isLoading = ref(true)
const isSaving = ref(false);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return 'Next';
});

const handleError = (errorResponse) => {
  if (errorResponse) {
    const newErrors = {};

    for (const [field, message] of Object.entries(errorResponse)) {
      newErrors[field] = message;
    }

    Object.assign(errors, newErrors);
    form.clearErrors();
    form.setError(newErrors);
  }

  isSaving.value = false;
};

function save() {
  isSaving.value = true;

  Inertia.post(route("admin.my-accounts.update", props.modelValue.id), form, {
    onError: handleError,
    onSuccess: () => {
      form.reset();
      getUpdatedCandidate(form.id).then(() => {
        updateProfileCompletionStatus();
      });
      isSaving.value = false;
    },
  });
}

function getUpdatedCandidate(candidate) {
  return axios.get(route("admin.my-accounts.fetch"), {
    params: {
      candidate: candidate
    },
  })
      .then(response => {
        Object.assign(form, response.data.candidate);
      })
      .catch(error => {
        console.error(error);
      });
}

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
