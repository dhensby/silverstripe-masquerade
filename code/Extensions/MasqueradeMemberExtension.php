<?php

/**
 * Class MasqueradeMemberExtension
 *
 * The masquerade decorator for Member objects
 */
class MasqueradeMemberExtension extends DataExtension
{

    public function canMasquerade($member = null)
    {
        if (!$member) {
            $member = Member::currentUser();
        }
        elseif (is_numeric($member)) {
            $member = Member::get()->byID($member);
        }
        if ($member && $member->ID == $this->getOwner()->ID) {
            return false;
        }
        return Permission::check('ADMIN', 'any', $member);
    }

}
