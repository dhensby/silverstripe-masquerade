<?php

class MasqueradeGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {

    private static $allowed_actions = array(
        'masquerade',
    );

    public function masquerade()
    {
        $member = $this->getRecord();
        if (!$member instanceof Member || !$member->canMasquerade()) {
            Security::permissionFailure($this->getController());
            return;
        }
        // don't use $member->logIn() because it triggers tracking and breaks remember me tokens, etc.
        $sessionData = Session::get_all();
        Session::clear_all();
        Session::set("loggedInAs", $member->ID);
        Session::set('Masquerade.Old', $sessionData);
        $this->getController()->redirect(Director::absoluteBaseURL());
    }

}
