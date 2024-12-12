<template>
  <AppLayout
      :title="branch.name"
  >
    <div class="flex flex-col gap-6">
      <div class="card">
        <div class="p-6">
          <div v-if="isLoading">
            <Saving message="Loading ...."/>
          </div>

          <div v-else>
            <div class="grid lg:grid-cols-2 gap-3">
              <div>
                <Label>Name</Label>
                <p class="mt-1 text-gray-700">{{ branch.name }}</p>
              </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6 mt-4">
              <div>
                <Label>Description</Label>
                <p class="mt-1 text-gray-700">{{ branch.description }}</p>
              </div>
            </div>
          </div>

          <div class="flex mt-4">
            <Button class="mr-3" btn-color="dark" @click="back">
              Back
            </Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import Saving from "@/Components/Widgets/Saving.vue";
import Button from "@/Components/Button.vue";
import Label from "@/Components/Label.vue";

let props = defineProps({
  branch: {
    type: Object,
    required: true,
  },
});

const isLoading = ref(false);

function back() {
  window.history.back();
}

onMounted(() => {
  isLoading.value = false;
});
</script>

<style scoped>
p {
  padding: 0.5rem;
  background-color: #f9f9f9;
  border-radius: 0.375rem;
}
</style>
