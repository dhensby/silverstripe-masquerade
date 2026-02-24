<?php

declare(strict_types=1);

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

final class MasqueradeMemberTest extends FunctionalTest
{

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testCanMasquerade(): void
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        //added function correctly
        $this->assertTrue($member->hasMethod('canMasquerade'));

        // admin can masquerade as another
        $this->assertTrue($member->canMasquerade());

        //admin can't masquerade as themselves
        $this->assertFalse($admin->canMasquerade());

        Security::setCurrentUser();

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

    public function testMasquerade(): void
    {
        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));

        $this->session()->set('masqueradingAs', $member->ID);

        // make a new request allowing the session middleware to do its thing
        $this->get("/", $this->session());

        // we've been logged in as the user
        $this->assertEquals($member->ID, Security::getCurrentUser()->ID);

        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));
        $this->assertEquals($member->ID, $this->session()->get('masqueradingAs'));
    }
}
