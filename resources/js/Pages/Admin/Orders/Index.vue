<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { priceFormat } from "@/utils/format";
import { Order, Employee, Customer } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

interface AdminOrder extends Order {
    employee: Employee;
    customer: Customer | null;
}

defineProps<{
    orders: AdminOrder[];
}>();
</script>

<template>
    <Head title="รายการขาย" />
    <DashboardLayout path="orders">
        <table class="table table-bordered mt-5" style="margin-bottom: 200px">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center">วันที่</th>
                    <th class="text-center">พนักงานขาย</th>
                    <th class="text-center">ลูกค้า</th>
                    <th class="text-center">วิธีชำระเงิน</th>
                    <th class="text-center">ยอดรวม</th>
                    <th width="8%"></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="order in orders">
                    <tr>
                        <th class="text-center">{{ order.OrderID }}</th>
                        <td class="text-center">{{ order.PurchaseDate }}</td>
                        <td class="text-center">
                            {{ order.employee.FirstName }}
                            {{ order.employee.LastName }}
                        </td>
                        <td class="text-center">
                            {{
                                order.customer
                                    ? `${order.customer.FirstName} ${order.customer.LastName}`
                                    : "-"
                            }}
                        </td>
                        <td class="text-center">{{ order.Payment }}</td>
                        <td class="text-center">
                            {{ priceFormat(order.Total) }}
                        </td>
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
