<template>
  <AppLayout
      title="Candidates"
  >
    <CandidatesTable
        :candidates="candidates"
        :notice-periods="noticePeriods"
        @update-status="updateStatus"
        @click-delete="handleClickDelete"
        @dropDown-filters="handleDropdownFilters"
        @clear="clearAll"
        @search="search"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Candidate</template>
      <template #content>
        Are you sure you want to delete this Candidate? Once a Candidate
        is deleted, all of its resources and data will be
        permanently deleted.
      </template>
      <template #footer>
        <JetSecondaryButton
            @click="isShowingDeleteConfirmationDialog = false"
        >
          Cancel
        </JetSecondaryButton>

        <JetDangerButton
            :class="{ 'opacity-25': deleteButtonStatus }"
            :disabled="deleteButtonStatus"
            class="ml-3"
            @click="deleteCandidate"
        >
          Delete Candidate
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CandidatesTable from "@/Components/Candidates/CandidatesTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref, watch} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

let props = defineProps({
  candidates: {
    type: Object,
    required: true,
  },
  noticePeriods: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("admin.candidates.status", status.id), status);
}

let selectedRow = ref({});
let deleteButtonStatus = ref(false);

const isShowingDeleteConfirmationDialog = ref(false);
const closeModal = () => {
  isShowingDeleteConfirmationDialog.value = false;
};

function handleClickDelete(item) {
  selectedRow.value = item;

  isShowingDeleteConfirmationDialog.value = true;
}

function deleteCandidate() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.candidates.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.candidates.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.candidates.index"), params);
}

let params = new URLSearchParams(window.location.search);
const noticePeriod = ref(params.get("notice-period") || "all");

function handleDropdownFilters(event) {
  noticePeriod.value = event;
}

function clearAll() {
  history.pushState({}, "", window.location.pathname);

  Inertia.get(route("admin.leads.index"), {});
}

watch(noticePeriod, (type) => {
  params.set("notice-period", type);

  if (noticePeriod.value) {
    params.set("notice-period", noticePeriod.value);
  }

  updateInertiaRequest(Object.fromEntries(params));
});
</script>
<style>

</style>
