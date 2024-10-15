<?php

namespace App\Services;

use App\Contracts\AuthInterface;
use App\DataTransferObjects\NoteData;
use App\Events\NoteEvent;
use App\Exceptions\DatabaseException;
use App\Model\Note;
use App\Repositories\Interfaces\DatabaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class NoteService
{
    private $noteRepository;
    private $userId;

    public function __construct(
        DatabaseRepositoryInterface $noteRepository,
        AuthInterface $auth
    ) {
        $this->noteRepository = $noteRepository;
        $this->userId         = $auth->id();
    }

    /**
     * Get all Notes
     *
     * @return Collection
     */
    public function getNotes(): Collection
    {
        return $this->noteRepository->getAll($this->userId);
    }

    /**
     * Store a Note
     *
     * @param NoteData $data
     *
     * @return Note
     * @throws DatabaseException
     */
    public function createNote(NoteData $data): Note
    {
        $data->owner_id = $this->userId;

        $note = $this->noteRepository->store($data->all());

        event(new NoteEvent($note));

        return $note;
    }

    /**
     * Get a Note
     *
     * @param int $recordId
     *
     * @return Note
     * @throws DatabaseException
     */
    public function getNote(int $recordId): Note
    {
        $note          = $this->noteRepository->getById($recordId, $this->userId);
        $note->created = $note->created_at->format('d-m-Y H:i');
        $note->updated = $note->updated_at->format('d-m-Y H:i');

        return $note;
    }

    /**
     * Update a Note
     *
     * @param int $recordId
     * @param NoteData $data
     *
     * @return Note
     * @throws DatabaseException
     */
    public function updateNote(int $recordId, NoteData $data): Note
    {
        $note = $this->noteRepository->getById($recordId, $this->userId);
        $note = $this->noteRepository->update(
            $note,
            $data->only("title", "description")->toArray()
        );

        event(new NoteEvent($note));

        return $note;
    }

    /**
     * Remove note
     *
     * @param int $recordId
     *
     * @return void
     * @throws DatabaseException
     */
    public function destroyNote(int $recordId): void
    {
        DB::beginTransaction();
        try {
            $note = $this->noteRepository->getById($recordId, $this->userId);

            if ($note->type === "folder" && count($note->load("notes")->notes)) {
                $this->noteRepository->destroyByColumn("parent_id", $recordId, $this->userId);
            }

            $this->noteRepository->destroy($recordId, $this->userId);

            event(new NoteEvent(["owner_id" => $this->userId]));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            throw new DatabaseException($e->getMessage());
        }
    }

    /**
     * Get a Folder
     *
     * @param int $folderId
     *
     * @return Note
     * @throws DatabaseException
     */
    public function getFolder(int $folderId): Note
    {
        $note = $this->noteRepository->getById($folderId, $this->userId);
        $note->load("notes");

        return $note;
    }
}
