<?php

namespace App;

use App\Exceptions\InvalidCapabilityException;

/**
 * Defines capabilities that a member of a group might have. They are realized in form of a
 * bit-field that is stored in the \App\Group-class. Members of a group might have different
 * capabilities depending on their group-membership (administrator|member).
 */
final class Capability
{
    /**
     * Maximum valid capability
     */
    const MAX_CAPABILITY = 0x1111;
    /**
     * View every users first and last name.
     */
    const VIEW_USER_NAMES = (1 << 0);
    /**
     * View every users address data like e-mail,
     * private address, phone number etc.
     *
     * Implies VIEW_USER_NAMES.
     */
    const VIEW_USER_ADDRESS_DATA = (1 << 1) | Capability::VIEW_USER_NAMES;
    /**
     * View every users date of birth
     *
     * Implies VIEW_USER_NAMES.
     */
    const VIEW_USER_DATE_OF_BIRTH = (1 << 2) | Capability::VIEW_USER_NAMES;
    /**
     * View all additional user data that is specified via configuration as well as
     * their group memberships.
     *
     * Implies VIEW_USER_ADDRESS_DATA and VIEW_USER_DATE_OF_BIRTH.
     */
    const VIEW_USER_ATTRIBUTES = (1 << 3)
    | Capability::VIEW_USER_ADDRESS_DATA
    | Capability::VIEW_USER_DATE_OF_BIRTH;
    /**
     * Create, update and delete users.
     *
     * Implies VIEW_USER_ATTRIBUTES.
     */
    const MANAGE_USERS = (1 << 4) | Capability::VIEW_USER_ATTRIBUTES;

    /**
     * This class must not be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Check if capability $c is valid.
     *
     * @param integer $capability
     * @throws InvalidCapabilityException
     */
    public static function validate($capability)
    {
        if ($capability > Capability::MAX_CAPABILITY) {
            throw new InvalidCapabilityException();
        }
    }

}