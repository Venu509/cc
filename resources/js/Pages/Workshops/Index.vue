<template>
  <AppLayout
      title="Workshops"
  >
    <WorkshopsTable
        :workshops="workshops"
        @update-status="updateStatus"
        @click-delete="handleClickDelete"
        @search="search"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete WorkShop</template>
      <template #content>
        Are you sure you want to delete this WorkShop? Once a WorkShop
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
            @click="deleteWorkShop"
        >
          Delete WorkShop
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import WorkshopsTable from "@/Components/Workshops/WorkshopsTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {onMounted, ref} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

let props = defineProps({
  workshop: {
    type: Object,
    required: true,
  },
  workshops: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("admin.workshops.status", status.id), status);
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

function deleteWorkShop() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.workshops.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.workshops.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.workshops.index"), params);
}

</script>
<style>

</style>
