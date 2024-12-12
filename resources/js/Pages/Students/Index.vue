<template>
  <AppLayout
      title="Students"
  >
    <StudentsTable
        :students="students"
        @update-status="updateStatus"
        @click-delete="handleClickDelete"
        @search="search"
        @bulk-delete="handleSelectedSelection"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Student</template>
      <template #content>
        Are you sure you want to delete this Student? Once a Student
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
            @click="deleteStudent"
        >
          Delete Student
        </JetDangerButton>
      </template>
    </ConfirmationModal>

    <ConfirmationModal
        :show="isShowingBulkDeleteConfirmationDialog"
        @close="isShowingBulkDeleteConfirmationDialog = false"
    >
      <template #title>Delete Selected Students</template>
      <template #content>
        Are you sure you want to delete these Students? Once a Students
        is deleted, all of its resources and data will be
        permanently deleted.
      </template>
      <template #footer>
        <JetSecondaryButton
            @click="isShowingBulkDeleteConfirmationDialog = false"
        >
          Cancel
        </JetSecondaryButton>

        <JetDangerButton
            :class="{ 'opacity-25': deleteButtonStatus }"
            :disabled="deleteButtonStatus"
            class="ml-3"
            @click="handleBulkDelete"
        >
          Delete Students
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import StudentsTable from "@/Components/Students/StudentsTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import eventBus from '@/Components/Widgets/Events/EventBus.js';

let props = defineProps({
  student: {
    type: Object,
    required: true,
  },
  students: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("students.status", status.id), status);
}

let selectedRow = ref({});
let selectedBulkIds = ref({});
let deleteButtonStatus = ref(false);

const isShowingDeleteConfirmationDialog = ref(false);
const isShowingBulkDeleteConfirmationDialog = ref(false);
const closeModal = () => {
  isShowingDeleteConfirmationDialog.value = false;
};

function handleClickDelete(item) {
  selectedRow.value = item;

  isShowingDeleteConfirmationDialog.value = true;
}

function deleteStudent() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.students.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.students.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
  Inertia.get(route("admin.students.index"), params);
}

function handleSelectedSelection(event) {
  selectedBulkIds.value = event
  isShowingBulkDeleteConfirmationDialog.value = true;
}

function handleBulkDelete() {
  Inertia.post(route("admin.students.delete"), {
    selectedIds: selectedBulkIds.value
  }, {
    onSuccess: () => {
      eventBus.emit('clear-selected-ids');
      isShowingBulkDeleteConfirmationDialog.value = false;
    },
  });
}
</script>
<style>

</style>
