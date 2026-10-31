<?php

namespace Tests\Unit;

use App\Services\OtpCooldown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class OtpCooldownTest extends TestCase
{
    public function test_duplicate_requests_are_blocked_across_normalized_email_addresses(): void
    {
        config(['cache.default' => 'array']);
        Cache::flush();
        OtpCooldown::claim(' Person@Example.com ');
        $this->assertSame(60, OtpCooldown::remaining('person@example.com'));
        OtpCooldown::claim('other@example.com');

        $this->expectException(ValidationException::class);
        OtpCooldown::claim('person@example.com');
    }

    public function test_requests_are_allowed_again_after_sixty_seconds(): void
    {
        config(['cache.default' => 'array']);
        Cache::flush();
        OtpCooldown::claim('person@example.com');
        $this->travel(30)->seconds();
        $this->assertSame(30, OtpCooldown::remaining('person@example.com'));
        $this->travel(31)->seconds();
        $this->assertSame(0, OtpCooldown::remaining('person@example.com'));
        OtpCooldown::claim('person@example.com');
        $this->assertSame(60, OtpCooldown::remaining('person@example.com'));
        $this->travelBack();
    }
}
