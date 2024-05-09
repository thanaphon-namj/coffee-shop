<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Customer } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps<{
    customer: Customer;
}>();

console.log(props.customer);
</script>

<template>
    <Head title="รายละเอียดลูกค้า" />
    <DashboardLayout
        title="รายละเอียดลูกค้า"
        path="customers"
    ></DashboardLayout>
</template>
