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
}
