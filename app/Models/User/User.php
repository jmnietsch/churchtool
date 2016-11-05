<?php
namespace App\Models\User;

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
        'password',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'date_of_birth',
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
     * Is this user admin or member of the given group
     *
     * @return boolean
     */
    public function isAdminOrMemberOf(Group $group)
    {
        return is_null($this->groups()->find($group->id)) ? false : true;
    }

    /**
     * Begin query the groups this user is admin of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\User\Group')->withPivot('is_admin')->withTimestamps(
        );
    }

    /**
     * Make this user an admin of the given group (possibly only sets the is_admin-flag,
     * if the user has been a member before.
     *
     * @param Group $group
     */
    public function setAdminOf(Group $group)
    {
        if (!$this->isMemberOf($group)) {
            $this->groups()->attach($group->id, ['is_admin' => true]);
        } else {
            $this->groups()->updateExistingPivot($group->id, ['is_admin' => true]);
        }
    }

    /**
     * Is this user member of the given group?
     *
     * @return boolean
     */
    public function isMemberOf(Group $group)
    {
        return is_null($this->groupsMember()->find($group->id)) ? false : true;
    }

    /**
     * Begin query the groups this user is admin or member of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupsMember()
    {
        return $this->groups()->wherePivot('is_admin', '=', false);
    }

    /**
     * Make this user a member of the given group (possibly only clears the is_admin-flag,
     * if the user has been an admin before.
     *
     * @param Group $group
     */
    public function setMemberOf(Group $group)
    {
        if (!$this->isAdminOf($group)) {
            $this->groups()->attach($group->id, ['is_admin' => false]);
        } else {
            $this->groups()->updateExistingPivot($group->id, ['is_admin' => false]);
        }
    }

    /**
     * Is this user admin of the given group?
     *
     * @return boolean
     */
    public function isAdminOf(Group $group)
    {
        return is_null($this->groupsAdmin()->find($group->id)) ? false : true;
    }

    /**
     * Begin query the groups this user is member of.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groupsAdmin()
    {
        return $this->groups()->wherePivot('is_admin', '=', true);
    }

}