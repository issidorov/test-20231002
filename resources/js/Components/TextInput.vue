<script setup>
import { onMounted, ref } from 'vue';
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        required: true,
    },
    id: {
        type: String,
        required: true,
    },
    autofocus: Boolean,
    autocomplete: String,
    required: Boolean,
    type: {
        type: String,
        defaults: 'text'
    },
    error: {
        required: true,
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (props.autofocus) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div>
        <label class="form-label" :for="id">{{ label }}</label>

        <input
            :id="id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="form-control"
            :class="{'is-invalid': !!error}"
            ref="input"
            :autocomplete="autocomplete"
            :autofocus="autofocus"
            :type="type"
            :required="required"
        >

        <InputError :message="error" />
    </div>
</template>
