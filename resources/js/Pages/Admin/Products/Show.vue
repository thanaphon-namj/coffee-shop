<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Product } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const props = defineProps<{
    product: Product;
}>();

console.log(props.product);
</script>

<template>
    <Head title="รายละเอียดสินค้า" />
    <DashboardLayout title="รายละเอียดสินค้า" path="products">
        Show
    </DashboardLayout>
</template>
