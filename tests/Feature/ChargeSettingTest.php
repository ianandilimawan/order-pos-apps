<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ChargeSetting;
use App\Http\Controllers\ChargeSettingController;
use App\Models\User;
use App\Models\Role;

class ChargeSettingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'Full system access',
            'is_active' => true,
        ]);

        // Create a test user for authentication with admin role
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Assign admin role to user
        $this->user->roles()->attach($adminRole->id);
    }

    /**
     * Test index page is accessible.
     */
    public function test_index_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.charge_settings.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.charge_settings.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new chargesetting.
     */
    public function test_store_creates_new_charge_setting(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.charge_settings.store'), $data);

        $response->assertRedirect(route('admin.charge_settings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('charge_settings', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.charge_settings.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays chargesetting details.
     */
    public function test_show_page_displays_charge_setting_details(): void
    {
        $chargeSetting = ChargeSetting::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.charge_settings.show', $chargeSetting));

        $response->assertStatus(200);
        $response->assertViewHas('chargeSetting');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $chargeSetting = ChargeSetting::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.charge_settings.edit', $chargeSetting));

        $response->assertStatus(200);
        $response->assertViewHas('chargeSetting');
    }

    /**
     * Test update method updates chargesetting.
     */
    public function test_update_modifies_charge_setting(): void
    {
        $chargeSetting = ChargeSetting::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.charge_settings.update', $chargeSetting), $data);

        $response->assertRedirect(route('admin.charge_settings.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('charge_settings', array_merge(
            ['id' => $chargeSetting->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $chargeSetting = ChargeSetting::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.charge_settings.update', $chargeSetting), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes chargesetting.
     */
    public function test_destroy_deletes_charge_setting(): void
    {
        $chargeSetting = ChargeSetting::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.charge_settings.destroy', $chargeSetting));

        $response->assertRedirect(route('admin.charge_settings.index'));
        $response->assertSessionHas('success');

        // ChargeSetting uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('charge_settings', ['id' => $chargeSetting->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.charge_settings.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a chargesetting.
     */
    protected function getValidCreateData(): array
    {
        return [
            'name' => 'Test Charge Setting',
            'type' => 'Test Value',
            'value' => 1,
            'applies_to' => 'Test Value',
            'is_active' => false,
            'sort' => 1,
        ];
    }

    /**
     * Get valid data for updating a chargesetting.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'name' => 'Updated Test Charge Setting',
            'type' => 'Updated Test Value',
            'value' => 2,
            'applies_to' => 'Updated Test Value',
            'is_active' => true,
            'sort' => 2,
        ];
    }

    /**
     * Get data for database assertion (excluding non-database fields).
     */
    protected function getDatabaseAssertionData(array $data): array
    {
        // Remove fields that are not stored in database (e.g., password confirmation)
        $excludedFields = ['password_confirmation', '_token', '_method'];

        return array_filter($data, function ($key) use ($excludedFields) {
            return !in_array($key, $excludedFields);
        }, ARRAY_FILTER_USE_KEY);
    }
}
