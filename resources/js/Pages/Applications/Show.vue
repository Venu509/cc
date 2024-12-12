<template>
  <AppLayout title="Applications">
    <div class="p-4">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Applications of {{ vacancy.data.title }}</h2>
        <Button
            type="button"
            btn-size="xm"
            btn-color="light"
            @click="back"
        >
          Back
        </Button>
      </div>

      <Resume
          v-for="(resume, index) in candidates.data"
          :key="resume.id"
          :resume="resume.data"
          :route-name="route('admin.applications.view', { vacancy: vacancy.data.id, user: resume.data.id, tab: currentTab })"
      />
    </div>
  </AppLayout>
</template>

<script setup>
import {ref} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Tabs from "@/Components/Vacancies/Candidates/Tabs.vue";
import {Inertia} from "@inertiajs/inertia";
import Resume from "@/Components/Resumes/Resume.vue";
import Button from "@/Components/Button.vue";

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

const urlParams = new URLSearchParams(window.location.search);
const currentTab = ref(urlParams.get('tab') || 'recent');
function changeTab(selectedTab) {
  currentTab.value = selectedTab
  Inertia.visit(`${route("admin.applications.index")}?tab=${selectedTab}`, {
    preserveState: true,
    replace: true
  });
}

function back() {
  window.history.back();
  Inertia.reload()
}
</script>
