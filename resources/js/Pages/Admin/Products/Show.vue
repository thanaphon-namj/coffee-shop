<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { Product } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { priceFormat, numberFormat, dashFormat } from "@/utils/format";

defineProps<{
    product: Product;
    canDelete: boolean;
}>();

const goBack = () => {
    window.history.back();
};
</script>

<template>
    <Head title="รายละเอียดสินค้า" />
    <DashboardLayout
        :title="`รายละเอียดสินค้า #${product.ProductID}`"
        path="products"
    >
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <h5>ชื่อสินค้า</h5>
                    <p class="lead">{{ product.ProductName }}</p>
                </div>
                <div class="mb-3">
                    <h5>รายละเอียด</h5>
                    <p class="lead">{{ dashFormat(product.Description) }}</p>
                </div>
                <div class="mb-3">
                    <h5>หมวดหมู่</h5>
                    <p class="lead">{{ product.Category }}</p>
                </div>
                <div class="mb-3">
                    <h5>ราคาขาย</h5>
                    <p class="lead">{{ priceFormat(product.Price) }}</p>
                </div>
                <div class="mb-3">
                    <h5>คงเหลือ</h5>
                    <p class="lead">{{ numberFormat(product.Stock) }}</p>
                </div>
                <div class="mb-3">
                    <h5>รูปภาพ</h5>
                    <div class="d-grid col-6">
                        <img
                            :src="product.ImageUrl"
                            class="img-thumbnail border-0"
                        />
                    </div>
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
                        :href="route('products.destroy', product.ProductID)"
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
