<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Crop;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FullSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_login_page()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee(__('app.name')); 
    }

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test Farmer',
            'email' => 'farmer@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'farmer',
            'terms' => 'on'
        ]);

        $response->assertRedirect('/complete-profile');
        $this->assertDatabaseHas('users', ['email' => 'farmer@test.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'password'),
            'profile_completed' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_farmer_can_access_dashboard()
    {
        $user = User::factory()->create(['role' => 'farmer', 'profile_completed' => true]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(__('app.name')); 
    }

    public function test_farmer_can_create_crop()
    {
        $user = User::factory()->create(['role' => 'farmer', 'profile_completed' => true]);

        $cropData = [
            'name' => 'Tomato',
            'type' => 'vegetables',
            'variety' => 'Cherry',
            'planting_date' => now()->subDays(10)->format('Y-m-d'),
            'field_name' => 'North Field',
            'area' => 100,
        ];

        $response = $this->actingAs($user)->post(route('crops.store'), $cropData);

        $response->assertRedirect(route('crops.index'));
        $this->assertDatabaseHas('crops', [
            'name' => 'Tomato',
            'user_id' => $user->id
        ]);
    }

    public function test_farmer_can_view_inventory()
    {
        $user = User::factory()->create(['role' => 'farmer', 'profile_completed' => true]);
        
        $response = $this->actingAs($user)->get(route('inventory.index'));
        $response->assertStatus(200);
    }

    public function test_farmer_can_add_inventory_item()
    {
        $user = User::factory()->create(['role' => 'farmer', 'profile_completed' => true]);

        $inventoryData = [
            'name' => 'Fertilizer A',
            'category' => 'fertilizers',
            'quantity_value' => 50,
            'unit' => 'kg',
            'notes' => 'Test notes'
        ];

        $response = $this->actingAs($user)->post(route('inventory.store'), $inventoryData);

        $response->assertRedirect(route('inventory.index'));
        $this->assertDatabaseHas('inventories', [
            'name' => 'Fertilizer A',
            'user_id' => $user->id
        ]);
    }
}
