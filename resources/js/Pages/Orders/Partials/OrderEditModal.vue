<template>
    <EditModal ref="modal">
        <template #default="{ form }: { form: InertiaForm<Form> }">
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
        </template>

        <template #deleteConfirm v-if="editableOrder">
            <p>
                Вы действительно хотите удалить заказ номер "{{ editableOrder.number }}" от {{ editableOrder.date }}
                клиента {{ editableOrder.client.name }}?
            </p>
            <p>Восстановить данные после удаления невозможно.</p>
        </template>
    </EditModal>
</template>

<script setup lang="ts">
import EditModal from "@/Components/EditModal/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import { BFormGroup, BFormSelect } from "bootstrap-vue-next";
import { InertiaForm } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import { PropType } from "vue";

interface Form {
    number: string;
    date: string;
    cost: string;
    client_id: string;
}

const props = defineProps({
    clients: {
        required: true,
        type: Array as PropType<any[]>,
    }
})

const modal = ref(null);
const editableOrder = ref(null);

const clientsList = computed(()  => {
    const clientIds = [];
    const res = props.clients.map((client) => {
        clientIds.push(client.id);
        return {value: client.id, text: getClientText(client)};
    });
    if (editableOrder.value?.client?.id && !clientIds.includes(editableOrder.value.client.id)) {
        // If exists client is soft-deleted
        res.unshift({value: editableOrder.value.client.id, text: getClientText(editableOrder.value.client)})
    }
    return res;
});

function getClientText(client) {
    return `${client.name} (${client.email})`;
}

defineExpose({
    showAsNew() {
        editableOrder.value = null;
        modal.value.show({
            isNew: true,
            title: 'Создание заказа',
            initFormParams: _createFormParams(null),
            afterSaveRedirectToUrl: route('orders'),
            saveUrl: route('order.new'),
            deleteUrl: null,
        });
    },
    showAsEdit(order) {
        editableOrder.value = order;
        modal.value.show({
            isNew: false,
            title: 'Редактирование заказа',
            initFormParams: _createFormParams(order),
            afterSaveRedirectToUrl: null,
            saveUrl: route('order.update', {id: order.id}),
            deleteUrl: route('order.delete', {id: order.id}),
        });
    },
});

function _createFormParams(order): Form {
    return {
        number: order?.number || '',
        date: order?.date || '',
        cost: order?.cost || '',
        client_id: order?.client?.id || '',
    }
}

</script>
