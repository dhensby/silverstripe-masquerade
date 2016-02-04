<?php

class MasqueradeMemberTest extends SapphireTest {

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testCanMasquerade()
    {
        $this->logInWithPermission('ADMIN');
        $admin = Member::currentUser();
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
        $member = $this->objFromFixture('Member', 'user');

        $this->assertEquals($admin->ID, Session::get('loggedInAs'));

        $member->masquerade();

        $this->assertEquals($member->ID, Session::get('loggedInAs'));
        $this->assertEquals($admin->ID, Session::get('Masquerade.Old.loggedInAs'));
    }

}
