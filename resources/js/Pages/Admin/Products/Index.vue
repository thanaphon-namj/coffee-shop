<script setup lang="ts">
import { Head, Link } from "@inertiajs/vue3";
import { Product } from "@/types";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";

defineProps<{
    products: Product[];
}>();
</script>

<template>
    <Head title="รายการสินค้า" />
    <DashboardLayout path="products">
        <div class="d-flex justify-content-end mb-3">
            <Link
                :href="route('products.create')"
                class="btn btn-primary btn-lg"
                style="width: 150px"
            >
                เพิ่มสินค้า
            </Link>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center" width="8%">รูปภาพ</th>
                    <th class="text-center">ชื่อสินค้า</th>
                    <th class="text-center">หมวดหมู่</th>
                    <th class="text-center">ราคาขาย</th>
                    <th class="text-center">จำนวนคงเหลือ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="product in products">
                    <tr>
                        <th class="text-center">{{ product.ProductID }}</th>
                        <td class="text-center">
                            <img
                                :src="product.ImageUrl"
                                class="img-thumbnail border-0"
                            />
                        </td>
                        <td class="text-center">{{ product.ProductName }}</td>
                        <td class="text-center">{{ product.Category }}</td>
                        <td class="text-center">{{ product.Price }}</td>
                        <td class="text-center">{{ product.Stock }}</td>
                        <td class="text-center">
                            <Link
                                :href="
                                    route('products.show', product.ProductID)
                                "
                                class="btn btn-link btn-sm"
                            >
                                ดูรายละเอียด
                            </Link>
                        </td>
                    </tr>
                </template>
                <tr v-if="products.length === 0">
                    <td class="text-center" colspan="7">ไม่มีรายการสินค้า</td>
                </tr>
            </tbody>
        </table>
    </DashboardLayout>
</template>
