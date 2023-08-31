<?php

namespace DHensby\SilverStripeMasquerade\Extensions;

use DHensby\SilverStripeMasquerade\Forms\GridField\GridFieldMasqueradeButton;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;

class SecurityAdminExtension extends Extension
{
    public function updateEditForm(Form $form)
    {
        /** @var GridField $gridField */
        $gridField = $form->Fields()->dataFieldByName('users');
        $gridField->getConfig()
            ->addComponent(new GridFieldMasqueradeButton());
    }
}
