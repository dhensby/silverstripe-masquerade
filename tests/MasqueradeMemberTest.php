<?php

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Control\Controller;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Security\Security;

class MasqueradeMemberTest extends SapphireTest {

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testCanMasquerade()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture('Member', 'user');

        //added function correctly
        $this->assertTrue($member->hasMethod('canMasquerade'));

        // admin can masquerade as another
        $this->assertTrue($member->canMasquerade());

        //admin can't masquerade as themselves
        $this->assertFalse($admin->canMasquerade());

        $admin->logOut();

        // no logged in user can't masquerade
        $this->assertFalse($member->canMasquerade());

        //admin can masquerade
        $this->assertTrue($member->canMasquerade($admin));

        // member cannot masquerade as themeselves
        $this->assertFalse($member->canMasquerade($member));

        Security::setCurrentUser($member);
        // member can't masquerade as themselves
        $this->assertFalse($member->canMasquerade());

        // member can't masquerade as an admin
        $this->assertFalse($admin->canMasquerade());
    }

    public function testMasquerade()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture('Member', 'user');

        $session = Controller::curr()->getRequest()->getSession();

        $this->assertEquals($admin->ID, $session->get('loggedInAs'));

        $member->masquerade();

        $this->assertEquals($member->ID, $session->get('loggedInAs'));
        $this->assertEquals($admin->ID, $session->get('masqueradingAs'));
    }

}
