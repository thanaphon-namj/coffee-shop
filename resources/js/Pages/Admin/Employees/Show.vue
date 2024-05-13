<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { numberFormat, dashFormat } from "@/utils/format";
import { Employee } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    employee: Employee;
    canDelete: boolean;
}>();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head title="รายละเอียดพนักงาน" />
    <DashboardLayout
        :title="`รายละเอียดพนักงาน #${employee.EmployeeID}`"
        path="employees"
    >
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <h5>ชื่อ</h5>
                    <p class="lead">{{ employee.FirstName }}</p>
                </div>
                <div class="mb-3">
                    <h5>นามสกุล</h5>
                    <p class="lead">{{ employee.LastName }}</p>
                </div>
                <div class="mb-3">
                    <h5>วันเกิด</h5>
                    <p class="lead">{{ employee.BirthDate }}</p>
                </div>
                <div class="mb-3">
                    <h5>หมายเลขโทรศัพท์</h5>
                    <p class="lead">{{ employee.PhoneNo }}</p>
                </div>
                <div class="mb-3">
                    <h5>อีเมล</h5>
                    <p class="lead">{{ employee.Email }}</p>
                </div>
                <div class="mb-3">
                    <h5>ตำแหน่ง</h5>
                    <p class="lead">{{ employee.Position }}</p>
                </div>
                <div class="mb-3">
                    <h5>เงินเดือน</h5>
                    <p class="lead">{{ numberFormat(employee.Salary) }}</p>
                </div>
                <div class="mb-3">
                    <h5>วันที่เริ่มงาน</h5>
                    <p class="lead">{{ employee.StartDate }}</p>
                </div>
                <div class="mb-3">
                    <h5>วันที่สิ้นสุด</h5>
                    <p class="lead">{{ dashFormat(employee.EndDate) }}</p>
                </div>
                <div class="mt-5">
                    <button
                        class="btn btn-danger"
                        style="width: 100px"
                        @click="goBack"
                    >
                        ย้อนกลับ
                    </button>
                    <Link
                        v-if="canDelete"
                        :href="route('employees.destroy', employee.EmployeeID)"
                        class="btn btn-warning ms-3"
                        style="width: 100px"
                        method="delete"
                        as="button"
                    >
                        ลบ
                    </Link>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
