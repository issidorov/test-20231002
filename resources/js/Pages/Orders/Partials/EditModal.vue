<template>
    <BModal
        :model-value="show"
        :title="isNew ? 'Создание заказа' : 'Редактирование заказа'"
        @update:model-value="$emit('update:show', $event.target?.value || false)"
    >
        <form @submit.prevent="submit">
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
        </form>

        <template #footer>
            <div class="d-flex justify-content-end w-100">
                <div class="me-auto">
                    <BButton variant="danger" @click="remove()" v-if="!isNew">Удалить</BButton>
                </div>

                <div class="d-flex gap-2">
                    <BButton variant="secondary" @click="cancel()">Отменить</BButton>

                    <BButton variant="primary" @click="submit()" :disabled="processing">
                        {{ isNew ? 'Добавить' : 'Изменить' }}
                    </BButton>
                </div>
            </div>
        </template>
    </BModal>

    <DeleteConfirm v-model:show="deleteConfirm" :url-on-delete="urlOnDelete" yes-title="Удалить клиента">
        <p>
            Вы действительно хотите удалить заказ номер "{{ order?.number }}" от {{ order?.date }}
            клиента {{ order?.client.name }}?
        </p>
        <p>Восстановить данные после удаления невозможно.</p>
    </DeleteConfirm>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import DeleteConfirm from "@/Components/DeleteConfirm.vue";
import { BModal, BButton, BFormGroup, BFormSelect } from "bootstrap-vue-next";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    id: {
        type: [Number, null],
        required: true,
    },
    form: {
        type: Object,
        required: true,
    },
    order: {
        required: true,
    },
    clients: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['update:show', 'update:form']);

const processing = ref(false);

const deleteConfirm = ref(false);
const urlOnDelete = computed(() => props.id ? route('order.delete', {id: props.id}) : null);
const isNew = computed(() => !props.id);

const clientsList = computed(()  => {
    const clientIds = [];
    const res = props.clients.map((client) => {
        clientIds.push(client.id);
        return {value: client.id, text: getClientText(client)};
    });
    if (props.order?.client?.id && !clientIds.includes(props.order.client.id)) {
        // If exists client is soft-deleted
        res.unshift({value: props.order.client.id, text: getClientText(props.order.client)})
    }
    return res;
});

function getClientText(client) {
    return `${client.name} (${client.email})`;
}

function submit() {
    processing.value = true;
    const url = isNew.value ? route('order.new') : route('order.update', {id: props.id});
    props.form.post(url, {
        onSuccess() {
            emit('update:show', false);
            emit('update:form', useForm({
                number: '',
                date: '',
                cost: '',
                client_id: '',
            }));
            if (isNew.value) {
                router.visit(route('orders'));
            }
        },
        onFinish() {
            processing.value = false;
        },
    });
}

function cancel() {
    emit('update:show', false);
}

function remove() {
    deleteConfirm.value = true;
}

</script>