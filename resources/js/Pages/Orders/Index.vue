<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import NoItemsText from "@/Components/NoItemsText.vue";
import {onMounted, ref} from "vue";
import { BButton } from "bootstrap-vue-next";
import Pagination from "@/Components/Pagination.vue";
import OrderEditModal from "@/Pages/Orders/Partials/OrderEditModal.vue";

const props = defineProps({
    orders: Object,
    clients: Array,
})

/** @type OrderEditModal */
const orderEditModal = ref(null);

onMounted(() => {
    if (props.orders.data.length === 0 && props.orders.current_page > 1) {
        router.visit(props.orders.last_page_url);
    }
})

</script>

<template>
    <Head title="Заказы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2>Заказы</h2>
                <BButton @click="orderEditModal.showAsNew()" variant="primary">Создать заказ</BButton>
            </div>
        </template>

        <div v-if="orders.data.length > 0">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Номер</th>
                    <th>Дата</th>
                    <th>Стоимость</th>
                    <th>Клиент</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="order in orders.data" :key="order.id" @click="orderEditModal.showAsEdit(order)">
                    <td>{{ order.number }}</td>
                    <td>{{ order.date }}</td>
                    <td>{{ order.cost }}</td>
                    <td>{{ order.client.name }} ({{ order.client.email }})</td>
                </tr>
                </tbody>
            </table>

            <Pagination :links="orders.links" v-if="orders.last_page > 1"/>
        </div>
        <div v-else>
            <NoItemsText />
        </div>
    </AuthenticatedLayout>

    <OrderEditModal ref="orderEditModal" :clients="clients" />
</template>
