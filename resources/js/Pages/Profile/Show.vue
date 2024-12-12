<template>
  <AppLayout
      title="My Profile"
  >
    <div class="flex justify-center items-start py-6">
      <div class="bg-white w-full rounded-lg shadow-lg p-6 md:p-10">
        <div class="flex justify-between items-center border-b pb-4">
          <h2 class="text-lg font-semibold capitalize">{{ activeTabLabel }}</h2>
          <Button btn-size="xm" btn-color="blue" @click="logout">
            Sign Out
          </Button>
        </div>

        <div class="md:flex mt-6">
          <div class="flex flex-col text-gray-600 w-full md:w-1/4 mb-4 md:mb-0 md:pr-6">
            <div class="flex items-center mb-4">


              <div class="flex items-center space-x-6">
                <div class="flex flex-col items-center">
                  <input
                      id="photo"
                      ref="photoInput"
                      type="file"
                      class="hidden"
                      accept="image/x-png,image/gif,image/jpeg"
                      @change="updatePhotoPreview"
                  >
                  <div v-show="!photoPreview" class="mt-2">
                    <img :src="user.avatar" :alt="user.name" class="rounded-full h-24 w-24 object-cover">
                  </div>
                  <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-24 h-24 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                  </div>
                </div>

                <div class="flex flex-col space-y-2">
                  <div>
                    <span class="block text-lg text-gray-800 font-semibold">{{ user.name }}</span>
                    <span class="block text-md text-gray-500">{{ user.email }}</span>
                  </div>

                  <div class="flex space-x-2">
                    <SecondaryButton type="button" @click.prevent="selectNewPhoto">
                      New
                    </SecondaryButton>
                    <SecondaryButton
                        v-if="user.avatar"
                        type="button"
                        @click="deletePhoto"
                    >
                      Remove
                    </SecondaryButton>
                  </div>
                </div>
              </div>
            </div>

            <nav>
              <ul v-for="tab in tabs"
                  :key="tab.value"
              >
                <li
                    v-if="tab.access"
                    :class="[
                      activeTab === tab.value ? 'border-l-4 border-secondary-500 text-secondary-500 font-semibold' : '',
                      'py-2 cursor-pointer'
                  ]" @click="changeTab(tab)">{{ tab.label }}
                </li>
              </ul>
            </nav>
          </div>

          <PersonalInformation :errors="errors"
                               :form="form"
                               :validateField="validateField"
                               :is-saving="isSaving"
                               v-if="isAcceptedRole && activeTab === 'personal-details'"
                               @save="updateUserInformation"/>

          <UpdatePasswordForm :user="$page.props.authUser" v-if="activeTab === 'change-password'"/>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {computed, onMounted, ref} from "vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";
import PersonalInformation from "@/Pages/Profile/Partials/PersonalInformation.vue";
import {errors, fetchRules, validateField} from '@/Components/Services/Validation';
import {useForm} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileInput from "@/Components/FileInput.vue";

let props = defineProps({
  candidate: {
    type: Object,
    required: true,
  },
});

let form = useForm({...props.candidate});

let page = usePage().props.value;
let user = page.authUser
let role = page.role
let isSaving = ref(false);
const photoPreview = ref(null);
const photoInput = ref(null);

const imageForm = useForm({
  _method: 'POST',
  photo: null,
});

const isGovernment = computed(() => role.name === 'government')
const isCandidate = computed(() => role.name === 'candidate')
const isInstitution = computed(() => role.name === 'institution')
const isCompany = computed(() => role.name === 'company')
const isAcceptedRole = (isGovernment || isCandidate || isInstitution || isCompany)

let urlSearchParams = new URLSearchParams(window.location.search);
const activeTab = ref(urlSearchParams.get('tab') || 'personal-details');
const activeTabLabel = ref('Personal Details');

const tabs = [
  {value: 'personal-details', label: 'Personal Details', access: isAcceptedRole},
  {value: 'change-password', label: 'Change Password', access: true},
];

onMounted(() => {
  fetchRules(route("admin.my-accounts.rules"));
});
const changeTab = (tab) => {
  activeTab.value = tab.value;
  activeTabLabel.value = tab.label;
  window.history.pushState({}, '', `?tab=${tab.value}`);
};

const logout = () => {
  Inertia.post(route("admin.logout"));
};

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

const handleSuccess = () => {
  form.reset();
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
  isSaving.value = false;
};

function updateUserInformation(form) {
  isSaving.value = true
  Inertia.post(route("admin.my-accounts.update", props.candidate.id), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

const selectNewPhoto = () => {
  photoInput.value.click();
};

function deletePhoto() {
  Inertia.post(route("admin.my-accounts.remove", props.candidate.id), imageForm, {
    onSuccess: () => {
      photoPreview.value = null;
      clearPhotoFileInput();
      location.reload()
    },
  });
}

function updateProfileInformation() {
  if (photoInput.value) {
    form.photo = photoInput.value.files[0];
  }

  Inertia.post(route("admin.my-accounts.info", props.candidate.id), form, {
    onSuccess: () => {
      clearPhotoFileInput();
      location.reload()
    },
  });
}

const updatePhotoPreview = () => {
  const photo = photoInput.value.files[0];

  if (!photo) return;

  const reader = new FileReader();

  reader.onload = (e) => {
    photoPreview.value = e.target.result;
  };

  reader.readAsDataURL(photo);

  updateProfileInformation()
};

const clearPhotoFileInput = () => {
  if (photoInput.value?.value) {
    photoInput.value.value = null;
  }
};
</script>