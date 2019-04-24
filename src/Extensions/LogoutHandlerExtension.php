<?php

namespace DHensby\SilverStripeMasquerade\Extensions;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Core\Extension;
use SilverStripe\Security\Security;

class LogoutHandlerExtension extends Extension
{
    public function beforeLogout()
    {
        /** @var HTTPRequest $request */
        $request = $this->owner->getRequest();
        $session = $request->getSession();

        // If we're currently masquerading, we only want to stop masquerading, not *actually* log out
        if ($session->get('masqueradingAs')) {
            $session->clear('masqueradingAs');
            $session->save($request);
            $response = $this->redirectAfterLogout();
            $response->output();
            exit;
        }
    }

    /**
     * Copied verbatim from LogoutHandler::redirectAfterLogout()
     *
     * @return HTTPResponse
     */
    protected function redirectAfterLogout()
    {
        $backURL = $this->owner->getBackURL();
        if ($backURL) {
            return $this->owner->redirect($backURL);
        }

        $link = Security::config()->get('login_url');
        $referer = $this->owner->getReturnReferer();
        if ($referer) {
            $link = Controller::join_links($link, '?' . http_build_query([
                'BackURL' => Director::makeRelative($referer)
            ]));
        }

        return $this->owner->redirect($link);
    }
}
