<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create necessary tables
        $this->artisan('migrate:fresh');
    }

    #[Test]
    public function it_has_fillable_attributes()
    {
        $fillable = [
            'name',
            'email',
            'password',
            'two_factor_secret',
            'two_factor_enabled',
            'two_factor_recovery_codes',
            'storage_limit'
        ];
        $user = new User();

        $this->assertEquals($fillable, $user->getFillable());
    }

    #[Test]
    public function it_has_hidden_attributes()
    {
        $hidden = [
            'password',
            'remember_token',
            'two_factor_secret',
            'two_factor_recovery_codes'
        ];
        $user = new User();

        $this->assertEquals($hidden, $user->getHidden());
    }

    #[Test]
    public function it_has_correct_casts()
    {
        $user = new User();
        $casts = [
            'id' => 'int',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
        ];

        $this->assertEquals($casts, $user->getCasts());
    }

    #[Test]
    public function it_hashes_password_automatically()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $this->assertTrue(Hash::check('password', $user->password));
    }

    #[Test]
    public function it_has_documents_relationship()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Document::class, $user->documents->first());
        $this->assertEquals($document->id, $user->documents->first()->id);
    }

    #[Test]
    public function it_has_shared_documents_relationship()
    {
        // Create the document_shares table if it doesn't exist
        if (!\Schema::hasTable('document_shares')) {
            \Schema::create('document_shares', function ($table) {
                $table->id();
                $table->foreignId('document_id')->constrained();
                $table->foreignId('user_id')->constrained();
                $table->string('permission');
                $table->timestamps();
            });
        }

        $user = User::factory()->create();
        $document = Document::factory()->create();
        
        $user->sharedDocuments()->attach($document, [
            'permission' => 'view',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->assertInstanceOf(Document::class, $user->sharedDocuments->first());
        $this->assertEquals($document->id, $user->sharedDocuments->first()->id);
        $this->assertEquals('view', $user->sharedDocuments->first()->pivot->permission);
    }

    #[Test]
    public function it_can_check_if_it_can_be_impersonated()
    {
        // Create roles
        $role = Role::create(['name' => 'super-admin']);
        $permission = Permission::create(['name' => 'impersonate users']);
        $role->givePermissionTo($permission);

        $admin = User::factory()->create();
        $admin->assignRole('super-admin');

        $regularUser = User::factory()->create();

        // Super admin can impersonate regular user
        $this->actingAs($admin);
        $this->assertTrue($regularUser->canBeImpersonated());

        // Regular user cannot impersonate admin
        $this->actingAs($regularUser);
        $this->assertFalse($admin->canBeImpersonated());

        // Admin can impersonate another admin (edge case)
        $anotherAdmin = User::factory()->create();
        $anotherAdmin->assignRole('super-admin');
        $this->actingAs($admin);
        $this->assertTrue($anotherAdmin->canBeImpersonated());
    }

    #[Test]
    public function it_can_check_if_it_can_impersonate()
    {
        $permission = Permission::create(['name' => 'impersonate users']);
        
        $userWithPermission = User::factory()->create();
        $userWithPermission->givePermissionTo('impersonate users');
        
        $userWithoutPermission = User::factory()->create();

        $this->assertTrue($userWithPermission->canImpersonate());
        $this->assertFalse($userWithoutPermission->canImpersonate());
    }

    #[Test]
    public function it_has_roles_trait()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'test-role']);
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('test-role'));
    }

    #[Test]
    public function it_has_logs_activity_trait()
    {
        $user = User::factory()->create();
        
        // Check if the trait is used by checking for a method it should add
        $this->assertTrue(method_exists($user, 'enableLogging') || 
             method_exists($user, 'activities') ||
             method_exists($user, 'logActivity'));
    }
}