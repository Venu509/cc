<template>
  <BaseTable
      :title="`Users`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`users/create`"
      @search="emit('search', $event)"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>Name</BaseTableTh>

        <BaseTableTh>Role</BaseTableTh>

        <BaseTableTh>Email</BaseTableTh>

        <BaseTableTh>Phone</BaseTableTh>

        <BaseTableTh>Image</BaseTableTh>

        <BaseTableTh>Status</BaseTableTh>

        <BaseTableTh>Last Access</BaseTableTh>

        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <UserTableRow
          v-for="employee in employees.data"
          :key="employee.id"
          :employee="employee"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
    <BaseTableFooter v-if="!employees.data.length" :data-length="7"/>
  </BaseTable>
  <Pagination :items="employees"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import BaseTableFooter from "@/Components/BaseTable/BaseTableFooter.vue";
import UserTableRow from "@/Components/Users/UserTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", "search"]);

let props = defineProps({
  employees: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
