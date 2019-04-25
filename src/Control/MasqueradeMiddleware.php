<?php

namespace DHensby\SilverStripeMasquerade\Control;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

class MasqueradeMiddleware implements HTTPMiddleware
{
    public function process(HTTPRequest $request, callable $delegate)
    {
        $session = $request->getSession();
        if ($session->isStarted() && $id = $session->get('masqueradingAs')) {
            $member = Member::get()->byID($id);
            if ($member) {
                Security::setCurrentUser($member);
            }
        }

        return $delegate($request);
    }
}
