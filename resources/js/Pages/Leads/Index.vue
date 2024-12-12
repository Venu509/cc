<template>
  <AppLayout
      title="Lead"
  >
    <LeadsTable
        :leads="leads"
        :types="types"
        @click-delete="handleClickDelete"
        @update-status="updateStatus"
        @dropDown-filters="handleDropdownFilters"
        @clear="clearAll"
        @search="search"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Lead</template>
      <template #content>
        Are you sure you want to delete this Lead? Once a
        Lead is deleted, all of its resources and data will
        be permanently deleted.
      </template>
      <template #footer>
        <JetSecondaryButton
            @click="isShowingDeleteConfirmationDialog = false"
        >
          Cancel
        </JetSecondaryButton>
        <JetDangerButton
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            class="ml-3"
            @click="deleteLead"
        >
          Delete Lead
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {ref, watch} from "vue";
import {Inertia} from "@inertiajs/inertia";
import JetDangerButton from "@/Components/DangerButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import LeadsTable from "@/Components/Leads/LeadsTable.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const pages = [
  {name: "Leads", route: "admin.leads.index", current: true},
];

let props = defineProps({
  leads: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  },
});

let page = usePage().props.value;

let selectedLead = ref({});

const isShowingDeleteConfirmationDialog = ref(false);

function updateStatus(status) {
  Inertia.put(route("admin.leads.status", status.id), status);
}

function handleClickDelete(lead) {
  selectedLead.value = lead;
  isShowingDeleteConfirmationDialog.value = true;
}

function deleteLead() {
  Inertia.delete(route("admin.leads.destroy", selectedLead.value.id), {
    onSuccess: () => (isShowingDeleteConfirmationDialog.value = false),
  });
}

function search(event) {
  Inertia.get(route("admin.leads.index"), {
    search: event
  });
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

function updateInertiaRequest(params) {
  Inertia.get(route("admin.leads.index"), params);
}

watch(typeTerm, (type) => {
  params.set("type", type);

  if (typeTerm.value) {
    params.set("type", typeTerm.value);
  }

  updateInertiaRequest(Object.fromEntries(params));
});
</script>
