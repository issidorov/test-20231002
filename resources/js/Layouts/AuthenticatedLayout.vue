<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import { Link, router } from '@inertiajs/vue3';
import {BDropdown, BDropdownItemButton} from "bootstrap-vue-next";
import {ref} from "vue";

const userDropdownShow = ref(false);

</script>

<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <Link :href="route('dashboard')" class="navbar-brand">
                <ApplicationLogo/>
            </Link>

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        Dashboard
                    </NavLink>
                </li>
            </ul>
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <BDropdown v-model="userDropdownShow" :text="$page.props.auth.user.name" variant="light">
                        <BDropdownItemButton @click="router.visit(route('profile.edit'))">Profile</BDropdownItemButton>
                        <BDropdownItemButton @click="router.visit(route('logout'), {method: 'post'})">Log Out</BDropdownItemButton>
                    </BDropdown>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Page Heading -->
    <header v-if="$slots.header">
        <div class="container my-4">
            <slot name="header" />
        </div>
    </header>

    <!-- Page Content -->
    <main>
        <div class="container">
            <slot />
        </div>
    </main>
</template>
