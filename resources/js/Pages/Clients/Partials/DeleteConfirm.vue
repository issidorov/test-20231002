<template>
    <BModal :model-value="show" @update:model-value="$emit('update:show', $event.target?.value || false)">
        <p>Вы действительно хотите удалить клиента {{ name }}?</p>
        <p>Восстановить данные после удаления невозможно.</p>

        <template #footer>
            <div class="d-flex gap-2">
                <BButton variant="secondary" @click="cancel()">Отмена</BButton>
                <BButton variant="danger" @click="remove()" :disabled="processing">Удалить клиента</BButton>
            </div>
        </template>
    </BModal>
</template>

<script setup>
import { BModal, BButton } from "bootstrap-vue-next";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    name: {
        required: true,
        type: String,
    },
    urlOnDelete: {
        required: true,
    },
    show: {
        required: true,
        type: Boolean,
    },
})

const emit = defineEmits(['update:show'])

const processing = ref(false);

function cancel() {
    emit('update:show', false);
}

function remove() {
    processing.value = true;
    router.visit(props.urlOnDelete, {
        method: 'delete',
        onSuccess() {
            emit('update:show', false);
        },
        onFinish() {
            processing.value = false;
        },
    })
}
</script>
