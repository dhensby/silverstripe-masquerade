<?php

class MasqueradeGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {

    private static $allowed_actions = array(
        'masquerade',
    );

    public function masquerade()
    {
        $member = $this->hasMethod('getRecord') ? $this->getRecord() : $this->record;
        if (!$member->hasMethod('canMasquerade') || !$member->hasMethod('masquerade') || !$member->canMasquerade()) {
            Security::permissionFailure($this->getController());
            return;
        }
        $member->masquerade();
        $this->getController()->redirect(Director::absoluteBaseURL());
    }

}
