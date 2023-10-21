<template>
    <BModal :model-value="show" @update:model-value="$emit('update:show', $event.target?.value || false)">
        <slot>
            <p>Вы действительно хотите выполнить удаление?</p>
            <p>Восстановить данные после удаления невозможно.</p>
        </slot>

        <template #footer>
            <div class="d-flex gap-2">
                <BButton variant="secondary" @click="cancel()">Отмена</BButton>
                <BButton variant="danger" @click="remove()" :disabled="processing">{{ yesTitle }}</BButton>
            </div>
        </template>
    </BModal>
</template>

<script setup>
import { BModal, BButton } from "bootstrap-vue-next";
import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    urlOnDelete: {
        required: false,
    },
    show: {
        required: true,
        type: Boolean,
    },
    yesTitle: {
        type: String,
        defaults: 'Удалить',
    }
})

const emit = defineEmits(['update:show', 'deleted'])

const processing = ref(false);

function cancel() {
    emit('update:show', false);
}

function remove() {
    processing.value = true;
    router.delete(props.urlOnDelete, {
        onSuccess() {
            emit('update:show', false);
            emit('deleted');
        },
        onFinish() {
            processing.value = false;
        }
    })
}
</script>
