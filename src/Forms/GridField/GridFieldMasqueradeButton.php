<?php

namespace DHensby\SilverStripeMasquerade\Forms\GridField;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridField_ActionMenuItem;
use SilverStripe\Forms\GridField\GridField_ActionMenuLink;
use SilverStripe\Forms\GridField\GridField_ColumnProvider;
use SilverStripe\Forms\GridField\GridField_URLHandler;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\ORM\ValidationException;
use SilverStripe\Security\Member;
use SilverStripe\View\ArrayData;
use SilverStripe\View\SSViewer;

class GridFieldMasqueradeButton implements GridField_ColumnProvider, GridField_ActionMenuLink, GridField_URLHandler
{
    public function getTitle($gridField, $record, $columnName)
    {
        return _t(__CLASS__ . '.MASQUERADE', 'Masquerade');
    }

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
        return ['class' => 'grid-field__col-compact'];
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
            return ['title' => ''];
        }
        return [];
    }

    /**
     * Which columns are handled by this component
     *
     * @param GridField $gridField
     * @return array
     */
    public function getColumnsHandled($gridField)
    {
        return ['Actions'];
    }

    /**
     * Which GridField actions are this component handling.
     *
     * @param $gridField
     * @return array
     */
    public function getActions($gridField)
    {
        return ['masquerade'];
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
        return $this->getMasqueradeAction($gridField, $record, $columnName);
    }

    /**
     * @param $gridField
     * @param $record
     * @param $columnName
     * @return DBHTMLText|null
     */
    protected function getMasqueradeAction($gridField, $record, $columnName)
    {
        if (!$record instanceof Member || !$record->canMasquerade()) {
            return null;
        }

        $data = new ArrayData([
            'Link' => $this->getUrl($gridField, $record, $columnName),
            'ExtraClass' => 'grid-field__icon-action--hidden-on-hover font-icon-eye btn--icon-large action-menu--handled'
        ]);

        $template = SSViewer::get_templates_by_class($this, '', __CLASS__);
        return $data->renderWith($template);
    }

    public function getExtraData($gridField, $record, $columnName)
    {
        return [
            'classNames' => 'font-icon-eye action-detail'
        ];
    }

    public function getGroup($gridField, $record, $columnName)
    {
        $action = $this->getMasqueradeAction($gridField, $record, $columnName);
        return $action ? GridField_ActionMenuItem::DEFAULT_GROUP: null;
    }

    public function handleMasquerade(GridField $gridField, HTTPRequest $request)
    {
        /** @var DataObject $item */
        $item = $gridField->getList()->byID($request->param('ID'));
        if (!$item) {
            return;
        }

        if (!$item->canMasquerade()) {
            throw new ValidationException(
                _t(__CLASS__ . '.MasqueradePermissionsFailure', 'No masquerade permissions')
            );
        }

        if (Member::config()->get('session_regenerate_id') && !Director::is_cli() && !headers_sent()) {
            @session_regenerate_id(true);
        }

        $request->getSession()->set('masqueradingAs', $item->ID);

        $response = new HTTPResponse();
        $response->addHeader('X-Reload', true);
        $response->addHeader('X-ControllerURL', Director::absoluteBaseURL());
        return $response;
    }

    public function getUrl($gridField, $record, $columnName)
    {
        return Controller::join_links($gridField->Link('masquerade'), $record->ID);
    }

    public function getURLHandlers($gridField)
    {
        return ['masquerade/$ID' => 'handleMasquerade'];
    }
}
