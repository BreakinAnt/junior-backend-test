<template>
    <div>
        <h1>Contacts</h1>
        <ul>
        <li v-for="contact in contacts.data" :key="contact.id">
            {{ contact.name }} - {{ contact.email }}
        </li>
        </ul>
        <pagination :links="contacts.links" />
    </div>
</template>
<script>
    defineProps({ contacts: Object })
</script>