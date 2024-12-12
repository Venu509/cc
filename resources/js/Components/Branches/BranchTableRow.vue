<template>
  <BaseTableTr>

    <BaseTableTd>
      {{ index }}
    </BaseTableTd>

    <BaseTableTd class="uppercase">
      {{ branch.data.name }}
    </BaseTableTd>

    <BaseTableTd>
      {{ branch.data.studentsCount }}
    </BaseTableTd>
    <BaseTableTd class="relative">
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>

  </BaseTableTr>
</template>

<script setup>
import {defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon, TrashIcon, EyeIcon } from "@heroicons/vue/solid";
import ActionDropDown from "@/Components/ActionDropDown.vue";

let props = defineProps({
  branch: {
    type: Object,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  }
});

const emit = defineEmits(["select-row", "click-delete", "update-status"]);

const actions = [
  {
    active: true,
    name: "View",
    callback: () => {
      Inertia.visit(route("admin.branches.view", {id: props.branch.data.id}));
    },
    icon: EyeIcon,
  },
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.branches.show", {id: props.branch.data.id}));
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
  return emit("click-delete", props.branch);
}
</script>

<style scoped></style>
