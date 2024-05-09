<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { numberFormat, dashFormat } from "@/utils/format";
import { Customer } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    customer: Customer;
    canDelete: boolean;
}>();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head title="รายละเอียดลูกค้า" />
    <DashboardLayout
        :title="`รายละเอียดลูกค้า #${customer.CustomerID}`"
        path="customers"
    >
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <h5>ชื่อ</h5>
                    <p class="lead">{{ customer.FirstName }}</p>
                </div>
                <div class="mb-3">
                    <h5>นามสกุล</h5>
                    <p class="lead">{{ customer.LastName }}</p>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">วันเกิด</h5>
                    <p class="lead">{{ dashFormat(customer.BirthDate) }}</p>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">หมายเลขโทรศัพท์</h5>
                    <p class="lead">{{ customer.PhoneNo }}</p>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">คะแนนสะสม</h5>
                    <p class="lead">{{ numberFormat(customer.Points) }} แต้ม</p>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">วันที่ลงทะเบียน</h5>
                    <p class="lead">{{ customer.RegisterDate }}</p>
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
                        :href="route('customers.destroy', customer.CustomerID)"
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
