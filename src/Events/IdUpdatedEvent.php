<?php
namespace src\Events;

/**
 * Represents an event that is triggered when a model's ID is updated.
 */
class IdUpdatedEvent
{
    /**
     * The model whose ID was updated.
     *
     * @var mixed
     */
    public $model;

    /**
     * The old ID value.
     *
     * @var mixed
     */
    public $oldId;

    /**
     * The new ID value.
     *
     * @var mixed
     */
    public $newId;

    /**
     * Constructs the IdUpdatedEvent.
     *
     * @param mixed $model The model whose ID was updated.
     * @param mixed $oldId The old ID value.
     * @param mixed $newId The new ID value.
     */
    public function __construct($model, $oldId, $newId)
    {
        $this->model = $model;
        $this->oldId = $oldId;
        $this->newId = $newId;
    }
}