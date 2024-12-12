<template>
  <AppLayout
      title="Banner"
      btn-color="info"
      :action="true"
      :pages="pages"
      :search-enabled="true"
      @handel-click-action="addBanner"
  >
    <BannersTable
        :banners="banners"
        :types="types"
        @click-delete="handleClickDelete"
        @update-status="updateStatus"
        @dropDown-filters="handleDropdownFilters"
        @clear="clearAll"
        @search="search"
    />

    <JetDialogModal :show="isModelOpen" @close="closeForm">
      <template #title> {{ modalTitle }}</template>

      <template #content>
        <BannerForm
            v-model="form"
            :types="types"
            :is-editing="isEditing"
        />
      </template>

      <template #footer>
        <JetSecondaryButton @click="closeForm">
          Cancel
        </JetSecondaryButton>

        <Button
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
            class="ml-3"
            @click="save"
        >
          Save
        </Button>
      </template>
    </JetDialogModal>

    <ConfirmationModal
        :show="isShowingDeleteConfirmationDialog"
        @close="isShowingDeleteConfirmationDialog = false"
    >
      <template #title>Delete Banner</template>
      <template #content>
        Are you sure you want to delete this Banner? Once a
        Banner is deleted, all of its resources and data will
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
            @click="deleteBanner"
        >
          Delete Banner
        </JetDangerButton>
      </template>
    </ConfirmationModal>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Button from "@/Components/Button";
import {computed, onMounted, ref, watch} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import JetDialogModal from "@/Components/DialogModal.vue";
import JetDangerButton from "@/Components/DangerButton.vue";
import ConfirmationModal from "@/Components/ConfirmationModal";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import BannerForm from "@/Components/Banners/BannerForm.vue";
import BannersTable from "@/Components/Banners/BannersTable.vue";
import { usePage } from "@inertiajs/inertia-vue3";

const pages = [
  {name: "Banners", route: "admin.banners.index", current: true},
];

let props = defineProps({
  banners: {
    type: Object,
    required: true,
  },
  banner: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  },
});

let page = usePage().props.value;
let role = page.role;

let form = useForm({
  id: null,
  role: role.name,
  title: "",
  type: "",
  remark: "",
});

let assignedData = ref({});
let modelTitle = ref("Products");

let isModelOpen = ref(false);

let selectedBanner = ref({});

const isShowingDeleteConfirmationDialog = ref(false);

const isShowingAssignedDataDialog = ref(false);

const isEditing = computed(() => !!props.banner.data.id);

const modalTitle = computed(() => {
  if (isEditing.value) {
    return "Edit Banner";
  }
  return "Add Banner";
});

onMounted(() => {
  if (!props.banner.data) {
    return;
  }
  form.id = props.banner.data.id;
  form.title = props.banner.data.title;
  form.type = props.banner.data.type;
  form.remark = props.banner.data.remark;
  isModelOpen.value = true;
});

function save() {
  if (isEditing.value) {
    return updateBanner();
  }
  saveBanner();
}

function saveBanner() {
  Inertia.post(route("admin.banners.store"), form, {
    onError: (errors) => {
      form.clearErrors().setError(errors);
    },
    onSuccess: () => {
      form.reset();
      isModelOpen.value = false;
    },
  });
}

function updateBanner() {
  Inertia.post(route("admin.banners.update", props.banner.data.id), form, {
    onError: (errors) => {
      form.clearErrors().setError(errors);
    },
    onSuccess: () => {
      form.reset();
      isModelOpen.value = false;
    },
  });
}

function updateStatus(status) {
  Inertia.put(route("admin.banners.status", status.id), status);
}

function handleClickDelete(banner) {
  selectedBanner.value = banner;
  isShowingDeleteConfirmationDialog.value = true;
}

function deleteBanner() {
  Inertia.delete(route("admin.banners.destroy", selectedBanner.value.id), {
    onSuccess: () => (isShowingDeleteConfirmationDialog.value = false),
  });
}

function closeForm() {
  Inertia.get(route("admin.banners.index"), {}, {replace: true});
  isModelOpen.value = false;
}

function addBanner() {
  isModelOpen.value = true;
}

function search(event) {
  Inertia.get(route("admin.banners.index"), {
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

  Inertia.get(route("admin.banners.index"), {});
}

function updateInertiaRequest(params) {
  Inertia.get(route("admin.banners.index"), params);
}

watch(typeTerm, (type) => {
  params.set("type", type);

  if (typeTerm.value) {
    params.set("type", typeTerm.value);
  }

  updateInertiaRequest(Object.fromEntries(params));
});
</script>
