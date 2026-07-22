<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OrderCharge;
use App\Http\Controllers\OrderChargeController;
use App\Models\User;
use App\Models\Role;

class OrderChargeTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.order_charges.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.order_charges.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new ordercharge.
     */
    public function test_store_creates_new_order_charge(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.order_charges.store'), $data);

        $response->assertRedirect(route('admin.order_charges.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_charges', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.order_charges.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays ordercharge details.
     */
    public function test_show_page_displays_order_charge_details(): void
    {
        $orderCharge = OrderCharge::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.order_charges.show', $orderCharge));

        $response->assertStatus(200);
        $response->assertViewHas('orderCharge');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $orderCharge = OrderCharge::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.order_charges.edit', $orderCharge));

        $response->assertStatus(200);
        $response->assertViewHas('orderCharge');
    }

    /**
     * Test update method updates ordercharge.
     */
    public function test_update_modifies_order_charge(): void
    {
        $orderCharge = OrderCharge::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.order_charges.update', $orderCharge), $data);

        $response->assertRedirect(route('admin.order_charges.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_charges', array_merge(
            ['id' => $orderCharge->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $orderCharge = OrderCharge::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.order_charges.update', $orderCharge), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes ordercharge.
     */
    public function test_destroy_deletes_order_charge(): void
    {
        $orderCharge = OrderCharge::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.order_charges.destroy', $orderCharge));

        $response->assertRedirect(route('admin.order_charges.index'));
        $response->assertSessionHas('success');

        // OrderCharge uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('order_charges', ['id' => $orderCharge->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.order_charges.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a ordercharge.
     */
    protected function getValidCreateData(): array
    {
        return [
            'order_id' => 1,
            'charge_name' => 'Test Order Charge',
            'charge_type' => 'Test Value',
            'charge_rate' => 1,
            'charge_amount' => 1,
        ];
    }

    /**
     * Get valid data for updating a ordercharge.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'order_id' => 1,
            'charge_name' => 'Updated Test Order Charge',
            'charge_type' => 'Updated Test Value',
            'charge_rate' => 2,
            'charge_amount' => 2,
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
