<?php

namespace App\Repositories\Contracts;

use App\Models\Contact;

interface ContactRepositoryInterface
{
    /**
     *
     * @param array $data
     * @return Contact
     */
    public function create(array $data): Contact;

    /**
     *
     * @param int $id
     * @return Contact|null
     */
    public function findById(int $id): ?Contact;

    /**
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll();

    /**
     *
     * @param int $id
     * @param array $data
     * @return Contact|null
     */
    public function update(int $id, array $data): ?Contact;

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * Get paginated trashed contacts
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTrashedPaginated(int $perPage = 10);

    /**
     * Restore a trashed contact
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool;

    /**
     * Permanently delete a contact
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool;

    /**
     * Get paginated contacts with filters
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getPaginationWithFilters(int $perPage = 15, ?string $search = null, string $sortBy = 'name', string $sortDirection = 'asc'): \Illuminate\Pagination\LengthAwarePaginator;

    /**
     * Get paginated trashed contacts with filters
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTrashedPaginationWithFilters(int $perPage = 10, ?string $search = null, string $sortBy = 'name', string $sortDirection = 'asc'): \Illuminate\Pagination\LengthAwarePaginator;
}
