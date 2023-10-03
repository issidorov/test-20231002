<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import NoItemsText from "@/Components/NoItemsText.vue";
import {onMounted, ref} from "vue";
import { BButton } from "bootstrap-vue-next";
import EditModal from "@/Pages/Orders/Partials/EditModal.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    orders: Object,
    clients: Array,
})

onMounted(() => {
    if (props.orders.data.length === 0 && props.orders.current_page > 1) {
        router.visit(props.orders.last_page_url);
    }
})

const modalOrderShown = ref(false);
const modalOrderId = ref(null);
const modalOrderForm = ref(useForm({
    number: '',
    date: '',
    cost: '',
    client_id: '',
}));
const modalOrderModel = ref(null);


function newOrder() {
    modalOrderForm.value = useForm({
        number: '',
        date: '',
        cost: '',
        client_id: '',
    });
    modalOrderId.value = null;
    modalOrderShown.value = true;
    modalOrderModel.value = null;
}

function editOrder(order) {
    modalOrderForm.value = useForm({
        number: order.number,
        date: order.date,
        cost: order.cost,
        client_id: order.client.id,
    });
    modalOrderId.value = order.id;
    modalOrderShown.value = true;
    modalOrderModel.value = order;
}


</script>

<template>
    <Head title="Заказы" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2>Заказы</h2>
                <BButton @click="newOrder()" variant="primary">Создать заказ</BButton>
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
                <tr v-for="order in orders.data" :key="order.id" @click="editOrder(order)">
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

    <EditModal
        v-model:form="modalOrderForm"
        :id="modalOrderId"
        v-model:show="modalOrderShown"
        :order="modalOrderModel"
        :clients="clients"
    />
</template>
