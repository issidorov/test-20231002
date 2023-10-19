<template>
    <EditModal v-model:show="show" :is-new="isNew" :title="title"
               @init="resetForm()" @save="onSave" @delete="onDelete">

        <TextInput
            id="number"
            :error="form.errors.number"
            label="Номер"
            v-model="form.number"
            class="mb-3"
        />

        <TextInput
            id="date"
            :error="form.errors.date"
            label="Дата"
            v-model="form.date"
            type="date"
            class="mb-3"
        />

        <TextInput
            id="cost"
            :error="form.errors.cost"
            label="Стоимость"
            v-model="form.cost"
            class="mb-3"
        />

        <BFormGroup
            label="Клиент"
            label-for="client_id"
            :invalid-feedback="form.errors.client_id"
            class="mb-3"
        >
            <BFormSelect id="client_id" v-model="form.client_id" :options="clientsList" />
        </BFormGroup>

        <template #deleteConfirm>
            <p>
                Вы действительно хотите удалить заказ номер "{{ order?.number }}" от {{ order?.date }}
                клиента {{ order?.client.name }}?
            </p>
            <p>Восстановить данные после удаления невозможно.</p>
        </template>
    </EditModal>
</template>

<script setup>
import EditModal from "@/Components/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import { BFormGroup, BFormSelect } from "bootstrap-vue-next";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";

defineExpose({showAsNew, showAsEdit});

const props = defineProps({
    clients: {
        type: Array,
        required: true,
    },
});

const show = ref(false);
const order = ref(null);
const form = ref(null);
const processing = ref(false);

const isNew = computed(() => !order.value);
const title = computed(() => isNew.value ? 'Создание заказа' : 'Редактирование заказа');

const clientsList = computed(()  => {
    const clientIds = [];
    const res = props.clients.map((client) => {
        clientIds.push(client.id);
        return {value: client.id, text: getClientText(client)};
    });
    if (order.value?.client?.id && !clientIds.includes(order.value.client.id)) {
        // If exists client is soft-deleted
        res.unshift({value: order.value.client.id, text: getClientText(order.value.client)})
    }
    return res;
});

function getClientText(client) {
    return `${client.name} (${client.email})`;
}

function showAsNew() {
    resetForm();
    order.value = null;
    show.value = true;
}

function showAsEdit(editableOrder) {
    resetForm(editableOrder);
    order.value = editableOrder;
    show.value = true;
}

function resetForm(order = null) {
    form.value = useForm({
        number: order?.number || '',
        date: order?.date || '',
        cost: order?.cost || '',
        client_id: order?.client?.id || '',
    });
}

function onSave({onFinish}) {
    const url = isNew.value ? route('order.new') : route('order.update', {id: order.value.id});
    form.value.post(url, {
        onSuccess() {
            show.value = false;
            if (isNew.value) {
                router.visit(route('orders'));
            }
        },
        onFinish,
    });
}

function onDelete() {
    const url = route('order.delete', {id: order.value.id});
    router.visit(url, {
        method: 'delete',
        onSuccess() {
            show.value = false;
        },
        onFinish() {
            processing.value = false;
        },
    })
}

</script>