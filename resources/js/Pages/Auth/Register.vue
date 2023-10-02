<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout style="max-width: 300px">
        <Head title="Register" />

        <form @submit.prevent="submit">
            <TextInput
                id="name"
                type="text"
                label="Name"
                class="mb-3"
                v-model="form.name"
                :error="form.errors.name"
                required
                autofocus
                autocomplete="name"
            />

            <TextInput
                id="email"
                type="email"
                label="Email"
                class="mb-3"
                v-model="form.email"
                :error="form.errors.email"
                required
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
                <Link :href="route('login')">Already registered?</Link>

                <PrimaryButton class="ms-3" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
