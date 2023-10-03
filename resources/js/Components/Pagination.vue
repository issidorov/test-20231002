<template>
    <nav>
        <ul class="pagination">
            <li class="page-item" v-for="link in links">
                <Link
                    class="page-link"
                    :class="{active: link.active}"
                    :href="link.url"
                    v-html="link.label"
                    :disabled="!link.url"
                />
            </li>
        </ul>
    </nav>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";


defineProps({
    links: {
        required: true,
        type: Array,
    }
})
</script>
