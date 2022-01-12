<?php

namespace Tests\Unit;

use App\Supports\Md5Hashing;
use Illuminate\Contracts\Hashing\Hasher;
use PHPUnit\Framework\TestCase;
use Exception;

class Md5HashingTest extends TestCase
{
    /** @var Hasher */
    protected $hash;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hash = new Md5Hashing();
    }

    public function testFailedInfo()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf(
            'This password md5 require integer value. [%s].',
            Md5Hashing::class
        ));

        $this->hash->info($this->hash->make('random-hash'));
    }

    public function testSuccessInfo()
    {
        $result = $this->hash->info($this->hash->make(123456));

        $this->assertEquals(
            $result,
            [
                "algo" => "md5",
                "algoName" => "md5",
                "options" => []
            ]
        );
    }

    public function testFailMake()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf(
            'This password md5 require integer value. [%s].',
            Md5Hashing::class
        ));

        $this->hash->info($this->hash->make('random-hash'));
    }

    public function testMake()
    {
        $result = $this->hash->make(123456);

        $this->assertIsString($result);
    }

    public function testCheck()
    {
        $value = $this->hash->make(123456);
        $this->assertNotSame(123456, $value);
        $this->assertTrue($this->hash->check(123456, $value));
    }

    public function testNeedRehash()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(sprintf(
            'This password md5 does not implement needsRehash. [%s].',
            Md5Hashing::class
        ));

        $this->hash->needsRehash('random-hash');
    }
}
