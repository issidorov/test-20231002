<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <TextInput
                id="email"
                type="email"
                label="Email"
                class="mb-3"
                v-model="form.email"
                :error="form.errors.email"
                required
                autofocus
                autocomplete="username"
            />

            <TextInput
                id="password"
                type="password"
                label="Password"
                class="mb-3"
                v-model="form.password"
                :error="form.errors.password"
                required
                autocomplete="new-password"
            />

            <TextInput
                id="password_confirmation"
                type="password"
                label="Confirm Password"
                class="mb-3"
                v-model="form.password_confirmation"
                :error="form.errors.password_confirmation"
                required
                autocomplete="new-password"
            />

            <div class="d-flex align-items-baseline justify-content-between">
                <PrimaryButton :disabled="form.processing">
                    Reset Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
