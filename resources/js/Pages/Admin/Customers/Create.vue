<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const form = useForm({
    firstName: "",
    lastName: "",
    birthDate: "",
    phoneNo: "",
});

const submit = () => {
    form.post(route("customers.store"), {
        onFinish: () => {
            form.reset("firstName", "lastName", "birthDate", "phoneNo");
        },
    });
};
</script>

<template>
    <Head title="เพิ่มลูกค้า" />
    <DashboardLayout title="เพิ่มลูกค้า" path="customers">
        <div class="row">
            <div class="col-4">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.firstName"
                            maxlength="20"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.lastName"
                            maxlength="20"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">วันเกิด</label>
                        <input
                            type="date"
                            class="form-control"
                            v-model="form.birthDate"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">หมายเลขโทรศัพท์</label>
                        <input
                            type="phone"
                            class="form-control"
                            v-model="form.phoneNo"
                            maxlength="10"
                        />
                    </div>
                    <div class="mt-5">
                        <Link
                            :href="route('customers.index')"
                            class="btn btn-danger"
                            style="width: 100px"
                        >
                            ย้อนกลับ
                        </Link>
                        <button
                            type="submit"
                            class="btn btn-primary ms-3"
                            style="width: 100px"
                            :disabled="form.processing"
                        >
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>
