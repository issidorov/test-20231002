<template>
    <EditModal v-model:show="show" :is-new="isNew" :title="title"
               @init="resetForm()" @save="onSave" @delete="onDelete">

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

        <template #deleteConfirm>
            <p>Вы действительно хотите удалить клиента {{ form.name }}?</p>
            <p>Восстановить данные после удаления невозможно.</p>
        </template>
    </EditModal>
</template>

<script setup>
import EditModal from "@/Components/EditModal/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import {router, useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";

defineExpose({showAsNew, showAsEdit});


const show = ref(false);
const client = ref(null);
const form = ref(null);
const processing = ref(false);

const isNew = computed(() => !client.value);
const title = computed(() => isNew.value ? 'Добавление клиента' : 'Редактирование клиента');

function showAsNew() {
    resetForm();
    client.value = null;
    show.value = true;
}

function showAsEdit(editableClient) {
    resetForm(editableClient);
    client.value = editableClient;
    show.value = true;
}

function resetForm(client = null) {
    form.value = useForm({
        name: client?.name || '',
        address: client?.address || '',
        email: client?.email || '',
    });
}

function onSave({onFinish}) {
    const url = isNew.value ? route('client.new') : route('client.update', {id: client.value.id});
    form.value.post(url, {
        onSuccess() {
            show.value = false;
            if (isNew.value) {
                router.visit(route('clients'));
            }
        },
        onFinish,
    });
}

function onDelete() {
    const url = route('client.delete', {id: client.value.id});
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