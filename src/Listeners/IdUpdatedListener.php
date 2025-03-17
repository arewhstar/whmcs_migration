<?php
namespace src\Listeners;

use src\Events\IdUpdatedEvent;

/**
 * Listener for handling the IdUpdatedEvent.
 */
class IdUpdatedListener
{
    /**
     * Handles the IdUpdatedEvent.
     *
     * @param IdUpdatedEvent $event The event containing the model, old ID, and new ID.
     */
    public function handle(IdUpdatedEvent $event)
    {
        if (method_exists($event->model, 'updateRelatedIds')) {
            $event->model->updateRelatedIds($event->oldId, $event->newId);
        }
    }
}