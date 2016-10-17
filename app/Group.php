<?php

namespace App;

use App\Exceptions\InvalidCapabilityException;
use Illuminate\Database\Eloquent\Model;

/**
 * Base model for group management.
 */
class Group extends Model
{
    /**
     * These attributes are mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'enable_mail'
    ];

    /**
     * Get users that belong to this group.
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withPivot('is_admin')->withTimestamps();
    }

    /**
     * Add a capability to members of this group.
     *
     * @param integer $capability
     * @throws InvalidCapabilityException
     */
    public function allowMembersTo($capability)
    {
        Capability::validate($capability);
        $this->member_capabilities |= $capability;
        $this->save();
    }

    /**
     * Add a capability to admins of this group.
     *
     * @param integer $capability
     * @throws InvalidCapabilityException
     */
    public function allowAdminsTo($capability)
    {
        Capability::validate($capability);
        $this->admin_capabilities |= $capability;
        $this->save();
    }

    /**
     * Remove a capability from members of this group.
     *
     * @param integer $capability
     * @throws InvalidCapabilityException
     */
    public function disallowMembersTo($capability)
    {
        Capability::validate($capability);
        $this->member_capabilities &= ~$capability;
        $this->save();
    }

    /**
     * Remove a capability from admins of this group.
     *
     * @param integer $capability
     * @throws InvalidCapabilityException
     */
    public function disallowAdminsTo($capability)
    {
        Capability::validate($capability);
        $this->admin_capabilities &= ~$capability;
        $this->save();
    }

}
