<script setup lang="ts">
import { ref } from "vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";

const props = defineProps<{
    uuid: "";
}>();

const isLoading = ref(false);

const pay = () => {
    isLoading.value = true;

    try {
        axios.post(route("orders.paid"), {
            uuid: props.uuid,
        });
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <Head title="Pay" />
    <div>
        <button type="button" class="btn btn-primary btn-lg" @click="pay">
            จ่ายเงิน
        </button>
    </div>
</template>
