<template>
    <div class="page-container">
        <div class="page-wrapper">
            <!-- Header -->
            <div class="card section-spacing">
                <div class="card-header">
                    <h1 class="card-title">Trash</h1>
                    <p class="card-subtitle">
                        Deleted contacts - you can restore or permanently delete them
                    </p>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="card section-spacing">
                <div class="card-body card-actions">
                    <Link :href="route('contacts.index')" 
                          class="btn btn-primary btn-with-icon">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Contacts
                    </Link>
                    
                    <div class="card-actions-right">
                        {{ contacts.data?.length || 0 }} contacts in trash
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <div v-if="flash?.success" class="alert alert-success section-spacing">
                <div class="alert-content">
                    <svg class="alert-icon alert-icon-success" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="alert-message">
                        <p class="alert-text-success">{{ flash.success }}</p>
                    </div>
                </div>
            </div>

            <div v-if="flash?.error" class="alert alert-error section-spacing">
                <div class="alert-content">
                    <svg class="alert-icon alert-icon-error" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="alert-message">
                        <p class="alert-text-error">{{ flash.error }}</p>
                    </div>
                </div>
            </div>

            <!-- Filter Component -->
            <ContactFilter 
                :search="search || ''"
                :sort-by="sort || 'name'"
                :sort-direction="direction || 'asc'"
                placeholder="Search contacts by name, email, or phone..."
                route-name="contacts.trash"
            />

            <!-- Trashed Contacts List -->
            <div v-if="contacts.data && contacts.data.length > 0" class="contact-list">
                <div class="divide-y divide-gray-200">
                    <div v-for="contact in contacts.data" 
                         :key="contact.id" 
                         class="contact-list-item-trashed">
                        <div class="contact-list-content">
                            <div class="contact-list-info">
                                <div class="contact-list-details">
                                    <div class="contact-avatar">
                                        <div class="contact-avatar-circle-trashed">
                                            <span class="contact-avatar-text-trashed">
                                                {{ contact.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="contact-info">
                                        <div class="contact-name">
                                            {{ contact.name }}
                                        </div>
                                        <div class="contact-email">
                                            {{ contact.email }}
                                        </div>
                                        <div v-if="contact.phone" class="contact-phone">
                                            {{ formatPhone(contact.phone) }}
                                        </div>
                                        <div class="contact-deleted-date">
                                            Deleted: {{ formatDate(contact.deleted_at) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="contact-actions">
                                <button @click="restoreContact(contact.id)" 
                                        class="btn btn-success btn-sm btn-with-icon">
                                    <svg class="btn-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Restore
                                </button>
                                
                                <button @click="permanentlyDeleteContact(contact.id)" 
                                        class="btn btn-danger btn-sm btn-with-icon">
                                    <svg class="btn-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Forever
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="contacts.links && contacts.links.length > 3" class="pagination-container">
                    <div class="pagination-wrapper">
                        <div class="pagination-mobile">
                            <Link v-if="contacts.prev_page_url" 
                                  :href="contacts.prev_page_url" 
                                  class="pagination-link-mobile">
                                Previous
                            </Link>
                            <Link v-if="contacts.next_page_url" 
                                  :href="contacts.next_page_url" 
                                  class="pagination-link-mobile ml-3">
                                Next
                            </Link>
                        </div>
                        <div class="pagination-desktop">
                            <div>
                                <p class="pagination-info">
                                    Showing <span class="font-medium">{{ contacts.from || 0 }}</span> to <span class="font-medium">{{ contacts.to || 0 }}</span> of <span class="font-medium">{{ contacts.total || 0 }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="pagination-nav">
                                    <template v-for="link in contacts.links" :key="link.label">
                                        <Link v-if="link.url && !link.active" 
                                              :href="link.url" 
                                              class="pagination-link">
                                            <span v-if="link.label.includes('Previous')" class="sr-only">Previous</span>
                                            <span v-else-if="link.label.includes('Next')" class="sr-only">Next</span>
                                            <span v-else>{{ link.label }}</span>
                                            <svg v-if="link.label.includes('Previous')" class="pagination-arrow" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg v-if="link.label.includes('Next')" class="pagination-arrow" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </Link>
                                        <span v-else-if="link.active" 
                                              class="pagination-link-active">
                                            {{ link.label }}
                                        </span>
                                        <span v-else 
                                              class="pagination-link-disabled">
                                            <span v-if="link.label.includes('Previous')" class="sr-only">Previous</span>
                                            <span v-else-if="link.label.includes('Next')" class="sr-only">Next</span>
                                            <span v-else>{{ link.label }}</span>
                                            <svg v-if="link.label.includes('Previous')" class="pagination-arrow" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg v-if="link.label.includes('Next')" class="pagination-arrow" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                    </template>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="empty-state">
                <div class="empty-state-content">
                    <svg class="empty-state-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <h3 class="empty-state-title">No deleted contacts</h3>
                    <p class="empty-state-description">The trash is empty.</p>
                    <div class="empty-state-actions">
                        <Link :href="route('contacts.index')" 
                              class="btn btn-primary btn-with-icon">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Contacts
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import ContactFilter from '@/Components/ContactFilter.vue'

defineProps({ 
    contacts: Object,
    search: String,
    sort: String,
    direction: String,
    flash: Object
})

// Format phone number to Brazilian format
const formatPhone = (phone) => {
    if (!phone) return ''
    
    // Remove all non-digits
    const cleaned = phone.replace(/\D/g, '')
    
    // Format based on length
    if (cleaned.length === 11) {
        // Mobile: (11) 99999-9999
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 7)}-${cleaned.slice(7)}`
    } else if (cleaned.length === 10) {
        // Landline: (11) 9999-9999
        return `(${cleaned.slice(0, 2)}) ${cleaned.slice(2, 6)}-${cleaned.slice(6)}`
    }
    
    return phone
}

// Format date to Brazilian format
const formatDate = (dateString) => {
    if (!dateString) return ''
    
    const date = new Date(dateString)
    return date.toLocaleDateString('pt-BR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Restore contact
const restoreContact = (contactId) => {
    if (confirm('Are you sure you want to restore this contact?')) {
        router.patch(route('contacts.restore', contactId), {}, {
            onSuccess: () => {
                // Success message will be handled by flash messages
            },
            onError: () => {
                alert('Error restoring contact. Please try again.')
            }
        })
    }
}

// Permanently delete contact
const permanentlyDeleteContact = (contactId) => {
    if (confirm('Are you sure you want to permanently delete this contact? This action cannot be undone.')) {
        router.delete(route('contacts.force-delete', contactId), {
            onSuccess: () => {
                // Success message will be handled by flash messages
            },
            onError: () => {
                alert('Error permanently deleting contact. Please try again.')
            }
        })
    }
}
</script>

<style scoped>
@import '../../../css/contacts.css';
</style>
