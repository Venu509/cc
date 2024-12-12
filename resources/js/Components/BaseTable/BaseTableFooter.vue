<template>
    <BaseTableTbody>
        <BaseTableTr>
            <td
                :colspan="dataLength"
                class="px-2 first:pl-5 last:pr-5 whitespace-nowrap py-4 pl-4 pr-3 text-sm text-center font-medium text-gray-500 sm:pl-6"
            >
                No data available
            </td>
        </BaseTableTr>
    </BaseTableTbody>
</template>

<script setup>
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";

const props = defineProps({
    dataLength: {
        type: Number,
        require: true,
    },
});
</script>
