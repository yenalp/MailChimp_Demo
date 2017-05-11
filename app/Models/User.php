<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Exceptions\LoginExcpetion;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use SoftDeletes;
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $domain = 'USERS';

    public $timestamps = true;

    /**
     * Adding deleted_at to the dates array
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The list of attribute's that can be mass-assigned
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'password',
        'legacy_user_id',
        'contact_number',
        'user_type',
        'disabled',
        'source',
        'import_version',
        'whitelist_ips',
        'logging_enabled',
        'import_id',
        'updated_at',
        'deleted_at',
        'created_at',
        'token_admin',
        'token_expires_admin',
        'token_consultant',
        'token_expires_consultant',
        'token_client',
        'token_expires_client',
        'id',
        'no_active_users',
        'permissions'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'first_name' => 'string',
        'last_name' => 'string',
        'user_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'legacy_user_id' => 'string',
        'contact_number' => 'string',
        'user_type' => 'string',
        'disabled' => 'boolean',
        'source' => 'string',
        'import_version' => 'string',
        'whitelist_ips' => 'json',
        'permissions' => 'json',
        'id' => 'integer',
        'import_id' => 'string',
        'token_admin' => 'string',
        'token_expires_admin' => 'datetime',
        'token_consultant' => 'string',
        'token_expires_consultant' => 'datetime',
        'token_client' => 'string',
        'token_expires_client' => 'datetime',
        'remember_token' => 'string',
        'no_active_users' => 'integer'
    ];

    /**
     * The attributes that should be hidden.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'deleted_at',
        'password',
        'searchable',
        'logging_enabled',
    ];

    public $fieldsToLog = [
        'first_name',
        'last_name',
        'user_name',
        'email',
        'contact_number',
        'user_type',
        'disabled',
    ];

    public function getModelLogDescription()
    {
        return parent::getModelLogDescription() . " \"{$this->user_name}({$this->id})\"";
    }

    public function appendExclusionQuery($query)
    {
        return $query->whereRaw('deleted_at IS NULL');
    }

    /**
     * Sets the User as having many Previous Passwords
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function previousPasswords()
    {
        return $this->hasMany('App\Models\UserPasswordHistory')
            ->select(DB::raw('user_password_histories.*, \'previous_passwords\' as type'));
    }

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'user_name' => $this->user_name,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'user_type' => $this->user_type,
        ];
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function save(array $options = [], $isLoggingOnSave = true)
    {

        parent::save($options, $isLoggingOnSave);
    }

    public function getFullNameAttribute()
    {
        return "$this->first_name $this->last_name";
    }

    public function canLogin()
    {
        if ($this->disabled) {
            return false;
        }
        return true;
    }

    public function canLoginToSite($siteId)
    {
        return in_array($this->user_type, config("constants.SITE_USER_TYPES.{$siteId}"));
    }

    public function isTokenExpired($siteId)
    {
        $tokenField = "token_" . strtolower($siteId);
        $tokenExpiresField = "token_expires_" . strtolower($siteId);
        return !$this->{$tokenField} || $this->{$tokenExpiresField} < Carbon::now();
    }

    public function setNewToken($siteId)
    {
        $tokenField = "token_" . strtolower($siteId);
        if (!$this->{$tokenField}) {
            $this->{$tokenField} = str_random(62);
        }
    }

    public function refreshTokenExpiry($siteId)
    {
        $tokenExpiresField = "token_expires_" . strtolower($siteId);
        $currentTime = new Carbon();
        $this->{$tokenExpiresField} = $currentTime->addMinutes(env('SESSION_TIMEOUT'))->toDateTimeString();
        $this->save([], false);
    }

}
