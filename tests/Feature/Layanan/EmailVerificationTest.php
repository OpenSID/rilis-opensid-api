<?php

namespace Tests\Feature\Api\Auth;

use App\Models\PendudukMandiri;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    public function test_email_can_be_verified()
    {
        $user = PendudukMandiri::where('id_pend', 20)->first();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'jwt.verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id_pend, 'hash' => sha1($user->email), 'token' => auth('jwt')->tokenById($user->id_pend)]
        );

        $this->actingAs($user, 'jwt')->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_hash()
    {
        $user = PendudukMandiri::where('id_pend', 2)->first();

        $verificationUrl = URL::temporarySignedRoute(
            'jwt.verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id_pend, 'hash' => sha1('wrong-email'), 'token' => auth('jwt')->tokenById($user->id_pend)]
        );

        $this->actingAs($user, 'jwt')->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
