<?php

declare(strict_types=1);

namespace DHensby\SilverStripeMasquerade\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;

class MemberExtension extends Extension
{
    /**
     * @param mixed $member
     * @return bool|int
     */
    public function canMasquerade($member = null)
    {
        if (!$member) {
            $member = Security::getCurrentUser();
        } elseif (is_numeric($member)) {
            $member = Member::get()->byID($member);
        }

        if ($member && $member->ID == $this->getOwner()->ID) {
            return false;
        }

        return Permission::check('ADMIN', 'any', $member);
    }
}
