<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import NoItemsText from "@/Components/NoItemsText.vue";
import {onMounted, ref} from "vue";
import { BButton } from "bootstrap-vue-next";
import EditModal from "@/Pages/Clients/Partials/EditModal.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    clients: Object,
})

onMounted(() => {
    if (props.clients.data.length === 0 && props.clients.current_page > 1) {
        router.visit(props.clients.last_page_url);
    }
})

const modalClientShown = ref(false);
const modalClientId = ref(null);
const clientModel = ref(null);


function newClient() {
    clientModel.value = null;
    modalClientId.value = null;
    modalClientShown.value = true;
}

function editClient(client) {
    clientModel.value = client;
    modalClientId.value = client.id;
    modalClientShown.value = true;
}


</script>

<template>
    <Head title="Клиенты" />

    <AuthenticatedLayout>
        <template #header>
            <div class="d-flex justify-content-between">
                <h2>Клиенты</h2>
                <BButton @click="newClient()" variant="primary">Добавить клиента</BButton>
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
                <tr v-for="client in clients.data" :key="client.id" @click="editClient(client)">
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

    <EditModal :client="clientModel" :id="modalClientId" v-model:show="modalClientShown"/>
</template>
