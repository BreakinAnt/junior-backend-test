<?php

namespace App\Services;

use App\Models\Contact;
use App\Repositories\Contracts\ContactRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
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
