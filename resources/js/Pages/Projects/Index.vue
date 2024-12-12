<template>
  <AppLayout
      title="Projects"
  >
    <ProjectsTable
        :projects="projects"
        @update-status="updateStatus"
        @click-delete="handleClickDelete"
        @search="search"
    />

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Project</template>
      <template #content>
        Are you sure you want to delete this Project? Once a Project
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
            @click="deleteProject"
        >
          Delete Project
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ProjectsTable from "@/Components/Projects/ProjectsTable.vue";
import {Inertia} from "@inertiajs/inertia";
import {onMounted, ref} from "vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetDangerButton from "@/Components/DangerButton.vue";

let props = defineProps({
  project: {
    type: Object,
    required: true,
  },
    projects: {
    type: Object,
    required: true,
  },
});

function updateStatus(status) {
  Inertia.put(route("admin.projects.status", status.id), status);
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

function deleteProject() {
  deleteButtonStatus.value = true;
  Inertia.delete(route("admin.projects.destroy", selectedRow.value.data.id), {
    onSuccess: () => {
      isShowingDeleteConfirmationDialog.value = false;
      deleteButtonStatus.value = false;
    },
  });
}

function search(event) {
  Inertia.get(route("admin.projects.index"), {
    search: event
  });
}

function updateInertiaRequest(params) {
    Inertia.get(route("admin.projects.index"), params);
}

</script>
<style>

</style>
