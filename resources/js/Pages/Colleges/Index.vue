<template>
  <AppLayout
      title="Colleges"
  >
    <CollegesTable
        :colleges="colleges"
        :types="types"
        :branches="branches"
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
      <template #title>Delete College</template>
      <template #content>
        Are you sure you want to delete this College? Once a College
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
            @click="deleteCollege"
        >
          Delete College
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CollegesTable from "@/Components/Colleges/CollegesTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref, watch} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import LeadsTable from "@/Components/Leads/LeadsTable.vue";

let props = defineProps({
  colleges: {
    type: Object,
    required: true,
  },
  branches: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("colleges.status", status.id), status);
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

function deleteCollege() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.colleges.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.colleges.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.colleges.index"), params);
}

let params = new URLSearchParams(window.location.search);
const typeTerm = ref(params.get("type") || "all");

function handleDropdownFilters(event) {
  typeTerm.value = event;
}

function clearAll() {
  history.pushState({}, "", window.location.pathname);

  Inertia.get(route("admin.leads.index"), {});
}

watch(typeTerm, (type) => {
  params.set("type", type);

  if (typeTerm.value) {
    params.set("type", typeTerm.value);
  }

  updateInertiaRequest(Object.fromEntries(params));
});
</script>
<style>

</style>
