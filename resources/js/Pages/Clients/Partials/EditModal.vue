<template>
    <BModal
        :model-value="show"
        :title="isNew ? 'Добавление клиента' : 'Редактирование клиента'"
        @update:model-value="$emit('update:show', $event.target?.value || false)"
    >
        <form @submit.prevent="submit">
            <TextInput
                id="name"
                :error="form.errors.name"
                label="Имя"
                v-model="form.name"
                class="mb-3"
            />

            <TextInput
                id="address"
                :error="form.errors.address"
                label="Адрес"
                v-model="form.address"
                class="mb-3"
            />

            <TextInput
                id="email"
                :error="form.errors.email"
                label="Email"
                v-model="form.email"
                class="mb-3"
            />
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

    <DeleteConfirm v-model:show="deleteConfirm" :url-on-delete="urlOnDelete" :name="form.name"/>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import { BModal, BButton } from "bootstrap-vue-next";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import DeleteConfirm from "@/Pages/Clients/Partials/DeleteConfirm.vue";

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
});

const emit = defineEmits(['update:show', 'update:form']);

const processing = ref(false);

const deleteConfirm = ref(false);
const urlOnDelete = computed(() => props.id ? route('client.delete', {id: props.id}) : null);

function submit() {
    processing.value = true;
    const url = isNew.value ? route('client.new') : route('client.update', {id: props.id});
    props.form.post(url, {
        onSuccess() {
            emit('update:show', false);
            emit('update:form', useForm({
                name: '',
                address: '',
                email: '',
            }));
            if (isNew.value) {
                router.visit(route('clients'));
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

const isNew = computed(() => !props.id);

</script>