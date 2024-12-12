<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ candidate.data.id }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.username }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.name }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.phone }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.email }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.userDetail.dob }}
    </BaseTableTd>

    <BaseTableTd>
      {{ candidate.data.userDetail.age }} Years
    </BaseTableTd>

    <BaseTableTd class="capitalize">
      {{ candidate.data.userDetail.gender }}
    </BaseTableTd>

    <BaseTableTd class="capitalize">
      {{ candidate.data.userDetail.maritalStatus }}
    </BaseTableTd>

    <BaseTableTd>
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>

  </BaseTableTr>
</template>

<script setup>
import {computed, defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import Toggle from "@/Components/Toggle.vue";
import ActionDropDown from "@/Components/ActionDropDown.vue";
import RoleWidget from "@/Components/Widgets/RoleWidget.vue";

let props = defineProps({
  candidate: {
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
      Inertia.visit(route("admin.candidates.show", {id: props.candidate.data.id, tab: 'personal-details'}));
    },
    icon: PencilIcon,
  },
  // {
  //   active: true,
  //   name: "Delete",
  //   callback: () => {
  //     clickDeleteAction();
  //   },
  //   icon: TrashIcon,
  // },
];

function clickDeleteAction() {
  return emit("click-delete", props.candidate);
}
</script>

<style scoped></style>
