<script setup>

</script>

<template>
    <div class="profile-menu">
        <div class="flex flex-col items-center h-full gap-4 py-10 px-3">
            <a href="#" type="button" class="flex flex-col items-center gap-1">
                <img src="assets/images/users/avatar-6.jpg" alt="user-image" class="rounded-full h-8 w-8">
                <span class="font-medium text-base">Jamie D.</span>
                <span class="text-sm">Admin</span>
            </a>

            <button type="button" data-hs-overlay="#search-modal" class="bg-white rounded-full shadow-md p-2">
                <span class="sr-only">Search</span>
                <span class="flex items-center justify-center h-6 w-6">
                        <i class="uil uil-search text-2xl"></i>
                    </span>
            </button>

            <div class="flex">
                <button data-toggle="fullscreen" type="button" class="bg-white rounded-full shadow-md p-2">
                    <span class="sr-only">Fullscreen Mode</span>
                    <span class="flex items-center justify-center h-6 w-6">
                            <i class="uil uil-focus text-2xl"></i>
                        </span>
                </button>
            </div>
        </div>
    </div>

</template>

<style scoped>

</style>
