<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * Caches the capabilities that this user has.
     *
     * @var integer
     */
    protected $capabilities = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'first_name',
        'last_name',
        'date_of_birth',
        'active',
        'sex',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This mutator automatically hashes the password.
     *
     * @var string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = \Hash::make($value);
    }

    /**
     * Return the groups this user is member of.
     *
     * @return Group[]
     */
    public function groupsMember()
    {
        return $this->groups()->wherePivot('is_admin', '=', false);
    }

    /**
     * Return the groups this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')->withPivot('is_admin')->withTimestamps();
    }

    /**
     * Return the groups where this user is admin.
     *
     * @return Group[]
     */
    public function groupsAdmin()
    {
        return $this->groups()->wherePivot('is_admin', '=', true);
    }

    /**
     * Does user have this capability?
     *
     * @param integer $capability
     * @return boolean
     *
     * @see \App\Capability
     */
    public function hasCapability($capability)
    {
        $this->retrieveCapabilities();
        return (($capability & $this->capabilities) == $capability) ? true : false;
    }

    /**
     * If $capabilities === null, derives the capabilities from the user's group memberships
     * and stores them in $capabilities.
     */
    protected function retrieveCapabilities()
    {
        if (!is_null($this->capabilities))
            return;

        $this->capabilities = 0b0;
        foreach ($this->groups as $group) {
            $this->capabilities |= $group->member_capabilities;
            if ($group->pivot->is_admin)
                $this->capabilities |= $group->admin_capabilities;
        }
    }

    /**
     * Check if this user is active (i.e. allowed to log in).
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

}