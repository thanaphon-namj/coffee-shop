<script setup lang="ts">
import { computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import { Order, OrderDetail, Product, Employee, Customer } from "@/types";

import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { priceFormat, numberFormat } from "@/utils/format";

interface AdminOrderDetail extends OrderDetail {
    product: Product;
}

interface AdminOrder extends Order {
    details: AdminOrderDetail[];
    employee: Employee;
    customer: Customer | null;
}

const props = defineProps<{
    order: AdminOrder;
    canDelete: boolean;
}>();

const details = computed<AdminOrderDetail[]>(() => props.order.details);
const employee = computed<Employee>(() => props.order.employee);
const customer = computed<Customer | null>(() => props.order.customer);
</script>

<template>
    <Head title="รายละเอียดการขาย" />
    <DashboardLayout
        :title="`รายละเอียดการขาย #${order.OrderID}`"
        path="orders"
    >
        <div class="row">
            <div class="col-4">
                <div class="mb-3">
                    <h5 class="form-label">วันที่ซื้อ</h5>
                    <p class="lead">{{ order.PurchaseDate }}</p>
                </div>
                <div class="mb-3">
                    <h5 class="form-label">วิธีชำระเงิน</h5>
                    <p class="lead">{{ order.Payment }}</p>
                </div>
                <div class="mb-3">
                    <h5>พนักงานขาย</h5>
                    <Link
                        :href="route('employees.show', employee.EmployeeID)"
                        class="lead"
                    >
                        {{ employee.FirstName }} {{ employee.LastName }}
                    </Link>
                </div>
                <div class="mb-3">
                    <h5>ลูกค้า</h5>
                    <Link
                        v-if="customer"
                        :href="route('customers.show', customer.CustomerID)"
                        class="lead"
                    >
                        {{ customer.FirstName }} {{ customer.LastName }}
                    </Link>
                    <p v-else class="lead">-</p>
                </div>
            </div>
        </div>
        <h4 class="mt-4 mb-3">รายการสินค้า</h4>
        <table class="table table-bordered mb-5">
            <thead>
                <tr>
                    <th class="text-center" width="5%">#</th>
                    <th class="text-center" width="8%">รูปภาพ</th>
                    <th class="text-center">ชื่อสินค้า</th>
                    <th class="text-center">ราคาสินค้า</th>
                    <th class="text-center">จำนวน</th>
                    <th class="text-center">รวม</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(detail, index) in details">
                    <tr>
                        <th class="text-center">{{ index + 1 }}</th>
                        <td class="text-center">
                            <img
                                :src="detail.product.ImageUrl"
                                class="img-thumbnail border-0"
                            />
                        </td>
                        <td class="text-center">
                            <Link
                                :href="
                                    route(
                                        'products.show',
                                        detail.product.ProductID,
                                    )
                                "
                            >
                                {{ detail.product.ProductName }}
                            </Link>
                        </td>
                        <td class="text-center">
                            {{ priceFormat(detail.product.Price) }}
                        </td>
                        <td class="text-center">
                            {{ numberFormat(detail.Quantity) }}
                        </td>
                        <td class="text-center">
                            {{ priceFormat(detail.Total) }}
                        </td>
                    </tr>
                </template>
                <tr v-if="details.length === 0">
                    <td class="text-center" colspan="5">ไม่มีรายการสินค้า</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <h3>รวมทั้งหมด: {{ priceFormat(order.Total) }}</h3>
        </div>
        <div class="mt-5" style="margin-bottom: 200px">
            <Link
                :href="route('orders.index')"
                class="btn btn-danger"
                style="width: 100px"
            >
                ย้อนกลับ
            </Link>
            <Link
                v-if="canDelete"
                :href="route('orders.destroy', order.OrderID)"
                class="btn btn-warning ms-3"
                style="width: 100px"
                method="delete"
                as="button"
            >
                ลบ
            </Link>
        </div>
    </DashboardLayout>
</template>
