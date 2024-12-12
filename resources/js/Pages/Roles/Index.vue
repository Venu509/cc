<template>
  <AppLayout
      title="Roles"
  >
    <RolesTable
        :roles="roles"
        @update-status="updateStatus"
        @click-delete="handleClickDelete"
        @search="search"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Role</template>
      <template #content>
        Are you sure you want to delete this Role? Once a Role
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
            @click="deleteRole"
        >
          Delete Role
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import RolesTable from "@/Components/Roles/RolesTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

let props = defineProps({
    roles: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("admin.roles.status", status.id), status);
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

function deleteRole() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.roles.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.roles.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.roles.index"), params);
}

</script>
<style>

</style>
