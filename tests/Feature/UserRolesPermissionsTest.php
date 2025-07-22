<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use PHPUnit\Framework\Attributes\Test;

class UserRolesPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    #[Test]
    public function admin_user_should_have_admin_role_and_all_permissions()
    {
        $admin = User::where('email', 'admin@example.com')->first();

        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->hasPermissionTo('impersonate users'));
        $this->assertTrue($admin->canImpersonate());
    }

    #[Test]
    public function regular_user_should_have_user_role_and_limited_permissions()
    {
        $user = User::where('email', 'user@example.com')->first();

        $this->assertTrue($user->hasRole('user'));
        $this->assertTrue($user->hasPermissionTo('view users'));
        $this->assertFalse($user->canImpersonate());
    }

    #[Test]
    public function regular_user_can_be_impersonated_by_admin()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $user = User::where('email', 'user@example.com')->first();

        $this->assertTrue($user->canBeImpersonated($admin));

    }

    #[Test]
	public function admin_user_should_not_be_impersonated_by_regular_user()
	{
	    $admin = User::where('email', 'admin@example.com')->first();
	    $user = User::where('email', 'user@example.com')->first();

	    $this->assertFalse($admin->canBeImpersonated($user));
	}


}
