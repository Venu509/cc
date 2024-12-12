<script setup>
import {ref} from 'vue';
import OrderItem from "@/Components/Orders/OrderItem.vue";

let props = defineProps({
  orders: {
    type: Object,
    required: true,
  },
});

const expandedOrderId = ref(props.orders.data[0]?.id || null);

const setExpandedOrder = (orderId) => {
  expandedOrderId.value = expandedOrderId.value === orderId ? null : orderId;
};
</script>

<template>
  <div>
    <OrderItem
        v-for="order in orders.data"
        :key="order.data.id"
        :item="order.data"
        :is-expanded="expandedOrderId === order.data.id"
        @toggle="setExpandedOrder(order.data.id)"
    />

    <div class="text-center border rounded-2xl overflow-hidden shadow-sm relative flex w-full p-4 transition-all ease-in border-b border-solid cursor-pointer border-slate-100 text-white bg-secondary-600" v-if="!orders.data.length">
      No Data Available
    </div>
  </div>
</template>
