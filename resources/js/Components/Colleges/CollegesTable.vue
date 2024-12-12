<template>
  <BaseTable
      :title="`Colleges`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`colleges/create`"
      :is-drop-down-filter="true"
      :drop-down-filters="types"
      @clear="emit('clear')"
      @dropDown-filters="emit('dropDownFilters', $event)"
      @search="emit('search', $event)"
      :items="colleges"
      :data-length="12"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>S.No</BaseTableTh>
        <BaseTableTh>College Name</BaseTableTh>
        <BaseTableTh>Type</BaseTableTh>
        <BaseTableTh>Phone</BaseTableTh>
        <BaseTableTh>Date Of Registration</BaseTableTh>
        <BaseTableTh>Address</BaseTableTh>
        <BaseTableTh>Contact Person</BaseTableTh>
        <BaseTableTh>Contact Person Phone</BaseTableTh>
        <BaseTableTh>Contact Person Email</BaseTableTh>
        <BaseTableTh>Contact Person Address</BaseTableTh>
        <BaseTableTh>Registration Document</BaseTableTh>
        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <CollegeTableRow
          v-for="college in colleges.data"
          :key="college.id"
          :college="college"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="colleges"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import CollegeTableRow from "@/Components/Colleges/CollegeTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search',
  "dropDownFilters",
  "clear",]);

let props = defineProps({
  colleges: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
