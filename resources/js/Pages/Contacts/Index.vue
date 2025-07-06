<template>
    <div>
        <h1>Contacts</h1>
        <button @click="route('contacts.create')" class="font-sans">
            Create Contact
        </button>
        <ul>
        <li v-for="contact in contacts.data" :key="contact.id">
            {{ contact.name }} - {{ contact.email }}
        </li>
        </ul>
        <!-- <pagination :links="contacts.links" /> -->
    </div>
</template>
<script setup>
    defineProps({ contacts: Object })
</script>