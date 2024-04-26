<script setup lang="ts">
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import axios from "axios";

const form = useForm({
    productName: "",
    description: "",
    category: "",
    price: "",
    stock: "",
    imageUrl: "",
});

const file = ref<File | null>();

const onFileChanged = (e: Event) => {
    const files = (e.target as HTMLInputElement).files;

    if (files) {
        file.value = files[0];
        upload();
    }
};

const upload = async () => {
    const formData = new FormData();

    if (file.value) {
        formData.append("file", file.value);
    }

    const res = await axios.post(route("upload.store"), formData);

    if (res.data.success === true) {
        form.imageUrl = res.data.url;
    }
};

const submit = () => {
    form.post(route("products.store"), {
        onFinish: () => {
            form.reset(
                "productName",
                "description",
                "category",
                "price",
                "stock",
                "imageUrl",
            );
        },
    });
};
</script>

<template>
    <Head title="เพิ่มสินค้า" />
    <DashboardLayout title="เพิ่มสินค้า" path="products">
        <div class="row">
            <div class="col-4">
                <form @submit.prevent="submit">
                    <div class="mb-3">
                        <label class="form-label">ชื่อสินค้า</label>
                        <input
                            type="text"
                            class="form-control"
                            v-model="form.productName"
                            required
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รายละเอียด</label>
                        <textarea
                            class="form-control"
                            rows="3"
                            v-model="form.description"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">หมวดหมู่</label>
                        <select
                            class="form-select"
                            v-model="form.category"
                            required
                        >
                            <option value="">เลือก...</option>
                            <option value="Hot Coffee">กาแฟร้อน</option>
                            <option value="Hot Tea">ชาร้อน</option>
                            <option value="Cold Coffee">กาแฟเย็น</option>
                            <option value="Iced Tea">ชาเย็น</option>
                            <option value="Frappuccino">
                                เครื่องดื่มแฟรบปูชิโน่
                            </option>
                            <option value="Dessert">ขนมและของหวาน</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ราคาขาย</label>
                        <div class="input-group mb-3">
                            <input
                                type="number"
                                class="form-control"
                                v-model="form.price"
                                required
                            />
                            <span class="input-group-text">฿</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">จำนวนคงเหลือ</label>
                        <input
                            type="number"
                            class="form-control"
                            v-model="form.stock"
                        />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">รูปภาพ</label>
                        <div v-if="form.imageUrl" class="d-grid col-3">
                            <img
                                :src="form.imageUrl"
                                class="img-thumbnail border-0"
                            />
                        </div>
                        <input
                            v-else
                            class="form-control"
                            type="file"
                            accept="image/png"
                            @change="onFileChanged"
                        />
                    </div>
                    <div class="d-flex">
                        <Link
                            :href="route('products.index')"
                            class="btn btn-danger"
                            style="width: 100px"
                        >
                            ย้อนกลับ
                        </Link>
                        <button
                            type="submit"
                            class="btn btn-primary ms-3"
                            style="width: 100px"
                            :disabled="form.processing || !form.imageUrl"
                        >
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DashboardLayout>
</template>
