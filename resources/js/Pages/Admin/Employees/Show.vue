<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Employee } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps<{
    employee: Employee;
}>();

console.log(props.employee);
</script>

<template>
    <Head title="รายละเอียดพนักงาน" />
    <DashboardLayout
        title="รายละเอียดพนักงาน"
        path="employees"
    ></DashboardLayout>
</template>
