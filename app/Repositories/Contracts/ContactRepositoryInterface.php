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
}
