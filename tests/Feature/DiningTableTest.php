<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\DiningTable;
use App\Http\Controllers\DiningTableController;
use App\Models\User;
use App\Models\Role;

class DiningTableTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.dining_tables.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.dining_tables.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new diningtable.
     */
    public function test_store_creates_new_dining_table(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.dining_tables.store'), $data);

        $response->assertRedirect(route('admin.dining_tables.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dining_tables', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.dining_tables.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays diningtable details.
     */
    public function test_show_page_displays_dining_table_details(): void
    {
        $diningTable = DiningTable::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.dining_tables.show', $diningTable));

        $response->assertStatus(200);
        $response->assertViewHas('diningTable');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $diningTable = DiningTable::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.dining_tables.edit', $diningTable));

        $response->assertStatus(200);
        $response->assertViewHas('diningTable');
    }

    /**
     * Test update method updates diningtable.
     */
    public function test_update_modifies_dining_table(): void
    {
        $diningTable = DiningTable::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.dining_tables.update', $diningTable), $data);

        $response->assertRedirect(route('admin.dining_tables.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('dining_tables', array_merge(
            ['id' => $diningTable->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $diningTable = DiningTable::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.dining_tables.update', $diningTable), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes diningtable.
     */
    public function test_destroy_deletes_dining_table(): void
    {
        $diningTable = DiningTable::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.dining_tables.destroy', $diningTable));

        $response->assertRedirect(route('admin.dining_tables.index'));
        $response->assertSessionHas('success');

        // DiningTable uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('dining_tables', ['id' => $diningTable->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.dining_tables.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a diningtable.
     */
    protected function getValidCreateData(): array
    {
        return [
            'number' => 'Test Value',
            'capacity' => 1,
            'status' => 'Test Value',
            'show' => false,
        ];
    }

    /**
     * Get valid data for updating a diningtable.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'number' => 'Updated Test Value',
            'capacity' => 2,
            'status' => 'Updated Test Value',
            'show' => true,
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
