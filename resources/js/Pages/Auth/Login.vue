<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout style="max-width: 300px">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 alert alert-primary">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <TextInput
                id="email"
                label="Email"
                class="mb-3"
                v-model="form.email"
                :error="form.errors.email"
                autofocus
                required
                autocomplete="username"
            />

            <TextInput
                id="password"
                label="Password"
                class="mb-3"
                v-model="form.password"
                :error="form.errors.password"
                required
                type="password"
                autocomplete="current-password"
            />


            <div class="d-flex align-items-baseline justify-content-between">
                <Checkbox id="remember" class="mb-3" v-model:checked="form.remember" label="Remember me" />

                <PrimaryButton class="ms-3" :disabled="form.processing">
                    Log in
                </PrimaryButton>
            </div>

            <div>
                <Link v-if="canResetPassword" :href="route('register')">
                    Are you not registered?
                </Link>
            </div>

            <div>
                <Link v-if="canResetPassword" :href="route('password.request')">
                    Forgot your password?
                </Link>
            </div>

        </form>
    </GuestLayout>
</template>
