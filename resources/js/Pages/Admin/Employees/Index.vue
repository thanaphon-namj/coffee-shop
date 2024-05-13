<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { numberFormat, dashFormat } from "@/utils/format";
import { Employee } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    employees: Employee[];
}>();
</script>

<template>
    <Head title="รายชื่อพนักงาน" />
    <DashboardLayout path="employees">
        <div class="d-flex justify-content-end my-3">
            <Link
                :href="route('employees.create')"
                class="btn btn-primary btn-lg"
                style="width: 150px"
            >
                เพิ่มพนักงาน
            </Link>
        </div>
        <table class="table table-bordered" style="margin-bottom: 200px">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center">ชื่อ</th>
                    <th class="text-center">นามสกุล</th>
                    <th class="text-center">วันเกิด</th>
                    <th class="text-center">หมายเลขโทรศัพท์</th>
                    <th class="text-center">อีเมล</th>
                    <th class="text-center">ตำแหน่ง</th>
                    <th class="text-center">เงินเดือน</th>
                    <th class="text-center">วันที่เริ่มงาน</th>
                    <th class="text-center">วันที่สิ้นสุด</th>
                    <th width="8%"></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="employee in employees">
                    <tr>
                        <th class="text-center">{{ employee.EmployeeID }}</th>
                        <td class="text-center">{{ employee.FirstName }}</td>
                        <td class="text-center">{{ employee.LastName }}</td>
                        <td class="text-center">{{ employee.BirthDate }}</td>
                        <td class="text-center">{{ employee.PhoneNo }}</td>
                        <td class="text-center">{{ employee.Email }}</td>
                        <td class="text-center">{{ employee.Position }}</td>
                        <td class="text-center">
                            {{ numberFormat(employee.Salary) }}
                        </td>
                        <td class="text-center">{{ employee.StartDate }}</td>
                        <td class="text-center">
                            {{ dashFormat(employee.EndDate) }}
                        </td>
                        <td class="text-center">
                            <Link
                                :href="
                                    route('employees.show', employee.EmployeeID)
                                "
                                class="btn btn-link btn-sm"
                            >
                                ดูรายละเอียด
                            </Link>
                        </td>
                    </tr>
                </template>
                <tr v-if="employees.length === 0">
                    <td class="text-center" colspan="11">
                        ไม่มีรายชื่อพนักงาน
                    </td>
                </tr>
            </tbody>
        </table>
    </DashboardLayout>
</template>
