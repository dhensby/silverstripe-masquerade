<?php

class MasqueradeSecurityAdminExtension extends Extension {

    public function updateEditForm($form)
    {
        $gridField = $form->Fields()->dataFieldByName('Members');

        $gridField->getConfig()
            ->addComponent(
                new GridFieldMasqueradeButton()
            )
            ->getComponentByType('GridFieldDetailForm')
                ->setItemRequestClass('MasqueradeGridFieldDetailForm_ItemRequest')
        ;
    }

}
