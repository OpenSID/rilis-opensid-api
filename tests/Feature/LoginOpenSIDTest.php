<?php

namespace Tests\Feature;

use App\Libraries\OpenSID;
use Tests\TestCase;

class LoginOpenSIDTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_successed()
    {
        $username = 'admin';
        $password = 'Admin100%';
        $clientOpenSID = OpenSID::loginOpensid($password, $username);
        $this->assertIsObject($clientOpenSID);
        $this->assertInstanceOf('GuzzleHttp\Client', $clientOpenSID);
    }

    public function test_login_failed()
    {
        // set wrong password
        $username = 'admin';
        $password = 'Admin90000%';
        try {
            OpenSID::loginOpensid($password, $username);
        } catch (\Exception $e) {
            $this->assertIsObject($e);
            $this->assertInstanceOf('Exception', $e);
            $this->assertEquals('Gagal Login ke Server OpenSid', $e->getMessage());
        }
    }
}
