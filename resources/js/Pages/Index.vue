<script setup lang="ts">
import { ref, reactive, computed, watch, onMounted } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";
import { Modal } from "bootstrap";
import QRCode from "qrcode.vue";
import { Product } from "@/types";

const props = defineProps<{
    products: Product[];
}>();

const form = ref<{
    total: number;
    payment: "Cash" | "PromptPay";
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

const products = ref<Product[]>([]);

const numberFormat = (amount: number) => {
    return Intl.NumberFormat("th-TH", {
        style: "currency",
        currency: "THB",
    }).format(amount);
};

const details = ref<
    {
        productID: number;
        productName: string;
        imageUrl: string;
        price: number;
        stock: number;
        quantity: number;
        total: number;
    }[]
>([]);

const addDetail = (product: Product) => {
    if (product.Stock === 0) return;

    const detail = details.value.find(
        (detail) => detail.productID === product.ProductID,
    );

    if (detail) {
        increment(product.ProductID);
    } else {
        details.value = [
            ...details.value,
            {
                productID: product.ProductID,
                productName: product.ProductName,
                imageUrl: product.ImageUrl,
                price: product.Price,
                stock: product.Stock,
                quantity: 1,
                total: product.Price,
            },
        ];
    }

    products.value = products.value.map((p) => {
        if (p.ProductID === product.ProductID) {
            return {
                ...p,
                Stock: p.Stock - 1,
            };
        }
        return p;
    });
};

const onQuantityChanged = () => {
    details.value = details.value.map((detail) => {
        return {
            ...detail,
            total: detail.price * detail.quantity,
        };
    });
};

const increment = (productID: number) => {
    details.value = details.value.map((detail) => {
        if (detail.productID === productID) {
            return { ...detail, quantity: detail.quantity + 1 };
        }
        return detail;
    });
    onQuantityChanged();
};

const decrement = (productID: number) => {
    details.value = details.value.map((detail) => {
        if (detail.productID === productID) {
            return { ...detail, quantity: detail.quantity + -1 };
        }
        return detail;
    });
    onQuantityChanged();
};

const removeDetail = (productID: number) => {
    details.value = details.value.filter(
        (detail) => detail.productID !== productID,
    );
};

const amount = ref(0);

const total = computed(() => {
    return details.value.reduce((prev, curr) => prev + curr.total, 0);
});

const change = computed(() => amount.value - total.value);

const totalFormat = computed(() => {
    return Intl.NumberFormat("th-TH", {
        style: "currency",
        currency: "THB",
    }).format(total.value);
});

const changeFormat = computed(() => {
    return Intl.NumberFormat("th-TH", {
        style: "currency",
        currency: "THB",
    }).format(change.value);
});

const setAmount = (money: number) => {
    amount.value = money;
};

const setPayment = (method: "Cash" | "PromptPay") => {
    form.value.payment = method;
};

const isLoading = ref(false);
const qrCode = ref("");
const uuid = ref("");
let intervalID: number | undefined;

const requestQRCode = async () => {
    isLoading.value = true;

    try {
        const res = await axios.post(route("orders.qr"));

        if (res.data.success === true) {
            qrCode.value = res.data.qr;
            uuid.value = res.data.uuid;

            intervalID = setInterval(() => {
                checkPaid();
            }, 2000);
        }
    } finally {
        isLoading.value = false;
    }
};

const cancelQR = () => {
    qrCode.value = "";
    clearInterval(intervalID);
};

const checkPaid = async () => {
    const res = await axios.post(route("orders.pay.check"), {
        uuid: uuid.value,
    });

    if (res.data.success === true && res.data.paid === true) {
        clearInterval(intervalID);
        submit();
    }
};

const submit = async () => {
    isLoading.value = true;

    try {
        form.value.total = total.value;
        form.value.customerID = customer.id;
        form.value.details = details.value.map((detail) => {
            return {
                productID: detail.productID,
                quantity: detail.quantity,
                total: detail.total,
            };
        });

        const res = await axios.post(route("orders.store"), form.value);

        if (res.data.success === true) {
            cancelDetail();

            Modal.getInstance("#paymentModal")?.hide();
        }
    } finally {
        isLoading.value = false;
    }
};

const cancelDetail = () => {
    form.value.total = 0;
    form.value.payment = "Cash";
    form.value.customerID = null;
    form.value.details = [];

    details.value = [];

    amount.value = 0;
};

const customer = reactive({
    enable: false,
    q: "",
    id: null,
    firstName: "",
    lastName: "",
});

const addCustomer = () => {
    customer.enable = true;
};

const getCutomer = async () => {
    const res = await axios.get(route("customers.search"), {
        params: {
            q: customer.q,
        },
    });

    if (res.data.success === true && res.data.customer) {
        customer.id = res.data.customer.CustomerID;
        customer.firstName = res.data.customer.FirstName;
        customer.lastName = res.data.customer.LastName;
    }
};

const clearCustomer = () => {
    customer.enable = false;
    customer.q = "";
    customer.id = null;
    customer.firstName = "";
    customer.lastName = "";
};

const query = reactive({
    q: "",
    category: "",
});

const setCatgory = (category: string) => {
    query.category = category;
};

const search = async () => {
    const res = await axios.get(route("products.search"), {
        params: query,
    });

    if (res.data.success === true) {
        products.value = res.data.products;
    }
};

watch(query, () => {
    search();
});

onMounted(() => {
    products.value = props.products;
});
</script>

<template>
    <Head title="POS" />
    <div>
        <nav class="navbar bg-body-tertiary">
            <div class="container-fluid">
                <div class="d-flex justify-content-start align-items-center">
                    <span class="navbar-brand mb-0 h1 user-select-none">
                        Coffee Shop
                    </span>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <form role="search">
                        <input
                            type="search"
                            class="form-control form-control-lg"
                            style="width: 500px"
                            v-model="query.q"
                            placeholder="ค้นหาสินค้า..."
                        />
                    </form>
                </div>
                <div class="d-flex justify-content-end align-items-center">
                    <div class="dropdown">
                        <button
                            class="nav-link dropdown-toggle"
                            data-bs-toggle="dropdown"
                        >
                            {{ $page.props.employee?.FirstName }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <Link
                                    :href="route('dashboard')"
                                    class="dropdown-item"
                                >
                                    ผู้ดูแลระบบ
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
        </nav>
        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-8">
                    <ul class="nav nav-pills nav-fill mb-3">
                        <li class="nav-item">
                            <button
                                class="nav-link active"
                                data-bs-toggle="pill"
                                @click="setCatgory('')"
                            >
                                ทั้งหมด
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Hot Coffee')"
                            >
                                กาแฟร้อน
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Hot Tea')"
                            >
                                ชาร้อน
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Cold Coffee')"
                            >
                                กาแฟเย็น
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Iced Tea')"
                            >
                                ชาเย็น
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Frappuccino')"
                            >
                                เครื่องดื่มแฟรบปูชิโน่
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                class="nav-link"
                                data-bs-toggle="pill"
                                @click="setCatgory('Dessert')"
                            >
                                ขนมและของหวาน
                            </button>
                        </li>
                    </ul>
                    <div class="row vh-100 overflow-auto">
                        <template v-for="product in products">
                            <div class="col-3">
                                <div
                                    class="card mb-3"
                                    :style="{
                                        cursor:
                                            product.Stock === 0
                                                ? 'default'
                                                : 'pointer',
                                    }"
                                    @click="addDetail(product)"
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
                                            {{ numberFormat(product.Price) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card vh-100">
                        <div
                            class="card-header d-flex justify-content-between align-items-center"
                            style="height: 80px"
                        >
                            <div></div>
                            <h5 class="mb-0">รายการสินค้า</h5>
                            <button
                                v-if="details.length > 0"
                                type="button"
                                class="btn btn-danger btn-sm p-2 lh-1"
                                title="ยกเลิกออเดอร์"
                                @click="cancelDetail"
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
                                    {{ customer.firstName }}
                                    {{ customer.lastName }}
                                </div>
                                <div class="flex-shrink-1 ms-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="clearCustomer"
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
                                        @click="getCutomer"
                                    >
                                        ตกลง
                                    </button>
                                </div>
                                <div class="flex-shrink-1 ms-3">
                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        @click="clearCustomer"
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
                            @click="addCustomer"
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
                                    class="row justify-content-center align-items-center"
                                >
                                    <div class="col-2 mb-1">
                                        <img
                                            :src="detail.imageUrl"
                                            class="img-thumbnail border-0"
                                        />
                                    </div>
                                    <div class="col-2">
                                        {{ detail.productName }}
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                title="ลดจำนวน"
                                                :disabled="detail.quantity <= 1"
                                                @click="
                                                    decrement(detail.productID)
                                                "
                                            >
                                                -
                                            </button>
                                            <input
                                                type="number"
                                                class="form-control text-center"
                                                v-model="detail.quantity"
                                                min="1"
                                                :max="detail.stock"
                                                placeholder="จำนวน"
                                                @change="onQuantityChanged"
                                            />
                                            <button
                                                class="btn btn-outline-secondary"
                                                type="button"
                                                title="เพิ่มจำนวน"
                                                :disabled="
                                                    detail.quantity >=
                                                    detail.stock
                                                "
                                                @click="
                                                    increment(detail.productID)
                                                "
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        {{ numberFormat(detail.total) }}
                                    </div>
                                    <div class="col-1">
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm p-2 lh-1"
                                            title="ลบสินค้า"
                                            @click="
                                                removeDetail(detail.productID)
                                            "
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
                            v-if="details.length > 0"
                            class="card-footer d-flex justify-content-between align-items-center bg-success text-white px-4"
                            style="height: 100px; cursor: pointer"
                            data-bs-toggle="modal"
                            data-bs-target="#paymentModal"
                        >
                            <h4 class="mb-0">รับชำระเงิน</h4>
                            <h2 class="mb-0">{{ totalFormat }}</h2>
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
                                        :disabled="isLoading || !!qrCode"
                                    ></button>
                                </div>
                                <div class="modal-body">
                                    <div
                                        class="d-flex justify-content-center mb-5"
                                    >
                                        <ul class="nav nav-pills">
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link active"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#cash"
                                                    :disabled="!!qrCode"
                                                    @click="setPayment('Cash')"
                                                >
                                                    เงินสด
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button
                                                    class="nav-link"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#promptpay"
                                                    :disabled="isLoading"
                                                    @click="
                                                        setPayment('PromptPay')
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
                                            class="tab-pane show active"
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
                                                                    totalFormat
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
                                                                    v-model="
                                                                        amount
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
                                                            {{ changeFormat }}
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
                                                                setAmount(20)
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
                                                                setAmount(50)
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
                                                                setAmount(100)
                                                            "
                                                        >
                                                            ฿100
                                                        </button>
                                                        <button
                                                            type="button"
                                                            class="btn btn-outline-warning"
                                                            :disabled="
                                                                isLoading
                                                            "
                                                            @click="
                                                                setAmount(1000)
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
                                        <div id="promptpay" class="tab-pane">
                                            <div
                                                class="d-flex flex-column align-items-center"
                                            >
                                                <div class="mb-5 text-center">
                                                    <h4>ยอดชำระรวม</h4>
                                                    <h2>{{ totalFormat }}</h2>
                                                </div>
                                                <QRCode
                                                    v-if="qrCode"
                                                    :value="qrCode"
                                                    :size="300"
                                                    level="H"
                                                />
                                            </div>
                                            <div
                                                class="d-flex justify-content-center mt-5"
                                            >
                                                <button
                                                    v-if="qrCode"
                                                    type="button"
                                                    class="btn btn-danger btn-lg"
                                                    style="width: 200px"
                                                    :disabled="isLoading"
                                                    @click="cancelQR"
                                                >
                                                    ยกเลิก
                                                </button>
                                                <button
                                                    v-else
                                                    type="button"
                                                    class="btn btn-success btn-lg"
                                                    style="width: 200px"
                                                    :disabled="isLoading"
                                                    @click="requestQRCode"
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
