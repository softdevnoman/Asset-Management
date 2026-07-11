<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use App\Mail\WelcomeOrganizationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrganizationRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_organization_registration_and_otp_verification_workflow()
    {
        Mail::fake();

        // 1. Organization Registers
        $response = $this->post('/register', [
            'company_name' => 'Test Enterprise Corp',
            'company_email' => 'contact@testenterprise.com',
            'name' => 'Jane Admin',
            'email' => 'jane@testenterprise.com',
            'password' => 'secretPassword123!',
            'password_confirmation' => 'secretPassword123!',
            'terms' => 'on',
        ]);

        $response->dumpSession();
        $response->dump();

        // 2. Validate Request & Account / Admin User Created
        $account = Account::where('company_email', 'contact@testenterprise.com')->first();
        $this->assertNotNull($account);
        $this->assertEquals('pending', $account->status);
        $this->assertEquals('Test Enterprise Corp', $account->company_name);

        $user = User::where('email', 'jane@testenterprise.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals($account->id, $user->account_id);
        $this->assertNotNull($user->otp);
        $this->assertNotNull($user->verification_token);
        $this->assertNull($user->email_verified_at);

        // 3. Check Welcome Email Sent
        Mail::assertSent(WelcomeOrganizationMail::class, function ($mail) use ($user, $account) {
            return $mail->user->id === $user->id && $mail->account->id === $account->id;
        });

        // 4. Check Redirect to OTP Verification Screen
        $response->assertRedirect(route('verification.otp.form', ['email' => $user->email]));

        // 5. Submit valid 6-digit OTP
        $verifyResponse = $this->post('/verify-otp', [
            'email' => $user->email,
            'otp' => $user->otp,
        ]);

        // 6. Verify User & Organization activated & logged in
        $verifyResponse->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $account->refresh();

        $this->assertNotNull($user->email_verified_at);
        $this->assertNull($user->otp);
        $this->assertNull($user->verification_token);
        $this->assertEquals('active', $account->status);
    }
}
