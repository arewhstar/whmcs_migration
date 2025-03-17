<?php
namespace src\Services;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use src\Events\IdUpdatedEvent;
use Illuminate\Events\Dispatcher;

class IdSyncService
{
    /**
     * Adjusts IDs in the source model to avoid conflicts with the target model.
     *
     * @param Model $sourceModel The source model.
     * @param Model $targetModel The target model.
     * @param Dispatcher $dispatcher The event dispatcher.
     * @return void
     */
    public function syncIds(Model $sourceModel, Model $targetModel, Dispatcher $dispatcher)
    {
        $tableName = $sourceModel->getTable();
        $targetMaxId = $this->getMaxId($targetModel);



            $targetMaxId = $this->getMaxId($targetModel);
            $targetMinId = $this->getMinId($targetModel); 
            // Find conflicting records in the source database
            $conflictingRecords = $sourceModel->newQuery()
                ->whereBetween('id', [$targetMinId, $targetMaxId]) 
                ->orderBy('id')
                ->get();

            if ($conflictingRecords->isEmpty()) {
                echo "No conflicting IDs found for table: $tableName.\n";
                return;
            }

            echo "Found " . $conflictingRecords->count() . " conflicting IDs in table: $tableName.\n";

            // Update the IDs in the source database to avoid conflicts
            foreach ($conflictingRecords as $record) {
                $oldId = $record->id;
                $newId = $this->findNextAvailableId($targetModel, $targetMaxId);
                echo "Updating ID from {$record->id} to $newId for record in table: $tableName.\n";

                // Dispatch the IdUpdatedEvent
                $dispatcher->dispatch(new IdUpdatedEvent($record, $oldId, $newId));

                // Update the record with the new ID
                $record->id = $newId;
                $record->save();
            }

            // Reset AUTO_INCREMENT for source DB
            $this->resetAutoIncrement($sourceModel);


    }

    /**
     * Gets the maximum ID in the target database.
     *
     * @param Model $targetModel The target model.
     * @return int The maximum ID.
     */
    private function getMaxId(Model $targetModel)
    {
        return (int) $targetModel->newQuery()->max('id');
    }

    /**
     * Gets the minimum ID in the target database.
     *
     * @param Model $targetModel The target model.
     * @return int The minimum ID.
     */
    private function getMinId(Model $targetModel)
    {
        return (int) $targetModel->newQuery()->min('id');
    }

    private function findNextAvailableId(Model $targetModel, int $startId)
    {
        while ($targetModel->newQuery()->where('id', $startId)->exists()) {
            $startId++;
        }
        return $startId;
    }
    /**
     * Resets the AUTO_INCREMENT value in the source database.
     *
     * @param Model $sourceModel The source model.
     * @return void
     */
    private function resetAutoIncrement(Model $sourceModel)
    {
        $tableName = $sourceModel->getTable();
        $connectionName = $sourceModel->getConnectionName();

        try {
            // Temporarily disable strict mode to allow AUTO_INCREMENT reset. Restrict the default created_at, updated_at formats.
            Capsule::connection($connectionName)->statement("SET SESSION sql_mode = ''");

            // Calculate the next AUTO_INCREMENT value
            $nextAutoIncrement = $this->getMaxId($sourceModel) + 1;

            // Reset AUTO_INCREMENT
            Capsule::connection($connectionName)
                ->statement("ALTER TABLE $tableName AUTO_INCREMENT = $nextAutoIncrement");

            echo "AUTO_INCREMENT reset to $nextAutoIncrement for table: $tableName.\n";

            // Re-enable strict mode
            Capsule::connection($connectionName)->statement("SET SESSION sql_mode = 'STRICT_TRANS_TABLES'");
        } catch (Exception $e) {
            echo "Failed to reset AUTO_INCREMENT for table: $tableName. Error: " . $e->getMessage() . "\n";
        }
    }
}