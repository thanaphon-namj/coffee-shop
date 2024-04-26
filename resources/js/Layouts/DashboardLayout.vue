<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineProps<{
    title?: string;
    path: string;
}>();
</script>

<template>
    <div>
        <nav class="navbar sticky-top bg-dark">
            <div class="container-fluid">
                <Link :href="route('pos')" class="navbar-brand text-white">
                    Coffee Shop
                </Link>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <div
                    class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary"
                >
                    <div class="bg-body-tertiary" tabindex="-1">
                        <div
                            class="d-md-flex flex-column p-0 overflow-y-auto"
                            style="padding-top: 4rem !important"
                        >
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <Link
                                        :href="route('dashboard')"
                                        class="nav-link d-flex align-items-center gap-2"
                                        :class="{
                                            active: path === 'dashboard',
                                        }"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-house-door"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z"
                                            />
                                        </svg>
                                        แดชบอร์ด
                                    </Link>
                                </li>
                                <li class="nav-item">
                                    <Link
                                        :href="route('orders.index')"
                                        class="nav-link d-flex align-items-center gap-2"
                                        :class="{
                                            active: path === 'orders',
                                        }"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-file-earmark-text"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"
                                            />
                                            <path
                                                d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"
                                            />
                                        </svg>
                                        รายการขาย
                                    </Link>
                                </li>
                                <li class="nav-item">
                                    <Link
                                        :href="route('products.index')"
                                        class="nav-link d-flex align-items-center gap-2"
                                        :class="{
                                            active: path === 'products',
                                        }"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-cart2"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l1.25 5h8.22l1.25-5zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"
                                            />
                                        </svg>
                                        รายการสินค้า
                                    </Link>
                                </li>
                                <li class="nav-item">
                                    <Link
                                        :href="route('customers.index')"
                                        class="nav-link d-flex align-items-center gap-2"
                                        :class="{
                                            active: path === 'customers',
                                        }"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-people"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"
                                            />
                                        </svg>
                                        รายชื่อลูกค้า
                                    </Link>
                                </li>
                                <li class="nav-item">
                                    <Link
                                        :href="route('employees.index')"
                                        class="nav-link d-flex align-items-center gap-2"
                                        :class="{
                                            active: path === 'employees',
                                        }"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="16"
                                            height="16"
                                            fill="currentColor"
                                            class="bi bi-person-badge"
                                            viewBox="0 0 16 16"
                                        >
                                            <path
                                                d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"
                                            />
                                            <path
                                                d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z"
                                            />
                                        </svg>
                                        รายชื่อพนักงาน
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div
                        v-if="title"
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"
                    >
                        <h3>{{ title }}</h3>
                    </div>
                    <div v-else class="pt-3 pb-2"></div>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
