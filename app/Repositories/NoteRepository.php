<?php

namespace App\Repositories;

use App\Exceptions\DatabaseException;
use App\Model\Note;
use App\Repositories\Interfaces\DatabaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class NoteRepository implements DatabaseRepositoryInterface
{
    /**
     * Get all notes
     *
     * @param int $ownerId
     * @return mixed
     */
    public function getAll(int $ownerId): Collection
    {
        return Note::where("owner_id", $ownerId)
            ->where("parent_id", null)
            ->orderBy("id", "desc")
            ->get();
    }

    /**
     * Store note or folder
     *
     * @param array $data
     * @return Note
     * @throws DatabaseException
     */
    public function store(array $data): Note
    {
        try {
            return Note::create($data);
        } catch (Exception $exception) {
            throw new DatabaseException("Error during store note");
        }
    }

    /**
     * Get a Note
     *
     * @param int $recordId
     * @param int $ownerId
     * @return mixed
     * @throws DatabaseException
     */
    public function getById(int $recordId, int $ownerId): Note
    {
        try {
            return Note::where("owner_id", $ownerId)
                ->where("id", $recordId)
                ->firstOrFail();
        } catch (Exception $exception) {
            throw new DatabaseException("Error during get a note");
        }
    }

    /**
     * Update note or folder
     *
     * @param Note $note
     * @param array $data
     *
     * @return Note
     * @throws DatabaseException
     */
    public function update(Note $note, array $data): Note
    {
        try {
            $note->update($data);

            return $note;
        } catch (Exception $exception) {
            throw new DatabaseException("Error during update a note");
        }
    }

    /**
     * Remove note or folder
     *
     * @param int $recordId
     * @param int $ownerId
     *
     * @return void
     * @throws DatabaseException
     */
    public function destroy(int $recordId, int $ownerId): void
    {
        try {
            Note::destroy($recordId);
        } catch (Exception $exception) {
            throw new DatabaseException("Error during remove a note");
        }
    }

    /**
     * Remove notes in folder
     *
     * @param string $column
     * @param int $recordId
     * @param int $ownerId
     *
     * @return void
     * @throws DatabaseException
     */
    public function destroyByColumn(string $column, int $recordId, int $ownerId): void
    {
        try {
            Note::where("owner_id", $ownerId)
                ->where($column, $recordId)
                ->delete();
        } catch (Exception $e) {
            throw new DatabaseException("Error during remove folder's notes");
        }
    }
}
