<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { Order } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    orders: Order[];
}>();
</script>

<template>
    <Head title="รายการขาย" />
    <DashboardLayout path="orders">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center">วันที่ซื้อ</th>
                    <th class="text-center">วิธีชำระเงิน</th>
                    <th class="text-center">ยอดรวม</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="order in orders">
                    <tr>
                        <th class="text-center">{{ order.OrderID }}</th>
                        <td class="text-center">{{ order.PurchaseDate }}</td>
                        <td class="text-center">{{ order.Payment }}</td>
                        <td class="text-center">{{ order.Total }}</td>
                        <td class="text-center">
                            <Link
                                :href="route('orders.show', order.OrderID)"
                                class="btn btn-link btn-sm"
                            >
                                ดูรายละเอียด
                            </Link>
                        </td>
                    </tr>
                </template>
                <tr v-if="orders.length === 0">
                    <td class="text-center" colspan="5">ไม่มีรายการขาย</td>
                </tr>
            </tbody>
        </table>
    </DashboardLayout>
</template>
