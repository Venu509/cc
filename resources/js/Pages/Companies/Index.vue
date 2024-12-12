<template>
  <AppLayout
      title="Companies"
  >
    <CompaniesTable
        :companies="companies"
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
      <template #title>Delete Company</template>
      <template #content>
        Are you sure you want to delete this Company? Once a Company
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
            @click="deleteCompany"
        >
          Delete Company
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CompaniesTable from "@/Components/Companies/CompaniesTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref, watch} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

let props = defineProps({
  companies: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("admin.companies.status", status.id), status);
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

function deleteCompany() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.companies.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.companies.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.companies.index"), params);
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
