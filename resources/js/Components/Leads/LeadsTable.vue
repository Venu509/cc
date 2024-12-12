<template>
  <BaseTable
      :title="`Leads`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`leads/create`"
      :is-drop-down-filter="true"
      :drop-down-filters="types"
      @clear="emit('clear')"
      @dropDown-filters="emit('dropDownFilters', $event)"
      @search="emit('search', $event)"
      :items="leads"
      :data-length="7"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh> Title</BaseTableTh>
        <BaseTableTh> Type</BaseTableTh>
        <BaseTableTh> Status</BaseTableTh>
        <BaseTableTh> Description</BaseTableTh>
        <BaseTableTh> Last Accessed By</BaseTableTh>
        <BaseTableTh> Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>
    <BaseTableTbody>
      <LeadTableRow
          v-for="lead in leads.data"
          :key="lead.id"
          :lead="lead"
          @click-delete="emit('click-delete', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="leads"/>
</template>
<script setup>
import {defineEmits} from "vue";
import Pagination from "@/Components/Pagination.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableFooter from "@/Components/BaseTable/BaseTableFooter.vue";
import LeadTableRow from "@/Components/Leads/LeadTableRow.vue";

let props = defineProps({
  leads: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits([
  "select-row",
  "click-delete",
  "update-status",
  "dropDownFilters",
  "clear",
]);

</script>

<style scoped></style>
