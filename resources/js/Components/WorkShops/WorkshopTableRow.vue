<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ workshop.data.name.label }}
    </BaseTableTd>
    <BaseTableTd>
      {{ workshop.data.branch.name }}
    </BaseTableTd>

    <BaseTableTd>
      {{ workshop.data.semester }}
    </BaseTableTd>

    <BaseTableTd>
      {{ workshop.data.date }}
    </BaseTableTd>

    <BaseTableTd>
      {{ workshop.data.endDate }}
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
  workshop: {
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
      Inertia.visit(route("admin.workshops.show", {id: props.workshop.data.id}));
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
  return emit("click-delete", props.workshop);
}
</script>

<style scoped></style>
