<?php

namespace App\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;

class NoteData extends DataTransferObject
{
    /**
     * Title of record
     *
     * @var string
     */
    public string $title;

    /**
     * Description of record
     *
     * @var string|null
     */
    public ?string $description;

    /**
     * Type of record, means folder or note
     *
     * @var string|null
     */
    public ?string $type;

    /**
     * Parent id
     *
     * @var int|null
     */
    public ?int $parent_id;

    /**
     * Owner id
     *
     * @var int|null
     */
    public ?int $owner_id;
}
