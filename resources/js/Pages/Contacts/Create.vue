<template>
    <div class="page-container">
        <div class="page-wrapper">
            <!-- Header -->
            <div class="card section-spacing">
                <div class="card-header">
                    <h1 class="card-title">{{ pageTitle }}</h1>
                    <p class="card-subtitle">{{ pageSubtitle }}</p>
                </div>
            </div>

            <!-- Success Message -->
            <div v-if="form.wasSuccessful" class="alert alert-success section-spacing">
                <div class="alert-content">
                    <svg class="alert-icon alert-icon-success" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="alert-message">
                        <p class="alert-text-success">{{ successMessage }}</p>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            <div v-if="hasFieldErrors" class="alert alert-error section-spacing">
                <div class="alert-content">
                    <svg class="alert-icon alert-icon-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="alert-message">
                        <h3 class="alert-title">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside space-y-1 mt-2">
                            <li v-for="(error, field) in fieldErrors" :key="field">
                                <span class="font-medium">{{ formatFieldName(field) }}:</span>
                                {{ getErrorMessage(error) }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Generic Error Message -->
            <div v-if="genericError" class="alert alert-error section-spacing">
                <div class="alert-content">
                    <svg class="alert-icon alert-icon-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="alert-message">
                        <p class="alert-text-error">{{ genericError }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="card">
                <div class="card-body">
                    <form @submit.prevent="submitForm" class="space-y-6">
                        <!-- Name Field -->
                        <div class="contact-form-group">
                            <label for="name" class="contact-form-label">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                type="text"
                                v-model="form.name"
                                :class="getInputClasses('name')"
                                placeholder="Enter full name"
                                required
                            />
                            <p v-if="errors.name" class="contact-form-error">
                                {{ getErrorMessage(errors.name) }}
                            </p>
                        </div>

                        <!-- Email Field -->
                        <div class="contact-form-group">
                            <label for="email" class="contact-form-label">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                :class="getInputClasses('email')"
                                placeholder="Enter email address"
                                required
                            />
                            <p v-if="errors.email" class="contact-form-error">
                                {{ getErrorMessage(errors.email) }}
                            </p>
                        </div>

                        <!-- Phone Field -->
                        <div class="contact-form-group">
                            <label for="phone" class="contact-form-label">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="phone"
                                type="tel"
                                v-model="form.phone"
                                :class="getInputClasses('phone')"
                                placeholder="(11) 99999-9999"
                                @input="formatPhoneInput"
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500">
                                Format: (11) 99999-9999 or (11) 9999-9999
                            </p>
                            <p v-if="errors.phone" class="contact-form-error">
                                {{ getErrorMessage(errors.phone) }}
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <Link :href="route('contacts.index')" class="btn btn-secondary btn-with-icon">
                                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Contacts
                            </Link>
                            
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                :class="submitButtonClasses"
                            >
                                <svg v-if="form.processing" class="btn-icon animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg v-else class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path v-if="isEditing" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ submitButtonText }}
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

// Props
const props = defineProps({ 
    errors: {
        type: Object,
        default: () => ({})
    },
    contact: {
        type: Object,
        default: null
    }
});

// State
const genericError = ref('');

// Computed properties
const isEditing = computed(() => !!props.contact);

const pageTitle = computed(() => 
    isEditing.value ? 'Edit Contact' : 'Create New Contact'
);

const pageSubtitle = computed(() => 
    isEditing.value ? 'Update contact information' : 'Add a new contact to your list'
);

const successMessage = computed(() => 
    `Contact ${isEditing.value ? 'updated' : 'created'} successfully!`
);

const fieldErrors = computed(() => {
    const filtered = { ...props.errors };
    delete filtered.message;
    return filtered;
});

const hasFieldErrors = computed(() => 
    Object.keys(fieldErrors.value).length > 0
);

const submitButtonText = computed(() => {
    if (form.processing) {
        return isEditing.value ? 'Updating...' : 'Creating...';
    }
    return isEditing.value ? 'Update Contact' : 'Create Contact';
});

const submitButtonClasses = computed(() => [
    'btn btn-primary btn-with-icon',
    form.processing ? 'btn-disabled' : ''
]);

// Form setup
const form = useForm({
    name: props.contact?.name || '',
    phone: formatPhoneDisplay(props.contact?.phone) || '',
    email: props.contact?.email || '',
});

// Utility functions
function formatPhoneDisplay(phone) {
    if (!phone) return '';
    
    const cleaned = phone.replace(/\D/g, '');
    
    if (cleaned.length === 11) {
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 7)}-${cleaned.slice(7)}`;
    } else if (cleaned.length === 10) {
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 6)}-${cleaned.slice(6)}`;
    }
    
    return phone;
}

function formatFieldName(field) {
    return field.charAt(0).toUpperCase() + field.slice(1);
}

function getErrorMessage(error) {
    return Array.isArray(error) ? error[0] : error;
}

function getInputClasses(field) {
    const baseClasses = 'contact-form-input';
    const errorClasses = 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500';
    
    return props.errors[field] ? `${baseClasses} ${errorClasses}` : baseClasses;
}

// Event handlers
function formatPhoneInput(event) {
    let value = event.target.value.replace(/\D/g, '');
    
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
}

function submitForm() {
    genericError.value = '';
    
    const options = {
        onError: (errors) => {
            genericError.value = errors.message || 
                `An unexpected error occurred while ${isEditing.value ? 'updating' : 'creating'} the contact. Please try again.`;
        }
    };
    
    if (isEditing.value) {
        form.put(route('contacts.update', props.contact.id), options);
    } else {
        form.post(route('contacts.store'), options);
    }
}
</script>

<style scoped>
@import '../../../css/contacts.css';
</style>