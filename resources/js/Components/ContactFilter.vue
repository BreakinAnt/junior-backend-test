<template>
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 sm:space-x-4">
                <!-- Search Input -->
                <div class="flex-1 min-w-0">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input
                            v-model="localSearch"
                            @input="handleSearch"
                            type="text"
                            :placeholder="placeholder"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        />
                        <div v-if="localSearch" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button
                                @click="clearSearch"
                                type="button"
                                class="text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Sort By -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label for="sortBy" class="text-sm font-medium text-gray-700">Sort by:</label>
                        <select
                            id="sortBy"
                            v-model="localSortBy"
                            @change="handleSort"
                            class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                        >
                            <option value="name">Name</option>
                            <option value="email">Email</option>
                            <option value="created_at">Date Added</option>
                            <option v-if="showDeletedAt" value="deleted_at">Date Deleted</option>
                        </select>
                    </div>
                    
                    <!-- Sort Direction -->
                    <button
                        @click="toggleSortDirection"
                        class="p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500"
                        :title="localSortDirection === 'asc' ? 'Sort Ascending' : 'Sort Descending'"
                    >
                        <svg v-if="localSortDirection === 'asc'" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"/>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Active Filters -->
            <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap items-center gap-2">
                <span class="text-sm text-gray-500">Active filters:</span>
                <span v-if="localSearch" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Search: "{{ localSearch }}"
                    <button @click="clearSearch" class="ml-1 text-blue-600 hover:text-blue-800">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </span>
                <span v-if="localSortBy !== 'name' || localSortDirection !== 'asc'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Sort: {{ localSortBy }} {{ localSortDirection === 'asc' ? '↑' : '↓' }}
                </span>
                <button 
                    @click="clearAllFilters"
                    class="text-xs text-red-600 hover:text-red-800 underline"
                >
                    Clear all filters
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'

const props = defineProps({
    search: {
        type: String,
        default: ''
    },
    sortBy: {
        type: String,
        default: 'name'
    },
    sortDirection: {
        type: String,
        default: 'asc'
    },
    placeholder: {
        type: String,
        default: 'Search contacts...'
    },
    showDeletedAt: {
        type: Boolean,
        default: false
    },
    routeName: {
        type: String,
        required: true
    }
})

const localSearch = ref(props.search)
const localSortBy = ref(props.sortBy)
const localSortDirection = ref(props.sortDirection)

const hasActiveFilters = computed(() => {
    return localSearch.value || localSortBy.value !== 'name' || localSortDirection.value !== 'asc'
})

// Debounced search function
const debouncedSearch = debounce((searchTerm) => {
    router.get(route(props.routeName), {
        search: searchTerm,
        sort_by: localSortBy.value,
        sort_direction: localSortDirection.value
    }, {
        preserveState: true,
        replace: true
    })
}, 300)

const handleSearch = () => {
    debouncedSearch(localSearch.value)
}

const handleSort = () => {
    router.get(route(props.routeName), {
        search: localSearch.value,
        sort_by: localSortBy.value,
        sort_direction: localSortDirection.value
    }, {
        preserveState: true,
        replace: true
    })
}

const toggleSortDirection = () => {
    localSortDirection.value = localSortDirection.value === 'asc' ? 'desc' : 'asc'
    handleSort()
}

const clearSearch = () => {
    localSearch.value = ''
    handleSearch()
}

const clearAllFilters = () => {
    localSearch.value = ''
    localSortBy.value = 'name'
    localSortDirection.value = 'asc'
    
    router.get(route(props.routeName), {}, {
        preserveState: true,
        replace: true
    })
}

// Watch for external changes to props
watch(() => props.search, (newVal) => {
    localSearch.value = newVal
})

watch(() => props.sortBy, (newVal) => {
    localSortBy.value = newVal
})

watch(() => props.sortDirection, (newVal) => {
    localSortDirection.value = newVal
})
</script>
