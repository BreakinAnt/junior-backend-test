<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactRepository implements ContactRepositoryInterface
{
    /**
     *
     * @param array $data
     * @return Contact
     */
    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    /**
     *
     * @param int $id
     * @return Contact|null
     */
    public function findById(int $id): ?Contact
    {
        return Contact::find($id);
    }

    /**
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return Contact::all();
    }

    public function getPagination(int $perPage = 15): LengthAwarePaginator
    {
        return Contact::paginate($perPage);
    }

    /**
     * Get paginated contacts with filters
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @return LengthAwarePaginator
     */
    public function getPaginationWithFilters(int $perPage = 15, ?string $search = null, string $sortBy = 'name', string $sortDirection = 'asc'): LengthAwarePaginator
    {
        $query = Contact::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get paginated trashed contacts with filters
     *
     * @param int $perPage
     * @param string|null $search
     * @param string $sortBy
     * @param string $sortDirection
     * @return LengthAwarePaginator
     */
    public function getTrashedPaginationWithFilters(int $perPage = 10, ?string $search = null, string $sortBy = 'name', string $sortDirection = 'asc'): LengthAwarePaginator
    {
        $query = Contact::onlyTrashed();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortDirection);

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     *
     * @param int $id
     * @param array $data
     * @return Contact|null
     */
    public function update(int $id, array $data): ?Contact
    {
        $contact = Contact::find($id);
        
        if (!$contact) {
            return null;
        }

        $contact->update($data);
        return $contact->fresh();
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $contact = Contact::find($id);
        
        if (!$contact) {
            return false;
        }

        return $contact->delete();
    }

    /**
     * Get paginated trashed contacts
     *
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTrashedPaginated(int $perPage = 10)
    {
        return Contact::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate($perPage);
    }

    /**
     * Restore a trashed contact
     *
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $contact = Contact::onlyTrashed()->find($id);
        
        if (!$contact) {
            return false;
        }

        return $contact->restore();
    }

    /**
     * Permanently delete a contact
     *
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id): bool
    {
        $contact = Contact::onlyTrashed()->find($id);
        
        if (!$contact) {
            return false;
        }

        return $contact->forceDelete();
    }
}
