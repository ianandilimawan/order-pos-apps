<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CashOpname;
use App\Http\Controllers\CashOpnameController;
use App\Models\User;
use App\Models\Role;

class CashOpnameTest extends TestCase
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
        $response = $this->actingAs($this->user)->get(route('admin.cash_opnames.index'));

        $response->assertStatus(200);
    }

    /**
     * Test create page is accessible.
     */
    public function test_create_page_is_accessible(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.cash_opnames.create'));

        $response->assertStatus(200);
    }

    /**
     * Test store method creates a new cashopname.
     */
    public function test_store_creates_new_cash_opname(): void
    {
        $data = $this->getValidCreateData();

        $response = $this->actingAs($this->user)->post(route('admin.cash_opnames.store'), $data);

        $response->assertRedirect(route('admin.cash_opnames.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_opnames', $this->getDatabaseAssertionData($data));
    }

    /**
     * Test store method validates required fields.
     */
    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.cash_opnames.store'), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test show page displays cashopname details.
     */
    public function test_show_page_displays_cash_opname_details(): void
    {
        $cashOpname = CashOpname::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_opnames.show', $cashOpname));

        $response->assertStatus(200);
        $response->assertViewHas('cashOpname');
    }

    /**
     * Test edit page is accessible.
     */
    public function test_edit_page_is_accessible(): void
    {
        $cashOpname = CashOpname::factory()->create();

        $response = $this->actingAs($this->user)->get(route('admin.cash_opnames.edit', $cashOpname));

        $response->assertStatus(200);
        $response->assertViewHas('cashOpname');
    }

    /**
     * Test update method updates cashopname.
     */
    public function test_update_modifies_cash_opname(): void
    {
        $cashOpname = CashOpname::factory()->create();
        $data = $this->getValidUpdateData();

        $response = $this->actingAs($this->user)->put(route('admin.cash_opnames.update', $cashOpname), $data);

        $response->assertRedirect(route('admin.cash_opnames.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cash_opnames', array_merge(
            ['id' => $cashOpname->id],
            $this->getDatabaseAssertionData($data)
        ));
    }

    /**
     * Test update method validates required fields.
     */
    public function test_update_validates_required_fields(): void
    {
        $cashOpname = CashOpname::factory()->create();

        $response = $this->actingAs($this->user)->put(route('admin.cash_opnames.update', $cashOpname), []);

        $response->assertSessionHasErrors();
    }

    /**
     * Test destroy method deletes cashopname.
     */
    public function test_destroy_deletes_cash_opname(): void
    {
        $cashOpname = CashOpname::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('admin.cash_opnames.destroy', $cashOpname));

        $response->assertRedirect(route('admin.cash_opnames.index'));
        $response->assertSessionHas('success');

        // CashOpname uses SoftDeletes, so check that deleted_at is set
        $this->assertSoftDeleted('cash_opnames', ['id' => $cashOpname->id]);
    }

    /**
     * Test unauthorized access is denied.
     */
    public function test_unauthorized_access_is_denied(): void
    {
        $response = $this->get(route('admin.cash_opnames.index'));

        $response->assertRedirect(route('admin.login'));
    }

    /**
     * Get valid data for creating a cashopname.
     */
    protected function getValidCreateData(): array
    {
        return [
            'user_id' => 'Test Value',
            'opname_date' => '2026-07-22',
            'expected_cash' => 1,
            'actual_cash' => 1,
            'expected_qris' => 1,
            'actual_qris' => 1,
            'expected_transfer' => 1,
            'actual_transfer' => 1,
            'difference' => 1,
            'status' => 'Test Value',
            'notes' => 'Test description',
        ];
    }

    /**
     * Get valid data for updating a cashopname.
     */
    protected function getValidUpdateData(): array
    {
        return [
            'user_id' => 'Updated Test Value',
            'opname_date' => '2026-07-22',
            'expected_cash' => 2,
            'actual_cash' => 2,
            'expected_qris' => 2,
            'actual_qris' => 2,
            'expected_transfer' => 2,
            'actual_transfer' => 2,
            'difference' => 2,
            'status' => 'Updated Test Value',
            'notes' => 'Updated Test description',
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
