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

    public function masquerade()
    {
        // don't use $member->logIn() because it triggers tracking and breaks remember me tokens, etc.
        $sessionData = Session::get_all();
        Session::clear_all();
        Session::set("loggedInAs", $this->getOwner()->ID);
        Session::set('Masquerade.Old', $sessionData);
        return $this->getOwner();
    }

}
