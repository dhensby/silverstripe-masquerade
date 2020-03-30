<?php

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;

class MasqueradeMemberTest extends FunctionalTest
{

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testCanMasquerade()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Member::currentUser();
        $member = $this->objFromFixture(Member::class, 'user');

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

        $member->logIn();
        // member can't masquerade as themselves
        $this->assertFalse($member->canMasquerade());

        // member can't masquerade as an admin
        $this->assertFalse($admin->canMasquerade());
    }

    public function testMasquerade()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Member::currentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));

        $this->session()->set('masqueradingAs', $member->ID);
        // TODO: this should make a new request allowing the session middleware to do its thing
        $this->get("/", $this->session());

        $this->assertEquals($member->ID, $this->session()->get('loggedInAs'));
        $this->assertEquals($admin->ID, $this->session()->get('Masquerade.Old.loggedInAs'));
    }
}
