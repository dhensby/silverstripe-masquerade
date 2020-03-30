<?php

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Security\Member;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\MemberAuthenticator\LogoutHandler;

class MasqueradeSecurityControllerTest extends FunctionalTest
{

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testLogout()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Member::currentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        $this->session()->set('masqueradingAs', $member->ID);

        // TODO: Need to trigger new request to allow middleware to do its thing

        $this->assertEquals($member->ID, $this->session()->get('loggedInAs'));
        $sc = new LogoutHandler();
        //$sc->init();
        $sc->logout(false);

        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));
    }
}
