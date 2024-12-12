<template>
  <div class="relative bg-gradient-to-r from-primary-400 to-secondary-300 p-6 sm:p-8 rounded-lg overflow-hidden mb-4">
    <div
        aria-hidden="true"
        class="absolute right-0 top-0 -mt-4 mr-16 pointer-events-none hidden xl:block"
    >
    </div>

    <div class="relative">
      <h1 class="text-2xl md:text-3xl text-white font-bold mb-2">
        {{ greeting() }}, {{ authUser.name }} 👋
      </h1>
      <p class="text-white text-sm md:text-base">Here is what’s happening with your application today:</p>
    </div>
  </div>

</template>

<script setup>
let props = defineProps({
  authUser: {
    type: Object,
    required: true,
  },
  role: {
    type: Object,
    required: true,
  },
});
function greeting() {
    const date = new Date();
    const hrs = date.getHours();

    let greet;

    if (hrs < 12) greet = "Good Morning";
    else if (hrs >= 12 && hrs <= 17) greet = "Good Afternoon";
    else if (hrs >= 17 && hrs <= 24) greet = "Good Evening";
    return greet;
}
</script>
