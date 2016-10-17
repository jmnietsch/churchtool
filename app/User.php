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
        'name', 'email', 'password',
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
     * Return the groups this user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Group')->withPivot('is_admin')->withTimestamps();
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

}