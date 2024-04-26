<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

const form = useForm({
    firstName: "",
    lastName: "",
    birthDate: "",
    phoneNo: "",
    email: "",
    position: "",
    salary: "",
    startDate: "",
});

const submit = () => {
    form.post(route("employees.store"), {
        onFinish: () => {
            form.reset(
                "firstName",
                "lastName",
                "birthDate",
                "phoneNo",
                "email",
                "position",
                "salary",
                "startDate",
            );
        },
    });
};
</script>

<template>
    <Head title="เพิ่มพนักงาน" />
    <DashboardLayout title="เพิ่มพนักงาน" path="employees">
        <div class="row">
            <div class="col-4">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <label class="form-label">ชื่อ</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.firstName"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">นามสกุล</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.lastName"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">วันเกิด</label>
                        <input
                            type="date"
                            class="form-control"
                            v-model="form.birthDate"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">หมายเลขโทรศัพท์</label>
                        <input
                            type="phone"
                            class="form-control"
                            v-model="form.phoneNo"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">อีเมล</label>
                        <input
                            type="email"
                            class="form-control"
                            v-model="form.email"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ตำแหน่ง</label>
                        <select
                            class="form-select"
                            v-model="form.position"
                            required
                        >
                            <option value="">เลือก...</option>
                            <option value="Manager">ผู้จัดการ</option>
                            <option value="Barista">ชงกาแฟ</option>
                            <option value="Baker">ทำเบเกอรี่</option>
                            <option value="Cashier">แคชเชียร์</option>
                            <option value="Housekeeping">แม่บ้าน</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">เงินเดือน</label>
                        <input
                            type="number"
                            class="form-control"
                            v-model="form.salary"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">วันที่เริ่มงาน</label>
                        <input
                            type="date"
                            class="form-control"
                            v-model="form.startDate"
                            required
                        />
                    </div>
                    <div>
                        <Link
                            :href="route('employees.index')"
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
