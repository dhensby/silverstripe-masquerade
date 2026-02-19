<?php

declare(strict_types=1);

namespace DHensby\SilverStripeMasquerade\Extensions;

use DHensby\SilverStripeMasquerade\Forms\GridField\GridFieldMasqueradeButton;
use SilverStripe\Core\Extension;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;

class SecurityAdminExtension extends Extension
{
    public function updateEditForm(Form $form): void
    {
        /** @var GridField $gridField */
        $gridField = $form->Fields()->dataFieldByName('users');
        if (!$gridField) {
            return;
        }

        $gridField->getConfig()
            ->addComponent(new GridFieldMasqueradeButton());
    }
}
