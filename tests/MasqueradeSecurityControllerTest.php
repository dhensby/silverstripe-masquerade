<?php

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Security\Member;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Security;

class MasqueradeSecurityControllerTest extends FunctionalTest
{

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testLogout()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        $this->session()->set('masqueradingAs', $member->ID);

        // TODO: Need to trigger new request to allow middleware to do its thing

        $this->assertEquals($member->ID, $this->session()->get('loggedInAs'));
        Security::setCurrentUser(null);
        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));
    }
}
