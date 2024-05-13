<script setup lang="ts">
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps<{
    uuid: "";
}>();

const isLoading = ref<boolean>(false);
const isPaid = ref<boolean>(false);

const onPay = async () => {
    isLoading.value = true;

    try {
        const res = await axios.post(route("orders.paid"), {
            uuid: props.uuid,
        });
        if (res.data.success === true && res.data.paid === true) {
            isPaid.value = true;
        }
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head title="จ่ายเงิน" />
    <div
        class="d-flex justify-content-center align-items-center w-100 vh-100 m-auto"
    >
        <button
            type="button"
            class="btn btn-success btn-lg"
            style="width: 200px"
            :disabled="isLoading || isPaid"
            @click="onPay"
        >
            จ่ายเงิน
        </button>
    </div>
</template>
