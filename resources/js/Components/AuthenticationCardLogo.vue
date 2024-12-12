<script setup>
import { Link } from '@inertiajs/vue3';
import {usePage} from "@inertiajs/inertia-vue3";

let page = usePage().props.value;
let logo = page.logo;
let selectedRole = page.selectedRole;
let governmentLogo = page.governmentLogo;
</script>

<template>
      <div class="flex gap-4">
<!--        <Link href="/"><img :src="governmentLogo" class="h-20"></Link>-->
        <Link href=""><img :src="logo" class="h-20"></Link>
      </div>
</template>
