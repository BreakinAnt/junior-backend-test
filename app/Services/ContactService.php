<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ContactService
{
    protected ContactRepositoryInterface $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     *
     * @param array $data
     * @return Contact
     * @throws \Exception
     */
    public function createContact(array $data): Contact
    {
        try {
            $cleanData = $this->cleanContactData($data);
            $contact = $this->contactRepository->create($cleanData);
            
            return $contact;
        } catch (\Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage(), [
                'data' => $data,
                'exception' => $e
            ]);
            
            throw $e;
        }
    }

    /**
     *
     * @param int $id
     * @return Contact|null
     */
    public function getContactById(int $id): ?Contact
    {
        return $this->contactRepository->findById($id);
    }

    /**
     *
     * @return Collection
     */
    public function getPagination(int $perPage = 15): LengthAwarePaginator 
    {
        return $this->contactRepository->getPagination($perPage);
    }
    /**
     *
     * @return Collection
     */
    public function getAllContacts(): Collection
    {
        return $this->contactRepository->getAll();
    }

    /**
     *
     * @param int $id
     * @param array $data
     * @return Contact|null
     */
    public function updateContact(int $id, array $data): ?Contact
    {
        $cleanData = $this->cleanContactData($data);
        return $this->contactRepository->update($id, $cleanData);
    }

    /**
     *
     * @param int $id
     * @return bool
     */
    public function deleteContact(int $id): bool
    {
        return $this->contactRepository->delete($id);
    }

    /**
     * Get paginated trashed contacts
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTrashedPagination(int $perPage = 10): LengthAwarePaginator
    {
        return $this->contactRepository->getTrashedPaginated($perPage);
    }

    /**
     * Restore a trashed contact
     *
     * @param int $id
     * @return bool
     */
    public function restoreContact(int $id): bool
    {
        return $this->contactRepository->restore($id);
    }

    /**
     * Permanently delete a contact
     *
     * @param int $id
     * @return bool
     */
    public function forceDeleteContact(int $id): bool
    {
        return $this->contactRepository->forceDelete($id);
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
        return $this->contactRepository->getPaginationWithFilters($perPage, $search, $sortBy, $sortDirection);
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
        return $this->contactRepository->getTrashedPaginationWithFilters($perPage, $search, $sortBy, $sortDirection);
    }

    /**
     *
     * @param array $data
     * @return array
     */
    private function cleanContactData(array $data): array
    {
        $cleanData = [];
        
        if (isset($data['name'])) {
            $cleanData['name'] = trim($data['name']);
        }
        
        if (isset($data['email'])) {
            $cleanData['email'] = strtolower(trim($data['email']));
        }
        
        if (isset($data['phone'])) {
            $cleanData['phone'] = preg_replace('/\D/', '', $data['phone']);
        }
        
        return $cleanData;
    }
}
