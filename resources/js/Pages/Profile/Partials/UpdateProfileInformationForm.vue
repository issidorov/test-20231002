<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h4>Profile Information</h4>
            <p>Update your account's profile information and email address.</p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))">
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <div class="mt-2 alert alert-primary text-center">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="btn btn-secondary"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </div>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 alert alert-success text-center"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="d-flex align-items-baseline">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
                <div v-if="form.recentlySuccessful" class="ms-3 text-success">Saved.</div>
            </div>
        </form>
    </section>
</template>
