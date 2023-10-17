<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import NoItemsText from "@/Components/NoItemsText.vue";
import {onMounted, ref} from "vue";
import { BButton } from "bootstrap-vue-next";
import Pagination from "@/Components/Pagination.vue";
import ClientEditModal from "@/Pages/Clients/Partials/ClientEditModal.vue";

const props = defineProps({
    clients: Object,
})

/** @type ClientEditModal */
const clientEditModal = ref(null);

onMounted(() => {
    if (props.clients.data.length === 0 && props.clients.current_page > 1) {
        router.visit(props.clients.last_page_url);
    }
})

</script>

<template>
    <Head title="Клиенты" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2>Клиенты</h2>
                <BButton @click="clientEditModal.showAsNew()" variant="primary">Добавить клиента</BButton>
            </div>
        </template>

        <div v-if="clients.data.length > 0">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Имя</th>
                    <th>Адрес</th>
                    <th>Email</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="client in clients.data" :key="client.id" @click="clientEditModal.showAsEdit(client)">
                    <td>{{ client.name }}</td>
                    <td>{{ client.address }}</td>
                    <td>{{ client.email }}</td>
                </tr>
                </tbody>
            </table>

            <Pagination :links="clients.links" v-if="clients.last_page > 1"/>
        </div>
        <div v-else>
            <NoItemsText />
        </div>
    </AuthenticatedLayout>

    <ClientEditModal ref="clientEditModal" />
</template>
