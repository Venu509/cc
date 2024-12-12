<template>
  <BaseTable
      :title="`Banners`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`banners/create`"
      :is-drop-down-filter="false"
      @search="emit('search', $event)"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh> Title</BaseTableTh>
        <BaseTableTh> Image</BaseTableTh>
        <BaseTableTh> Is Active</BaseTableTh>
        <BaseTableTh> Last Accessed By</BaseTableTh>
        <BaseTableTh> Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>
    <BaseTableTbody>
      <BannerTableRow
          v-for="banner in banners.data"
          :key="banner.id"
          :banner="banner"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
    <BaseTableFooter v-if="!banners.data.length" :data-length="6"/>
  </BaseTable>
  <Pagination :items="banners"/>
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
import BannerTableRow from "@/Components/Banners/BannerTableRow.vue";

let props = defineProps({
  banners: {
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
