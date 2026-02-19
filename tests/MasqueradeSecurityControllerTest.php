<?php

declare(strict_types=1);

namespace DHensby\SilverStripeMasquerade\Test;

use SilverStripe\Control\Director;
use SilverStripe\Security\Member;
use SilverStripe\Dev\FunctionalTest;
use SilverStripe\Security\Security;

final class MasqueradeSecurityControllerTest extends FunctionalTest
{

    protected static $fixture_file = 'MasqueradeMemberTest.yml';

    public function testLogout(): void
    {
        $this->markTestSkipped('not currently working');

        $this->logInWithPermission('ADMIN');
        $admin = Security::getCurrentUser();
        $member = $this->objFromFixture(Member::class, 'user');

        $this->session()->set('masqueradingAs', $member->ID);

        // make a new request allowing the session middleware to do its thing
        // for some reason this fails and the test does not continue
        $this->get(Director::makeRelative(Security::logout_url()), $this->session());

        $this->assertEquals($admin->ID, $this->session()->get('loggedInAs'));
        $this->assertEmpty($this->session()->get('masqueradingAs'));
    }
}
