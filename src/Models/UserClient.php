<?php
namespace src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a User-Client relationship in the system.
 *
 * This model is associated with the `tblusers_clients` table and is used to manage the many-to-many
 * relationship between users and clients. It includes methods for setting the database connection
 * and defining relationships to the `Client` and `User` models.
 */
class UserClient extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tblusers_clients';

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
     * Define a belongs-to relationship with the `Client` model.
     *
     * This relationship indicates that a user-client association belongs to a specific client.
     * The foreign key `client_id` is used to establish the relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Define a belongs-to relationship with the `User` model.
     *
     * This relationship indicates that a user-client association belongs to a specific user.
     * The foreign key `auth_user_id` is used to establish the relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }
}