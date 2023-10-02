<script setup>

import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div class="mx-auto" style="max-width: 400px; position: relative; margin-top: 50vh; transform: translateY(-50%);">
        <div class="mb-5 text-center">
            <Link href="/">
                <ApplicationLogo style="width: 100px; height: 100px; fill: red" />
            </Link>
        </div>

        <div class="card">
            <div class="card-body">
                <slot />
            </div>
        </div>
    </div>
</template>
