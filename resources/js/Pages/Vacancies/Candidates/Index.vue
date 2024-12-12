<template>
  <AppLayout :title="`Applied Candidates | ${vacancy.data.title}`">
    <div>
      <Tabs @change-tab="changeTab"/>

      <p class="text-lg font-semibold my-3">
        {{ candidates.total > 0 ? candidates.total + ' total Applications' : 'Records Not Found' }}
      </p>
      <Resume @click="drawerVisible = true"
              v-for="(resume, index) in candidates.data"
              :key="index"
              :resume="resume.data"
              @choose-candidate="chooseCandidate"/>

      <Pagination :items="candidates" class="mt-2"/>

      <Drawer
          v-show="drawerVisible"
          :resume="selectedCandidate"
          :vacancy="vacancy"
          @change-applicant-status="changeApplicantStatus"
          @close="drawerVisible = false"
          :drawer-visible="drawerVisible"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import Resume from "@/Components/Vacancies/Candidates/Resume.vue";
import Drawer from "@/Components/Widgets/Applicants/Drawer.vue";
import { ref , watch} from "vue";
import Tabs from "@/Components/Vacancies/Candidates/Tabs.vue";
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
  candidates: {
    type: Object,
    required: true,
  },
  vacancy: {
    type: Object,
    required: true,
  },
});

const selectedCandidate = ref(null);
const selectedTab = ref('pending');
const drawerVisible = ref(false);

const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);

selectedTab.value = urlParams.get('tab') ?? 'pending';

async function chooseCandidate(event) {
  selectedCandidate.value = event.resume;

  try {
    window.dispatchEvent(new Event("start"));

    await axios.post(route("admin.applied-candidates.status", props.vacancy.data.id), {
      vacancy: props.vacancy.data.id,
      resume: selectedCandidate.value.id,
      status: 'viewed',
      tab: selectedTab.value,
      type: 'axios',
    });

    drawerVisible.value = true;

  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    window.dispatchEvent(new Event("finish"));
  }
}

function changeApplicantStatus(event) {
  window.dispatchEvent(new Event("start"));
  Inertia.post(route("admin.applied-candidates.status", event.vacancy.data.id),{
    vacancy: event.vacancy.data.id,
    resume: event.resume.id,
    status: event.status,
    tab: selectedTab.value,
    type: 'inertia',
  }, {
    onError: () => {
      window.dispatchEvent(new Event("finish"));
      changeTab(selectedTab.value)
    },
    onSuccess: () => {
      window.dispatchEvent(new Event("finish"));
      changeTab(selectedTab.value)
      selectedCandidate.value = null;
    },
  });
}

function changeTab(selectedTab) {
  Inertia.visit(`${route("admin.applied-candidates.index", { id: props.vacancy.data.id })}?tab=${selectedTab}`, {
    preserveState: true,
    replace: true
  });
}
</script>
