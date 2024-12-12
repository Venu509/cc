<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ project.data.name.label }}
    </BaseTableTd>
    <BaseTableTd>
        <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20"
              v-if="project.data.type === 'mini'"> Mini Project
        </span>
        <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
              v-if="project.data.type === 'academic'"> Academic Project
        </span>
    </BaseTableTd>
    <BaseTableTd>
      {{ project.data.branch.name }}
    </BaseTableTd>

    <BaseTableTd>
      {{ project.data.semester }}
    </BaseTableTd>

    <BaseTableTd>
      {{ project.data.date }}
    </BaseTableTd>

    <BaseTableTd>
      {{ project.data.endDate }}
    </BaseTableTd>
    <BaseTableTd>
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>

  </BaseTableTr>
</template>

<script setup>
import {defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import Toggle from "@/Components/Toggle.vue";
import ActionDropDown from "@/Components/ActionDropDown.vue";

let props = defineProps({
  project: {
    type: Object,
    required: true,
  }
});

const emit = defineEmits(["select-row", "click-delete", "update-status"]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.projects.show", {id: props.project.data.id}));
    },
    icon: PencilIcon,
  },
  {
    active: true,
    name: "Delete",
    callback: () => {
      clickDeleteAction();
    },
    icon: TrashIcon,
  },
];

function clickDeleteAction() {
  return emit("click-delete", props.project);
}
</script>

<style scoped></style>
