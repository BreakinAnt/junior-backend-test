<template>
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <!-- Search Input -->
                <div class="flex-1 md:max-w-md">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            :placeholder="placeholder"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            @input="debounceSearch"
                        >
                        <div v-if="searchQuery" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button
                                @click="clearSearch"
                                class="text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Sort Dropdown -->
                    <div class="relative">
                        <select
                            v-model="sortBy"
                            @change="handleSortChange"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        >
                            <option value="name">Sort by Name</option>
                            <option value="email">Sort by Email</option>
                            <option value="created_at">Sort by Date Created</option>
                            <option v-if="showDeletedSort" value="deleted_at">Sort by Date Deleted</option>
                        </select>
                    </div>

                    <!-- Sort Direction -->
                    <button
                        @click="toggleSortDirection"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg v-if="sortDirection === 'asc'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <svg v-else class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                        </svg>
                        <span class="ml-1">{{ sortDirection === 'asc' ? 'A-Z' : 'Z-A' }}</span>
                    </button>

                    <!-- Clear Filters -->
                    <button
                        v-if="hasActiveFilters"
                        @click="clearAllFilters"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear Filters
                    </button>
                </div>
            </div>

            <!-- Active Filters Display -->
            <div v-if="hasActiveFilters" class="mt-3 flex flex-wrap gap-2">
                <span v-if="searchQuery" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Search: "{{ searchQuery }}"
                    <button @click="clearSearch" class="ml-1 inline-flex items-center p-0.5 rounded-full text-blue-400 hover:text-blue-600">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
                <span v-if="sortBy !== 'name' || sortDirection !== 'asc'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Sort: {{ sortBy }} ({{ sortDirection }})
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    placeholder: {
        type: String,
        default: 'Search contacts...'
    },
    initialSearch: {
        type: String,
        default: ''
    },
    initialSort: {
        type: String,
        default: 'name'
    },
    initialDirection: {
        type: String,
        default: 'asc'
    },
    showDeletedSort: {
        type: Boolean,
        default: false
    },
    routeName: {
        type: String,
        required: true
    }
})

const searchQuery = ref(props.initialSearch)
const sortBy = ref(props.initialSort)
const sortDirection = ref(props.initialDirection)
const debounceTimeout = ref(null)

const hasActiveFilters = computed(() => {
    return searchQuery.value || sortBy.value !== 'name' || sortDirection.value !== 'asc'
})

const debounceSearch = () => {
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value)
    }
    
    debounceTimeout.value = setTimeout(() => {
        performSearch()
    }, 300)
}

const performSearch = () => {
    const params = {}
    
    if (searchQuery.value) {
        params.search = searchQuery.value
    }
    
    if (sortBy.value !== 'name') {
        params.sort = sortBy.value
    }
    
    if (sortDirection.value !== 'asc') {
        params.direction = sortDirection.value
    }
    
    router.get(route(props.routeName), params, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}

const handleSortChange = () => {
    performSearch()
}

const toggleSortDirection = () => {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc'
    performSearch()
}

const clearSearch = () => {
    searchQuery.value = ''
    performSearch()
}

const clearAllFilters = () => {
    searchQuery.value = ''
    sortBy.value = 'name'
    sortDirection.value = 'asc'
    
    router.get(route(props.routeName), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}
</script>
