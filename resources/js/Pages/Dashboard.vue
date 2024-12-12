<script setup>
import {onMounted} from "vue";
import AppLayout from '@/Layouts/AppLayout.vue';
import Greeting from "@/Components/Greeting.vue";
import Alert from "@/Components/Widgets/Alert.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {ExclamationIcon, UserIcon, TrendingDownIcon, TrendingUpIcon  } from "@heroicons/vue/solid";

let page = usePage().props.value;
let authUser = page.authUser;
let role = page.role;

let props = defineProps({
  data: {
    type: Object,
    required: true,
  },
})

let profileCompletion = props.data.profileCompletion;
let isProfileCompleted = props.data.isProfileCompleted;

const actions = [
  {
    type: "warning",
    body: `Your profile is not completed. Please complete your profile before proceeding.`,
    hasAction: true,
    actionText: "Go to Settings",
    actionUrl: route("admin.my-accounts.show", {
      user: authUser.id,
      tab: 'personal-details'
    }),
    icon: ExclamationIcon,
    active: role.name === 'candidate' && !isProfileCompleted,
  },
];

let statistics = props.data.statistics

onMounted(() => {
  window.history.pushState(null, "", window.location.href);

  window.onpopstate = function () {
    window.history.pushState(null, "", window.location.href);
  };
});

</script>

<template>
  <AppLayout title="Dashboard">
    <Greeting :authUser="authUser" :role="role"/>

    <div
        v-for="(action, index) in actions"
        v-show="action.active"
        :key="index"
        class="w-full mx-auto"
    >
      <Alert :action="action" :profileCompletion="profileCompletion"/>
    </div>

    <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
      <div
          v-for="(statistic, index) in statistics"
          :key="index"
      >
        <div class="bg-white shadow-lg rounded-lg p-6 sm:p-8 max-w-xs mx-auto">
          <div class="flex items-center justify-start">
            <div :class="[
                `bg-${statistic.color}-200 p-3 rounded-full`
            ]">
              <svg :class="[
                  `h-6 w-6 text-${statistic.color}-600`
              ]" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                   xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M5.121 17.804A9.005 9.005 0 0112 4.5a9.005 9.005 0 016.879 13.304M9 21h6m-3-3v3"></path>
              </svg>
            </div>
          </div>

          <div class="mt-4">
            <p class="text-gray-600 text-sm">{{ statistic.title }}</p>
            <p class="text-3xl font-semibold text-gray-900">{{ statistic.count }}</p>
            <div class="flex items-center mt-1">
              <TrendingUpIcon v-show="statistic.type === 'up'" class="h-4 w-4 text-green-500"/>
              <TrendingDownIcon v-show="statistic.type === 'down'" class="h-4 w-4 text-red-500"/>
              <p :class="[
                  statistic.type === 'up' ? 'text-green-500' : 'text-red-500',
                  'text-sm ml-1'
              ]">{{ statistic.message }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
