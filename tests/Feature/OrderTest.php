<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Order;
use App\Http\Controllers\OrderController;
use App\Models\User;
use App\Models\Role;

class OrderTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.orders.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.orders.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new order.
     */
    public function test_store_creates_new_order(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.orders.store'), $data);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.orders.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays order details.
     */
    public function test_show_page_displays_order_details(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
        $response->assertViewHas('order');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.orders.edit', $order));

        $response->assertStatus(200);
        $response->assertViewHas('order');
    }

    /**
     * Test update method updates order.
     */
    public function test_update_modifies_order(): void
    {
        $order = Order::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.orders.update', $order), $data);

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', array_merge(
            ['id' => $order->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.orders.update', $order), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes order.
     */
    public function test_destroy_deletes_order(): void
    {
        $order = Order::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.orders.destroy', $order));

        $response->assertRedirect(route('admin.orders.index'));
        $response->assertSessionHas('success');

        // Order uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('orders', ['id' => $order->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.orders.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a order.
     */
    protected function getValidCreateData(): array
    {
        return [
            'order_number' => 'Test Value',
            'order_type' => 'Test Value',
            'status' => 'Test Value',
            'payment_status' => 'Test Value',
            'subtotal' => 1,
            'total' => 1,
        ];
    }

    /**
     * Get valid data for updating a order.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'order_number' => 'Updated Test Value',
            'order_type' => 'Updated Test Value',
            'status' => 'Updated Test Value',
            'payment_status' => 'Updated Test Value',
            'subtotal' => 2,
            'total' => 2,
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
