<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { Employee } from "@/types";

defineProps<{
    employees: Employee[];
}>();

const form = useForm({
    employeeID: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => {
            form.reset("employeeID");
        },
    });
};
</script>

<template>
    <Head title="เข้าสู่ระบบ" />
    <div
        class="d-flex justify-content-center align-items-center w-100 vh-100 m-auto"
        style="max-width: 330px"
    >
        <form @submit.prevent="submit">
            <div class="mb-3">
                <h1 class="display-5 text-center mb-5">Coffee Shop</h1>
                <select
                    class="form-select"
                    style="width: 300px"
                    v-model="form.employeeID"
                    required
                >
                    <option value="">เลือก...</option>
                    <template v-for="employee in employees">
                        <option :value="employee.EmployeeID">
                            {{ employee.FirstName }} | {{ employee.Position }}
                        </option>
                    </template>
                </select>
            </div>
            <button
                type="submit"
                class="btn btn-primary py-2"
                style="width: 300px"
                :disabled="form.processing"
            >
                เข้าสู่ระบบ
            </button>
        </form>
    </div>
</template>
