<template>
  <BaseTable
      :title="`Candidates`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`candidates/create?tab=personal-details`"
      :is-drop-down-filter="true"
      :drop-down-filters="noticePeriods"
      drop-down-url-param-label="notice-period"
      @clear="emit('clear')"
      @dropDown-filters="emit('dropDownFilters', $event)"
      @search="emit('search', $event)"
      :items="candidates"
      :data-length="10"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>S.No</BaseTableTh>
        <BaseTableTh>Username</BaseTableTh>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Phone</BaseTableTh>
        <BaseTableTh>Email</BaseTableTh>
        <BaseTableTh>DOB</BaseTableTh>
        <BaseTableTh>Age</BaseTableTh>
        <BaseTableTh>Gender</BaseTableTh>
        <BaseTableTh>Marital Status</BaseTableTh>
        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <CandidateTableRow
          v-for="candidate in candidates.data"
          :key="candidate.id"
          :candidate="candidate"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="candidates"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import CandidateTableRow from "@/Components/Candidates/CandidateTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search',
  "dropDownFilters",
  "clear",]);

let props = defineProps({
  candidates: {
    type: Object,
    required: true,
  },
  noticePeriods: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
