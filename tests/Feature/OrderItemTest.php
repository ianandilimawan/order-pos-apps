<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\OrderItem;
use App\Http\Controllers\OrderItemController;
use App\Models\User;
use App\Models\Role;

class OrderItemTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.order_items.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.order_items.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new orderitem.
     */
    public function test_store_creates_new_order_item(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.order_items.store'), $data);

        $response->assertRedirect(route('admin.order_items.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_items', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.order_items.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays orderitem details.
     */
    public function test_show_page_displays_order_item_details(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.order_items.show', $orderItem));

        $response->assertStatus(200);
        $response->assertViewHas('orderItem');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.order_items.edit', $orderItem));

        $response->assertStatus(200);
        $response->assertViewHas('orderItem');
    }

    /**
     * Test update method updates orderitem.
     */
    public function test_update_modifies_order_item(): void
    {
        $orderItem = OrderItem::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.order_items.update', $orderItem), $data);

        $response->assertRedirect(route('admin.order_items.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('order_items', array_merge(
            ['id' => $orderItem->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.order_items.update', $orderItem), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes orderitem.
     */
    public function test_destroy_deletes_order_item(): void
    {
        $orderItem = OrderItem::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.order_items.destroy', $orderItem));

        $response->assertRedirect(route('admin.order_items.index'));
        $response->assertSessionHas('success');

        // OrderItem uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('order_items', ['id' => $orderItem->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.order_items.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a orderitem.
     */
    protected function getValidCreateData(): array
    {
        return [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 100,
            'price' => 1,
            'subtotal' => 1,
        ];
    }

    /**
     * Get valid data for updating a orderitem.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 200,
            'price' => 2,
            'subtotal' => 2,
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
