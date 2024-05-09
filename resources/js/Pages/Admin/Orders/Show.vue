<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Order } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps<{
    order: Order;
}>();

console.log(props.order);
</script>

<template>
    <Head title="รายละเอียดการขาย" />
    <DashboardLayout title="รายละเอียดการขาย" path="orders"></DashboardLayout>
</template>
