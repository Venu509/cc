<template>
  <AppLayout title="Applications">
    <Tabs @change-tab="changeTab"/>

    <div v-if="vacancies.data.length > 0" class="space-y-4">
      <div
          v-for="(job, index) in vacancies.data"
          :key="index"
          class="bg-white p-6 rounded-lg shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
      >
        <div class="flex-1">
          <h3 class="text-xl font-semibold text-gray-800 truncate">{{ job.data.title }}</h3>
          <p class="mt-2 text-sm text-gray-500 capitalize">
            Total Applications received:
            <span class="font-medium text-gray-700">{{ job.data.appliedCandidatesCount['total'] }}</span> |
            {{ currentTab }} Applications:
            <span class="font-medium text-gray-700">{{ job.data.appliedCandidatesCount['selected'] }}</span>
          </p>
        </div>

        <div class="mt-4 sm:mt-0 sm:ml-6">
          <Button
              type="button"
              btn-size="xm"
              btn-color="light"
              @click="view(job.data.id)"
              class="w-full sm:w-auto"
          >
            <span>View</span>
          </Button>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-10">
      <p class="text-lg font-semibold text-gray-700">No Records Found</p>
      <p class="text-gray-500 text-sm">There are currently no {{ currentTab }} vacancies available.</p>
    </div>
  </AppLayout>
</template>

<script setup>
import {ref} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Tabs from "@/Components/Vacancies/Candidates/Tabs.vue";
import {Inertia} from "@inertiajs/inertia";
import Button from "@/Components/Button.vue";

const props = defineProps({
  vacancies: {
    type: Object,
    required: true,
  },
});

const urlParams = new URLSearchParams(window.location.search);
const currentTab = ref(urlParams.get('tab') || 'pending');
function changeTab(selectedTab) {
  currentTab.value = selectedTab
  Inertia.visit(`${route("admin.applications.index")}?tab=${selectedTab}`, {
    preserveState: true,
    replace: true
  });
}

function view(vacancy) {
  Inertia.visit(route('admin.applications.show', vacancy), {
    method: 'get',
    data: { tab: currentTab.value }
  });
}
</script>
