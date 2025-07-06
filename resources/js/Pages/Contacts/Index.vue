<template>
    <div>
        <h1>Contacts</h1>
        <Link :href="route('contacts.create')" class="btn">
            Create Contact
        </Link>
        <ul>
        <li v-for="contact in contacts.data" :key="contact.id">
            {{ contact.name }} - {{ contact.email }}
        </li>
        </ul>
        <!-- <pagination :links="contacts.links" /> -->
    </div>
</template>
<script setup>
    import { Link } from '@inertiajs/vue3'
    defineProps({ contacts: Object })
</script>