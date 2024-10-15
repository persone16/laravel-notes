<?php

namespace App\Contracts;

use App\Model\Note;
use Illuminate\Database\Eloquent\Collection;

interface DatabaseRepositoryInterface
{
    /**
     * Get all
     *
     * @param int $ownerId
     *
     * @return mixed
     */
    public function getAll(int $ownerId): Collection;

    /**
     * Store
     *
     * @param array $data
     *
     * @return Note
     */
    public function store(array $data): Note;

    /**
     * Get record by id
     *
     * @param int $recordId
     * @param int $ownerId
     *
     * @return Note
     */
    public function getById(int $recordId, int $ownerId): Note;

    /**
     * Update
     *
     * @param Note $note
     * @param array $data
     *
     * @return Note
     */
    public function update(Note $note, array $data): Note;

    /**
     * Remove
     *
     * @param int $recordId
     * @param int $ownerId
     *
     * @return void
     */
    public function destroy(int $recordId, int $ownerId): void;
}