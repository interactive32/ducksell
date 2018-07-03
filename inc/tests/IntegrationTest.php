<?php

class IntegrationTest extends TestCase {


    public function testLogin()
    {
        $this
            ->visit('/auth/login')
            ->type('admin@example.com', 'email')
            ->type('admin123', 'password')
            ->press('Sign in')
            ->see('Dashboard')
            ;
    }

    public function testLogout()
    {
        $this
            ->visit('/auth/logout')
            ->see('Sign in')
            ;
    }


}
