<template>
    <EditModal ref="modal">
        <template #default="{ form }: { form: InertiaForm<Form> }">
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
        </template>

        <template #deleteConfirm="{ form }">
            <p>Вы действительно хотите удалить клиента {{ form.name }}?</p>
            <p>Восстановить данные после удаления невозможно.</p>
        </template>
    </EditModal>
</template>

<script setup lang="ts">
import EditModal from "@/Components/EditModal/EditModal.vue";
import TextInput from "@/Components/TextInput.vue";
import { InertiaForm } from "@inertiajs/vue3";
import { ref } from "vue";

interface Form {
    name: string;
    address: string;
    email: string;
}

const modal = ref(null);

defineExpose({
    showAsNew() {
        modal.value.show({
            isNew: true,
            title: 'Добавление клиента',
            initFormParams: _createFormParams(null),
            afterSaveRedirectToUrl: route('clients'),
            saveUrl: route('client.new'),
            deleteUrl: null,
        });
    },
    showAsEdit(client) {
        modal.value.show({
            isNew: false,
            title: 'Редактирование клиента',
            initFormParams: _createFormParams(client),
            afterSaveRedirectToUrl: null,
            saveUrl: route('client.update', {id: client.id}),
            deleteUrl: route('client.delete', {id: client.id}),
        });
    },
});

function _createFormParams(client): Form {
    return {
        name: client?.name || '',
        address: client?.address || '',
        email: client?.email || '',
    }
}

</script>
