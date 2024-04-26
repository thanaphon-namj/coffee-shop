<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { Customer } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    customers: Customer[];
}>();
</script>

<template>
    <Head title="รายชื่อลูกค้า" />
    <DashboardLayout path="customers">
        <div class="d-flex justify-content-end mb-3">
            <Link
                :href="route('customers.create')"
                class="btn btn-primary btn-lg"
                style="width: 150px"
            >
                เพิ่มลูกค้า
            </Link>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center" width="8%">ชื่อ</th>
                    <th class="text-center">นามสกุล</th>
                    <th class="text-center">วันเกิด</th>
                    <th class="text-center">หมายเลขโทรศัพท์</th>
                    <th class="text-center">คะแนน</th>
                    <th class="text-center">วันที่ลงทะเบียน</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="customer in customers">
                    <tr>
                        <th class="text-center">{{ customer.CustomerID }}</th>
                        <td class="text-center">{{ customer.FirstName }}</td>
                        <td class="text-center">{{ customer.LastName }}</td>
                        <td class="text-center">{{ customer.BirthDate }}</td>
                        <td class="text-center">{{ customer.PhoneNo }}</td>
                        <td class="text-center">{{ customer.Points }}</td>
                        <td class="text-center">{{ customer.RegisterDate }}</td>
                        <td class="text-center">
                            <Link
                                :href="
                                    route('customers.show', customer.CustomerID)
                                "
                                class="btn btn-link btn-sm"
                            >
                                ดูรายละเอียด
                            </Link>
                        </td>
                    </tr>
                </template>
                <tr v-if="customers.length === 0">
                    <td class="text-center" colspan="8">ไม่มีรายการสินค้า</td>
                </tr>
            </tbody>
        </table>
    </DashboardLayout>
</template>
