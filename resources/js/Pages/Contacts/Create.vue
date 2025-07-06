<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ isEditing ? 'Edit Contact' : 'Create New Contact' }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ isEditing ? 'Update contact information' : 'Add a new contact to your list' }}
                    </p>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="form.wasSuccessful" class="mb-6">
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                Contact {{ isEditing ? 'updated' : 'created' }} successfully!
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <div v-if="filteredErrors && Object.keys(filteredErrors).length > 0" class="mb-6">
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                There were errors with your submission:
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li v-for="(error, field) in filteredErrors" :key="field">
                                        <span class="font-medium">{{ field.charAt(0).toUpperCase() + field.slice(1) }}:</span>
                                        {{ Array.isArray(error) ? error[0] : error }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generic Error Message -->
            <div v-if="genericError || errors?.message" class="mb-6">
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                An error occurred
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>{{ genericError || errors?.message }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-6">
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                    id="name"
                                    type="text"
                                    v-model="form.name"
                                    :class="[
                                        'block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm',
                                        errors.name ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
                                    ]"
                                    placeholder="Enter full name"
                                    required
                                />
                                <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                                    {{ Array.isArray(errors.name) ? errors.name[0] : errors.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    :class="[
                                        'block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm',
                                        errors.email ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
                                    ]"
                                    placeholder="Enter email address"
                                    required
                                />
                                <p v-if="errors.email" class="mt-2 text-sm text-red-600">
                                    {{ Array.isArray(errors.email) ? errors.email[0] : errors.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                    id="phone"
                                    type="tel"
                                    v-model="form.phone"
                                    :class="[
                                        'block w-full px-3 py-2 border rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm',
                                        errors.phone ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500' : 'border-gray-300'
                                    ]"
                                    placeholder="(11) 99999-9999"
                                    @input="formatPhoneInput"
                                    required
                                />
                                <p class="mt-1 text-xs text-gray-500">
                                    Format: (11) 99999-9999 or (11) 9999-9999
                                </p>
                                <p v-if="errors.phone" class="mt-2 text-sm text-red-600">
                                    {{ Array.isArray(errors.phone) ? errors.phone[0] : errors.phone }}
                                </p>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <Link :href="route('contacts.index')" 
                                  class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Contacts
                            </Link>
                            
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:bg-gray-400 cursor-pointer disabled:cursor-not-allowed transition-colors duration-200">
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="isEditing" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ form.processing ? (isEditing ? 'Updating...' : 'Creating...') : (isEditing ? 'Update Contact' : 'Create Contact') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { computed, ref } from "vue";

const props = defineProps({ 
    errors: Object,
    contact: {
        type: Object,
        default: null
    }
});

// Check if we're in edit mode
const isEditing = computed(() => !!props.contact);

// Generic error message state
const genericError = ref('');

// Filter out generic message from field-specific errors
const filteredErrors = computed(() => {
    if (!props.errors) return {};
    
    const filtered = { ...props.errors };
    delete filtered.message;
    
    return filtered;
});

// Format phone number for display
const formatPhoneDisplay = (phone) => {
    if (!phone) return '';
    
    // Remove all non-digits
    const cleaned = phone.replace(/\D/g, '');
    
    // Format based on length
    if (cleaned.length === 11) {
        // Mobile: (11) 99999-9999
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 7)}-${cleaned.slice(7)}`;
    } else if (cleaned.length === 10) {
        // Landline: (11) 9999-9999
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 6)}-${cleaned.slice(6)}`;
    }
    
    return phone;
};

// Initialize form with existing contact data or empty values
const form = useForm({
    name: props.contact?.name || '',
    phone: formatPhoneDisplay(props.contact?.phone) || '',
    email: props.contact?.email || '',
});

// Format phone number as user types
const formatPhoneInput = (event) => {
    let value = event.target.value.replace(/\D/g, ''); // Remove all non-digits
    
    if (value.length <= 2) {
        value = value.replace(/(\d{0,2})/, '($1');
    } else if (value.length <= 7) {
        value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
    } else if (value.length <= 10) {
        value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
    } else {
        value = value.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
    }
    
    form.phone = value;
};

// Submit form - handle both create and update
const submitForm = () => {
    genericError.value = '';
    
    if (isEditing.value) {
        form.put(route('contacts.update', props.contact.id), {
            onError: (errors) => {
                genericError.value = 'An unexpected error occurred while updating the contact. Please try again.';
                if (errors && errors.message) {
                    // If there's a generic error message
                    genericError.value = errors.message;
                }
            }
        });
    } else {
        form.post(route('contacts.store'), {
            onError: (errors) => {
                genericError.value = 'An unexpected error occurred while creating the contact. Please try again.';
                if (errors && errors.message) {
                    // If there's a generic error message
                    genericError.value = errors.message;
                }
            }
        });
    }
};
</script>