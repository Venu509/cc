<template>
  <BaseTable
      :title="`Companies`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`companies/create`"
      :is-drop-down-filter="false"
      @search="emit('search', $event)"
      :items="companies"
      :data-length="10"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>ID</BaseTableTh>
        <BaseTableTh>Username</BaseTableTh>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Phone</BaseTableTh>
        <BaseTableTh>Email</BaseTableTh>
        <BaseTableTh>Contact Person</BaseTableTh>
        <BaseTableTh>Contact Person Email</BaseTableTh>
        <BaseTableTh>Contact Person Phone</BaseTableTh>
        <BaseTableTh>Contact Person Address</BaseTableTh>
        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <CompanyTableRow
          v-for="company in companies.data"
          :key="company.id"
          :company="company"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="companies"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import CompanyTableRow from "@/Components/Companies/CompanyTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search',
  "dropDownFilters",
  "clear",]);

let props = defineProps({
  companies: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
