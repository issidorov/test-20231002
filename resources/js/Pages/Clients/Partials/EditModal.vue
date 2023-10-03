<template>
    <BModal
        :model-value="show"
        :title="isNew ? 'Добавление клиента' : 'Редактирование клиента'"
        @update:model-value="$emit('update:show', $event.target?.value || false)"
        v-if="form"
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

    <DeleteConfirm v-model:show="deleteConfirm" :url-on-delete="urlOnDelete" yes-title="Удалить клиента" v-if="form">
        <p>Вы действительно хотите удалить клиента {{ form.name }}?</p>
        <p>Восстановить данные после удаления невозможно.</p>
    </DeleteConfirm>
</template>

<script setup>
import TextInput from "@/Components/TextInput.vue";
import DeleteConfirm from "@/Components/DeleteConfirm.vue";
import { BModal, BButton } from "bootstrap-vue-next";
import {router, useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref, watch} from "vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    id: {
        type: [Number, null],
        required: true,
    },
    client: {
        required: true,
    }
});

const emit = defineEmits(['update:show', 'update:form']);

watch(() => props.show, (newValue) => {
    if (newValue === true) {
        if (props.client) {
            fill(props.client);
        }
    } else {
        reset();
    }
})

onMounted(() => {
    reset();
})

const processing = ref(false);

const deleteConfirm = ref(false);
const urlOnDelete = computed(() => props.id ? route('client.delete', {id: props.id}) : null);
const isNew = computed(() => !props.id);

const form = ref(null);

function reset() {
    form.value = useForm({
        name: '',
        address: '',
        email: '',
    });
}

function fill(client) {
    form.value = useForm({
        name: client.name,
        address: client.address,
        email: client.email,
    });
}

function submit() {
    processing.value = true;
    const url = isNew.value ? route('client.new') : route('client.update', {id: props.id});
    form.value.post(url, {
        onSuccess() {
            emit('update:show', false);
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

</script>