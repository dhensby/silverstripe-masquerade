<?php

class GridFieldMasqueradeButton implements GridField_ColumnProvider
{

    /**
     * @param GridField $gridField
     * @param array $columns
     */
    public function augmentColumns($gridField, &$columns)
    {
        if (!in_array('Actions', $columns)) {
            $columns[] = 'Actions';
        }
    }

    /**
     * Return any special attributes that will be used for FormField::create_tag()
     *
     * @param GridField $gridField
     * @param DataObject $record
     * @param string $columnName
     * @return array
     */
    public function getColumnAttributes($gridField, $record, $columnName)
    {
        return array('class' => 'col-buttons');
    }

    /**
     * Add the title
     *
     * @param GridField $gridField
     * @param string $columnName
     * @return array
     */
    public function getColumnMetadata($gridField, $columnName)
    {
        if ($columnName == 'Actions') {
            return array('title' => '');
        }
    }

    /**
     * Which columns are handled by this component
     *
     * @param GridField $gridField
     * @return array
     */
    public function getColumnsHandled($gridField)
    {
        return array('Actions');
    }

    /**
     * Which GridField actions are this component handling.
     *
     * @return array
     */
    public function getActions()
    {
        return array('masquerade');
    }

    /**
     * @param GridField $gridField
     * @param DataObject $record
     * @param string $columnName
     *
     * @return string - the HTML for the column
     */
    public function getColumnContent($gridField, $record, $columnName)
    {
        if (!$record instanceof Member || !$record->canMasquerade()) {
            return;
        }
        // No permission checks, handled through GridFieldDetailForm,
        // which can make the form readonly if no edit permissions are available.

        $data = new ArrayData(array(
            'Link' => Controller::join_links($gridField->Link('item'), $record->ID, 'masquerade')
        ));

        return $data->renderWith('GridFieldMasqueradeButton');
    }


}
