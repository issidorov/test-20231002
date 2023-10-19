<template>
    <BModal
        :model-value="show"
        :title="title || (isNew ? 'Добавление' : 'Редактирование')"
        @update:model-value="$emit('update:show', $event.target?.value || false)"
        @hidden="$emit('init')"
    >
        <form @submit.prevent="submit">
            <slot />
        </form>

        <template #footer>
            <div class="d-flex justify-content-end w-100">
                <div class="me-auto">
                    <BButton variant="danger" @click="showDeleteConfirm()" v-if="!isNew">Удалить</BButton>
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

    <DeleteConfirm v-model:show="deleteConfirm" @confirmed="$emit('delete')" yes-title="Удалить клиента">
        <slot name="deleteConfirm"/>
    </DeleteConfirm>
</template>

<script setup>
import DeleteConfirm from "@/Components/DeleteConfirm.vue";
import { BModal, BButton } from "bootstrap-vue-next";
import {onBeforeMount, ref} from "vue";

const props = defineProps({
    title: {
        type: String,
    },
    show: {
        type: Boolean,
        required: true,
    },
    isNew: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['update:show', 'init', 'save', 'delete']);

onBeforeMount(() => {
    emit('init');
})

const processing = ref(false);
const deleteConfirm = ref(false);

function submit() {
    processing.value = true;
    emit('save', {
        onFinish() {
            processing.value = false;
        },
    });
}

function cancel() {
    emit('update:show', false);
}

function showDeleteConfirm() {
    deleteConfirm.value = true;
}

</script>