<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900">Trash</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Deleted contacts - you can restore or permanently delete them
                    </p>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 flex justify-between items-center">
                    <Link :href="route('contacts.index')" 
                          class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Contacts
                    </Link>
                    
                    <div class="text-sm text-gray-600">
                        {{ contacts.data?.length || 0 }} contacts in trash
                    </div>
                </div>
            </div>

            <!-- Trashed Contacts List -->
            <div v-if="contacts.data && contacts.data.length > 0" class="bg-white shadow rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200">
                    <div v-for="contact in contacts.data" 
                         :key="contact.id" 
                         class="px-6 py-4 bg-red-50 hover:bg-red-100 transition-colors duration-150">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <span class="text-sm font-medium text-red-600">
                                                {{ contact.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ contact.name }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ contact.email }}
                                        </div>
                                        <div v-if="contact.phone" class="text-sm text-gray-600">
                                            {{ formatPhone(contact.phone) }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Deleted: {{ formatDate(contact.deleted_at) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button @click="restoreContact(contact.id)" 
                                        class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring ring-green-300 transition ease-in-out duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Restore
                                </button>
                                
                                <button @click="permanentlyDeleteContact(contact.id)" 
                                        class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring ring-red-300 transition ease-in-out duration-150">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete Forever
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="contacts.links && contacts.links.length > 3" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link v-if="contacts.prev_page_url" 
                                  :href="contacts.prev_page_url" 
                                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </Link>
                            <Link v-if="contacts.next_page_url" 
                                  :href="contacts.next_page_url" 
                                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ contacts.from || 0 }}</span> to <span class="font-medium">{{ contacts.to || 0 }}</span> of <span class="font-medium">{{ contacts.total || 0 }}</span> results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <template v-for="link in contacts.links" :key="link.label">
                                        <Link v-if="link.url && !link.active" 
                                              :href="link.url" 
                                              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 first:rounded-l-md last:rounded-r-md">
                                            <span v-if="link.label.includes('Previous')" class="sr-only">Previous</span>
                                            <span v-else-if="link.label.includes('Next')" class="sr-only">Next</span>
                                            <span v-else>{{ link.label }}</span>
                                            <svg v-if="link.label.includes('Previous')" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg v-if="link.label.includes('Next')" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </Link>
                                        <span v-else-if="link.active" 
                                              class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600 first:rounded-l-md last:rounded-r-md">
                                            {{ link.label }}
                                        </span>
                                        <span v-else 
                                              class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed first:rounded-l-md last:rounded-r-md">
                                            <span v-if="link.label.includes('Previous')" class="sr-only">Previous</span>
                                            <span v-else-if="link.label.includes('Next')" class="sr-only">Next</span>
                                            <span v-else>{{ link.label }}</span>
                                            <svg v-if="link.label.includes('Previous')" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <svg v-if="link.label.includes('Next')" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
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
            <div v-else class="bg-white shadow rounded-lg">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No deleted contacts</h3>
                    <p class="mt-1 text-sm text-gray-500">The trash is empty.</p>
                    <div class="mt-6">
                        <Link :href="route('contacts.index')" 
                              class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring ring-blue-300 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

defineProps({ contacts: Object })

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
