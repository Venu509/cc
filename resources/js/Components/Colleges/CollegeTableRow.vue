<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ college.data.id }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.companyName }}
    </BaseTableTd>

    <BaseTableTd>
     <RoleWidget :title="college.data.role" :color="college.data.roleColor"/>
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.companyMobileNumber }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.dateOfRegister }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.address }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.contactPerson }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.contactPersonPhone }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.contactPersonEmail }}
    </BaseTableTd>

    <BaseTableTd>
      {{ college.data.userDetail.contactPersonAddress }}
    </BaseTableTd>

    <BaseTableTd>
      <a class="cursor-pointer text-secondary-400" :href="college.data.userDetail.registrationDoc" target="_blank" download @click="college.data.userDetail.registrationDoc">Download</a>
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
  college: {
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
      Inertia.visit(route("admin.colleges.show", {id: props.college.data.id}));
    },
    icon: PencilIcon,
  },
];

function clickDeleteAction() {
  return emit("click-delete", props.college);
}
</script>

<style scoped></style>
