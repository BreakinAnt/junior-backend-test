<template>
    <div v-if="errors">
        <ul v-for="(error, index) in errors" :key="index">
            <li>
                <p class="text-red-500">{{ error }}</p>
            </li>
        </ul>
    </div>
    <div v-if="form.wasSuccessful">
        <p class="text-green-500">Contact created successfully!</p>
    </div>
    <div>
        <form @submit.prevent="form.post(route('contacts.store'))">
            <button type="submit" class="btn" :disabled="form.processing">Create Contact</button>
        </form>
    </div>
    <div>
        <Link :href="route('contacts.index')" class="btn">
            Back to Contacts
        </Link>
    </div>
</template>
<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";

defineProps({ errors: Object });

const form = useForm({
    name: 'test test',
    phone: '91987122338',
    email: "test@"+Math.random(1, 9999),
});

</script>