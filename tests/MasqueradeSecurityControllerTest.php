<?php

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Control\Session;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Security;

class MasqueradeSecurityControllerTest extends SapphireTest {

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testLogout()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture('Member', 'user');

        $member->masquerade();

        $this->assertEquals($member->ID, Security::getCurrentUser()->ID);
        $sc = new MasqueradeSecurityController();
        //$sc->init();
        $sc->logout(false);

        $this->assertEquals($admin->ID, Session::get('loggedInAs'));

    }

}
