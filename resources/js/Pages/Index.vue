<script setup lang="ts">
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";
import { Modal } from "bootstrap";
import { priceFormat, numberFormat } from "@/utils/format";
import { Product, PaymentMethods } from "@/types";

import QRCode from "qrcode.vue";

const props = defineProps<{
    products: Product[];
}>();

const products = ref<Product[]>([]);

const details = ref<
    {
        productID: number;
        productName: string;
        price: number;
        stock: number;
        imageUrl: string;
        quantity: number;
        total: number;
    }[]
>([]);

const hasProduct = (productID: number): boolean => {
    return details.value.some((detail) => detail.productID === productID);
};

const hasDetails = computed<boolean>(() => details.value.length > 0);

const total = computed<number>(() => {
    return details.value.reduce((prev, curr) => prev + curr.total, 0);
});

const form = reactive<{
    total: number;
    payment: PaymentMethods;
    customerID: number | null;
    details: {
        productID: number;
        quantity: number;
        total: number;
    }[];
}>({
    total: 0,
    payment: "Cash",
    customerID: null,
    details: [],
});

const onIncrementQuantity = (productID: number) => {
    details.value = details.value.map((detail) => {
        if (detail.productID === productID) {
            const quantity = detail.quantity + 1;
            return {
                ...detail,
                quantity,
                total: detail.price * quantity,
            };
        }
        return detail;
    });
};

const onDecrementQuantity = (productID: number) => {
    details.value = details.value.map((detail) => {
        if (detail.productID === productID) {
            const quantity = detail.quantity - 1;
            return {
                ...detail,
                quantity,
                total: detail.price * quantity,
            };
        }
        return detail;
    });
};

const onAdd = (product: Product) => {
    if (product.Stock === 0) return;

    const hasDetail = details.value.some(
        (detail) => detail.productID === product.ProductID,
    );

    if (hasDetail) {
        onIncrementQuantity(product.ProductID);
    } else {
        details.value = [
            ...details.value,
            {
                productID: product.ProductID,
                productName: product.ProductName,
                price: product.Price,
                stock: product.Stock,
                imageUrl: product.ImageUrl,
                quantity: 1,
                total: product.Price,
            },
        ];
    }
};

const onRemove = (productID: number) => {
    details.value = details.value.filter(
        (detail) => detail.productID !== productID,
    );
};

const onCancel = () => {
    form.total = 0;
    form.payment = "Cash";
    form.customerID = null;
    form.details = [];

    details.value = [];

    onCancelCustomer();
};

const isLoading = ref<boolean>(false);

const payment = reactive<{
    amount: number;
    qr: string;
    uuid: string;
}>({
    amount: 0,
    qr: "",
    uuid: "",
});

const hasPaymentQR = computed<boolean>(() => payment.qr !== "");

const change = computed<number>(() => payment.amount - total.value);

const intervalID = ref<number | undefined>();

const onSelectPayment = (method: PaymentMethods) => {
    form.payment = method;
    if (method === "PromptPay") {
        payment.amount = 0;
    }
};

const onAddPaymentAmount = (money: number) => {
    payment.amount = payment.amount + money;
};

const onCheckPayment = async () => {
    const res = await axios.post(route("orders.checkpay"), {
        uuid: payment.uuid,
    });
    if (res.data.success === true && res.data.paid === true) {
        clearInterval(intervalID.value);
        submit();
    }
};

const onRequestQR = async () => {
    isLoading.value = true;

    try {
        const res = await axios.post(route("orders.qr"));
        if (res.data.success === true) {
            payment.qr = res.data.qr;
            payment.uuid = res.data.uuid;

            intervalID.value = setInterval(onCheckPayment, 2000);
        }
    } finally {
        isLoading.value = false;
    }
};

const onCancelQR = () => {
    payment.qr = "";
    payment.uuid = "";

    clearInterval(intervalID.value);
};

const onClosePayment = () => {
    form.payment = "Cash";
    payment.amount = 0;
};

const submit = async () => {
    isLoading.value = true;

    try {
        form.total = total.value;
        form.customerID = customer.id;
        form.details = details.value.map((detail) => {
            return {
                productID: detail.productID,
                quantity: detail.quantity,
                total: detail.total,
            };
        });

        const res = await axios.post(route("orders.store"), form);
        if (res.data.success === true) {
            onCancel();

            Modal.getInstance("#paymentModal")?.hide();
        }
    } finally {
        isLoading.value = false;
    }
};

const customer = reactive<{
    enable: boolean;
    q: string;
    id: number | null;
    name: string;
    points: number;
}>({
    enable: false,
    q: "",
    id: null,
    name: "",
    points: 0,
});

const onEnableCustomer = () => {
    customer.enable = true;
};

const onSearchCustomer = async () => {
    const res = await axios.get(route("customers.search"), {
        params: {
            q: customer.q,
        },
    });
    if (res.data.success === true && res.data.customer) {
        customer.id = res.data.customer.CustomerID;
        customer.name = `${res.data.customer.FirstName} ${res.data.customer.LastName}`;
        customer.points = res.data.customer.Points;
    }
};

const onCancelCustomer = () => {
    customer.enable = false;
    customer.q = "";
    customer.id = null;
    customer.name = "";
    customer.points = 0;
};

const params = reactive<{
    q: string;
    category: string;
}>({
    q: "",
    category: "",
});

const onSelectCatgory = (category: string) => {
    params.category = category;
};

const onSearchProduct = async () => {
    const res = await axios.get(route("products.search"), {
        params,
    });
    if (res.data.success === true) {
        products.value = res.data.products;
    }
};

watch(params, () => {
    onSearchProduct();
});

onMounted(() => {
    products.value = props.products;
});
</script>

<template>
    <Head title="POS" />
    <div class="d-flex flex-column">
        <header class="py-2 mb-3 border-bottom">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="navbar-brand user-select-none mb-0">
                        Coffee Shop
                    </h1>
                    <form role="search">
                        <input
                            type="search"
                            class="form-control form-control-lg"
                            style="width: 500px"
                            v-model="params.q"
                            placeholder="ค้นหาสินค้า..."
                        />
                    </form>
                    <div class="dropdown">
                        <button
                            class="nav-link dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            {{ "Username" }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <Link
                                    :href="route('dashboard')"
                                    class="dropdown-item"
                                >
                                    Admin Dashboard
                                </Link>
                            </li>
                            <li>
                                <Link
                                    :href="route('logout')"
                                    class="dropdown-item"
                                    method="post"
                                    as="button"
                                >
                                    ออกจากระบบ
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8" style="max-height: 876px">
                    <ul class="nav nav-pills nav-fill mb-3">
                        <li class="nav-item">
                            <button
                                class="nav-link active"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('')"
                            >
                                ทั้งหมด
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Hot Coffee')"
                            >
                                กาแฟร้อน
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Hot Tea')"
                            >
                                ชาร้อน
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Cold Coffee')"
                            >
                                กาแฟเย็น
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Iced Tea')"
                            >
                                ชาเย็น
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Frappuccino')"
                            >
                                เครื่องดื่มแฟรบปูชิโน่
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="onSelectCatgory('Dessert')"
                            >
                                ขนมและของหวาน
                            </button>
                        </li>
                    </ul>
                    <div class="row overflow-auto" style="height: 870px">
                        <template v-for="product in products">
                            <div class="col-3">
                                <div
                                    class="card mb-3"
                                    :style="{
                                        cursor: hasProduct(product.ProductID)
                                            ? 'default'
                                            : 'pointer',
                                    }"
                                    @click="onAdd(product)"
                                >
                                    <img
                                        :src="product.ImageUrl"
                                        class="card-img-top img-fluid"
                                    />
                                    <div class="card-body text-center">
                                        <h6 class="card-title">
                                            {{ product.ProductName }}
                                        </h6>
                                        <div>
                                            {{ priceFormat(product.Price) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="col-4" style="max-height: 932px">
                    <div class="card" style="height: 916px">
                        <div
                            class="card-header d-flex justify-content-between align-items-center"
                            style="height: 80px"
                        >
                            <div></div>
                            <h5 class="mb-0">รายการสินค้า</h5>
                            <button
                                v-if="hasDetails"
                                type="button"
                                class="btn btn-danger btn-sm p-2 lh-1"
                                title="ยกเลิกออเดอร์"
                                @click="onCancel"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16"
                                    height="16"
                                    fill="currentColor"
                                    class="bi bi-x-lg"
                                    viewBox="0 0 16 16"
                                >
                                    <path
                                        d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"
                                    />
                                </svg>
                            </button>
                            <div v-else></div>
                        </div>
                        <div
                            v-if="customer.enable"
                            class="card-header bg-secondary-subtle"
                        >
                            <div
                                v-if="customer.id"
                                class="d-flex justify-content-center align-items-center"
                            >
                                <div class="flex-grow-1 text-center">
                                    {{ customer.name }}
                                    <br />
                                    คะแนนสะสม
                                    {{ numberFormat(customer.points) }} แต้ม
                                </div>
                                <div class="flex-shrink-1 ms-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="onCancelCustomer"
                                    >
                                        ยกเลิก
                                    </button>
                                </div>
                            </div>
                            <div
                                v-else
                                class="d-flex justify-content-center align-items-center"
                            >
                                <div class="flex-grow-1">
                                    <input
                                        type="search"
                                        class="form-control"
                                        v-model="customer.q"
                                        placeholder="หมายเลขโทรศัพท์"
                                    />
                                </div>
                                <div class="flex-shrink-1 ms-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        :disabled="!customer.q"
                                        @click="onSearchCustomer"
                                    >
                                        ตกลง
                                    </button>
                                </div>
                                <div class="flex-shrink-1 ms-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="onCancelCustomer"
                                    >
                                        ยกเลิก
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div
                            v-else
                            class="card-header d-flex justify-content-center align-items-center bg-secondary-subtle"
                            style="cursor: pointer"
                            @click="onEnableCustomer"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                class="me-2"
                                fill="currentColor"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"
                                />
                                <path
                                    d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"
                                />
                            </svg>
                            เพิ่มสมาชิก
                        </div>
                        <div class="card-body overflow-auto">
                            <template v-for="detail in details">
                                <div
                                    class="d-flex justify-content-center align-items-center mb-2"
                                    style="height: 80px"
                                >
                                    <div style="width: 80px">
                                        <img
                                            :src="detail.imageUrl"
                                            class="img-thumbnail border-0"
                                        />
                                    </div>
                                    <div
                                        class="flex-grow-1 text-center text-truncate px-2"
                                        style="max-width: 190px"
                                    >
                                        {{ detail.productName }}
                                    </div>
                                    <div class="flex-shrink-1">
                                        <div class="input-group">
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                title="ลดจำนวน"
                                                :disabled="
                                                    detail.quantity === 1
                                                "
                                                @click="
                                                    onDecrementQuantity(
                                                        detail.productID,
                                                    )
                                                "
                                            >
                                                -
                                            </button>
                                            <input
                                                type="text"
                                                class="form-control text-center"
                                                style="width: 45px"
                                                v-model="detail.quantity"
                                                placeholder="จำนวน"
                                                disabled
                                            />
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                title="เพิ่มจำนวน"
                                                :disabled="
                                                    detail.quantity ===
                                                    detail.stock
                                                "
                                                @click="
                                                    onIncrementQuantity(
                                                        detail.productID,
                                                    )
                                                "
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-center">
                                        {{ priceFormat(detail.total) }}
                                    </div>
                                    <div class="flex-shrink-1">
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm p-2 lh-1"
                                            title="ลบสินค้า"
                                            @click="onRemove(detail.productID)"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="16"
                                                height="16"
                                                fill="currentColor"
                                                class="bi bi-x-lg"
                                                viewBox="0 0 16 16"
                                            >
                                                <path
                                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div
                            v-if="hasDetails"
                            class="card-footer d-flex justify-content-between align-items-center bg-success text-white px-4"
                            style="height: 100px; cursor: pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#paymentModal"
                        >
                            <h4 class="mb-0">รับชำระเงิน</h4>
                            <h2 class="mb-0">{{ priceFormat(total) }}</h2>
                        </div>
                        <div
                            v-else
                            class="card-footer d-flex justify-content-between align-items-center bg-success-subtle text-white px-4"
                            style="height: 100px"
                        >
                            <h4 class="mb-0">รับชำระเงิน</h4>
                            <h2 class="mb-0">฿0.00</h2>
                        </div>
                    </div>
                    <div
                        class="modal fade"
                        id="paymentModal"
                        data-bs-backdrop="static"
                        data-bs-keyboard="false"
                        tabindex="-1"
                    >
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">การชำระเงิน</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        :disabled="isLoading || hasPaymentQR"
                                        @click="onClosePayment"
                                    />
                                </div>
                                <div class="modal-body">
                                    <div
                                        class="d-flex justify-content-center mb-5"
                                    >
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link"
                                                    :class="{
                                                        active:
                                                            form.payment ===
                                                            'Cash',
                                                    }"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#cash"
                                                    :disabled="hasPaymentQR"
                                                    @click="
                                                        onSelectPayment('Cash')
                                                    "
                                                >
                                                    เงินสด
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link"
                                                    :class="{
                                                        active:
                                                            form.payment ===
                                                            'PromptPay',
                                                    }"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#promptpay"
                                                    :disabled="isLoading"
                                                    @click="
                                                        onSelectPayment(
                                                            'PromptPay',
                                                        )
                                                    "
                                                >
                                                    พร้อมเพย์
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content">
                                        <div
                                            id="cash"
                                            class="tab-pane"
                                            :class="{
                                                'show active':
                                                    form.payment === 'Cash',
                                            }"
                                        >
                                            <div class="row">
                                                <div class="col-9">
                                                    <div
                                                        class="d-flex flex-column align-items-center"
                                                    >
                                                        <div
                                                            class="mb-5 text-center"
                                                        >
                                                            <h4>ยอดชำระรวม</h4>
                                                            <h2>
                                                                {{
                                                                    priceFormat(
                                                                        total,
                                                                    )
                                                                }}
                                                            </h2>
                                                        </div>
                                                        <div
                                                            class="d-grid col-6 mb-4"
                                                        >
                                                            <div
                                                                class="input-group input-group-lg"
                                                            >
                                                                <input
                                                                    type="number"
                                                                    class="form-control"
                                                                    v-model.number="
                                                                        payment.amount
                                                                    "
                                                                    min="0"
                                                                    max="100000"
                                                                    placeholder="รับเงินมา"
                                                                    :disabled="
                                                                        isLoading
                                                                    "
                                                                />
                                                                <span
                                                                    class="input-group-text"
                                                                >
                                                                    ฿
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <h5 v-if="change < 0">
                                                            เงินทอน ฿0.00
                                                        </h5>
                                                        <h5 v-else>
                                                            เงินทอน
                                                            {{
                                                                priceFormat(
                                                                    change,
                                                                )
                                                            }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div
                                                        class="d-grid gap-2 col-8 text-center"
                                                    >
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-success"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                onAddPaymentAmount(
                                                                    20,
                                                                )
                                                            "
                                                        >
                                                            ฿20
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-info"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                onAddPaymentAmount(
                                                                    50,
                                                                )
                                                            "
                                                        >
                                                            ฿50
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-danger"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                onAddPaymentAmount(
                                                                    100,
                                                                )
                                                            "
                                                        >
                                                            ฿100
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-secondary"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                onAddPaymentAmount(
                                                                    500,
                                                                )
                                                            "
                                                        >
                                                            ฿500
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-warning"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                onAddPaymentAmount(
                                                                    1000,
                                                                )
                                                            "
                                                        >
                                                            ฿1000
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex justify-content-center"
                                            >
                                                <button
                                                    type="button"
                                                    class="btn btn-success btn-lg mt-5"
                                                    style="width: 200px"
                                                    :disabled="
                                                        isLoading || change < 0
                                                    "
                                                    @click="submit"
                                                >
                                                    ชำระเงิน
                                                </button>
                                            </div>
                                        </div>
                                        <div
                                            id="promptpay"
                                            class="tab-pane"
                                            :class="{
                                                'show active':
                                                    form.payment ===
                                                    'PromptPay',
                                            }"
                                        >
                                            <div
                                                class="d-flex flex-column align-items-center"
                                            >
                                                <div class="mb-5 text-center">
                                                    <h4>ยอดชำระรวม</h4>
                                                    <h2>
                                                        {{ priceFormat(total) }}
                                                    </h2>
                                                </div>
                                                <QRCode
                                                    v-if="hasPaymentQR"
                                                    :value="payment.qr"
                                                    :size="300"
                                                    level="H"
                                                />
                                            </div>
                                            <div
                                                class="d-flex justify-content-center mt-5"
                                            >
                                                <button
                                                    v-if="hasPaymentQR"
                                                    type="button"
                                                    class="btn btn-danger btn-lg"
                                                    style="width: 200px"
                                                    :disabled="isLoading"
                                                    @click="onCancelQR"
                                                >
                                                    ยกเลิก
                                                </button>
                                                <button
                                                    v-else
                                                    type="button"
                                                    class="btn btn-success btn-lg"
                                                    style="width: 200px"
                                                    :disabled="isLoading"
                                                    @click="onRequestQR"
                                                >
                                                    ชำระเงิน
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
