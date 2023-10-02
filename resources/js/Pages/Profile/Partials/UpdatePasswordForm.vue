<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h4>Update Password</h4>
            <p>Ensure your account is using a long, random password to stay secure.</p>
        </header>

        <form @submit.prevent="updatePassword">
            <TextInput
                id="current_password"
                label="Current Password"
                ref="currentPasswordInput"
                v-model="form.current_password"
                :error="form.errors.current_password"
                type="password"
                class="mb-3"
                autocomplete="current-password"
            />

            <TextInput
                id="password"
                label="New Password"
                ref="passwordInput"
                v-model="form.password"
                :error="form.errors.password"
                type="password"
                class="mb-3"
                autocomplete="new-password"
            />

            <TextInput
                id="password_confirmation"
                label="Confirm Password"
                v-model="form.password_confirmation"
                :error="form.errors.password_confirmation"
                type="password"
                class="mb-3"
                autocomplete="new-password"
            />

            <div class="d-flex align-items-baseline">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <div v-if="form.recentlySuccessful" class="ms-3 text-success">Saved.</div>
            </div>
        </form>
    </section>
</template>
