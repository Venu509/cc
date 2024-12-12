<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ company.data.id }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.username }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.userDetail.companyName }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.phone }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.email }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.userDetail.contactPerson }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.userDetail.contactPersonEmail }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.userDetail.contactPersonPhone }}
    </BaseTableTd>

    <BaseTableTd>
      {{ company.data.userDetail.contactPersonAddress }}
    </BaseTableTd>

    <BaseTableTd>
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>

  </BaseTableTr>
</template>

<script setup>
import { defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import ActionDropDown from "@/Components/ActionDropDown.vue";

let props = defineProps({
  company: {
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
      Inertia.visit(route("admin.companies.show", {id: props.company.data.id}));
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
  return emit("click-delete", props.company);
}
</script>

<style scoped></style>
