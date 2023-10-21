<template>
    <BModal v-model="show" :title="title">
        <form @submit.prevent="onSave()" v-if="form">
            <slot :form="form" />
        </form>

        <template #footer>
            <div class="d-flex justify-content-end w-100">
                <div class="me-auto">
                    <BButton variant="danger" @click="onDelete()" v-if="!isNew">Удалить</BButton>
                </div>

                <div class="d-flex gap-2">
                    <BButton variant="secondary" @click="onCancel()">Отменить</BButton>

                    <BButton variant="primary" @click="onSave()" :disabled="saveProcessing">
                        {{ isNew ? 'Добавить' : 'Изменить' }}
                    </BButton>
                </div>
            </div>
        </template>
    </BModal>

    <DeleteConfirm v-model:show="deleteConfirm" :url-on-delete="deleteUrl" yes-title="Удалить" @deleted="show=false">
        <slot name="deleteConfirm" :form="form" v-if="form" />
    </DeleteConfirm>
</template>

<script setup>
import DeleteConfirm from "@/Components/DeleteConfirm.vue";
import { BModal, BButton } from "bootstrap-vue-next";
import {ref} from "vue";
import {router, useForm} from '@inertiajs/vue3';

const isNew = ref(true);
const title = ref('');
const afterSaveRedirectToUrl = ref('');
const saveUrl = ref('');
const deleteUrl = ref('');

const show = ref(false);
const saveProcessing = ref(false);
const deleteConfirm = ref(false);
const form = ref(null);

defineExpose({
    show(params) {
        isNew.value = params.isNew;
        title.value = params.title;
        afterSaveRedirectToUrl.value = params.afterSaveRedirectToUrl;
        saveUrl.value = params.saveUrl;
        deleteUrl.value = params.deleteUrl;
        form.value = useForm(params.initFormParams);
        show.value = true;
    }
});

function onSave() {
    saveProcessing.value = true;
    form.value.post(saveUrl.value, {
        onSuccess() {
            show.value = false;
            if (afterSaveRedirectToUrl.value) {
                router.visit(afterSaveRedirectToUrl.value);
            }
        },
        onFinish() {
            saveProcessing.value = false;
        },
    });
}

function onCancel() {
    show.value = false;
}

function onDelete() {
    deleteConfirm.value = true;
}

</script>
