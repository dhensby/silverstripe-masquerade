<?php

class MasqueradeSecurityControllerTest extends SapphireTest {

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testLogout()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Member::currentUser();
        $member = $this->objFromFixture('Member', 'user');

        $member->masquerade();

        $this->assertEquals($member->ID, Session::get('loggedInAs'));
        $sc = new MasqueradeSecurityController();
        //$sc->init();
        $sc->logout(false);

        $this->assertEquals($admin->ID, Session::get('loggedInAs'));

    }

}
