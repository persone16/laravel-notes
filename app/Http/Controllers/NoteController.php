<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\NoteData;
use App\Http\Requests\Notes\StoreRequest;
use App\Http\Requests\Notes\UpdateRequest;
use App\Http\Resources\Notes\AdvancedNoteResource;
use App\Http\Resources\Notes\FolderNoteResource;
use App\Http\Resources\Notes\NoteResource;
use App\Services\NoteService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

class NoteController extends Controller
{
    private $noteService;

    public function __construct(NoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    /**
     * @SWG\Get(
     *     tags={"Notes Component"},
     *     path="/api/notes",
     *     summary="list notes",
     *     @SWG\Response(
     *         response="200",
     *         description="Return all notes and folders for current auth user"
     *     )
     * )
     */
    public function index(): ResourceCollection
    {
        $notes = $this->noteService->getNotes();

        return NoteResource::collection($notes);
    }

    /**
     * @SWG\Post(
     *     tags={"Notes Component"},
     *     path="/api/notes",
     *     summary="create a note",
     *     @SWG\Parameter(
     *         in="formData",
     *         name="title",
     *         required=true,
     *         type="string",
     *         description="Title of the note or the folder"
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="type",
     *         required=true,
     *         type="string",
     *         description="Type of the item",
     *         enum={"note", "folder"},
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="parent_id",
     *         required=false,
     *         type="number",
     *         description="Parent id, if we are creating note inside a folder",
     *     ),
     *     @SWG\Response(
     *         response="201",
     *         description="Successfull created a note or a folder"
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Database Error"
     *     )
     * )
     */
    public function create(StoreRequest $request): JsonResponse
    {
        $data = new NoteData($request->validated());

        $note = $this->noteService->createNote($data);

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @SWG\Get(
     *     tags={"Notes Component"},
     *     path="/api/notes/{id}",
     *     summary="get note",
     *     @SWG\Parameter(
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="number",
     *         description="Id of item",
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Return data of item (note or folder)"
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Database Error"
     *     )
     * )
     */
    public function view(int $id): JsonResource
    {
        $note = $this->noteService->getNote($id);

        return new AdvancedNoteResource($note);
    }

    /**
     * @SWG\Put(
     *     tags={"Notes Component"},
     *     path="/api/notes/{id}",
     *     summary="update note",
     *     @SWG\Parameter(
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="number",
     *         description="Id of note or folder"
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="title",
     *         required=false,
     *         type="string",
     *         description="Title of the note or the folder",
     *     ),
     *     @SWG\Parameter(
     *         in="formData",
     *         name="description",
     *         required=false,
     *         type="string",
     *         description="Description for a note or a folder",
     *     ),
     *     @SWG\Response(
     *         response="202",
     *         description="Return id of updated item"
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Database Error"
     *     )
     * )
     */
    public function update(int $id, UpdateRequest $request): JsonResponse
    {
        $data = new NoteData($request->validated());

        $note = $this->noteService->updateNote($id, $data);

        return (new NoteResource($note))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @SWG\Delete(
     *     tags={"Notes Component"},
     *     path="/api/notes/{id}",
     *     summary="delete note",
     *     @SWG\Parameter(
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="number",
     *         description="Id of note or folder"
     *     ),
     *     @SWG\Response(
     *         response="204",
     *         description="No content"
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Database Error"
     *     )
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->noteService->destroyNote($id);

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @SWG\Get(
     *     tags={"Notes Component"},
     *     path="/api/notes/folder/{id}",
     *     summary="get notes by folder",
     *     @SWG\Parameter(
     *         in="query",
     *         name="id",
     *         required=true,
     *         type="number",
     *         description="Id of folder"
     *     ),
     *     @SWG\Response(
     *         response="200",
     *         description="Return folder data and his notes"
     *     ),
     *     @SWG\Response(
     *         response="500",
     *         description="Database Error"
     *     )
     * )
     */
    public function viewFolder(int $id): JsonResource
    {
        $note = $this->noteService->getFolder($id);

        return new FolderNoteResource($note);
    }
}
