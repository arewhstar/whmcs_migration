<?php
namespace src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Represents a User in the system.
 *
 * This model is associated with the `tblusers` table and is used to manage user records.
 * It includes methods for setting the database connection, defining relationships, and updating related records.
 */
class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblusers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     * Set the connection name for the model.
     *
     * This method allows dynamically setting the database connection for the model.
     *
     * @param string $connection The name of the connection.
     * @return void
     */
    public function setConnectionName($connection)
    {
        $this->setConnection($connection);
    }

    /**
     * Define a one-to-many relationship with the `UserClient` model.
     *
     * This relationship indicates that a user can have multiple user-client associations.
     * The foreign key `auth_user_id` is used to establish the relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userClients(): HasMany
    {
        return $this->hasMany(UserClient::class, 'auth_user_id');
    }

    /**
     * Update related records in the `tblusers_clients` table when the user's ID changes.
     *
     * This method updates the `auth_user_id` in the `tblusers_clients` table for all related records
     * when the user's ID is updated. It ensures data consistency by propagating the ID change
     * to all associated records.
     *
     * @param int $oldId The original ID of the user.
     * @param int $newId The new ID of the user.
     * @return void
     */
    public function updateRelatedIds($oldId, $newId)
    {
        if ($this->userClients()->exists()) {
            $this->userClients()->update(['auth_user_id' => $newId]);
            echo "Updated tblusers_clients: set auth_user_id from $oldId to $newId.\n";
        }
    }
}