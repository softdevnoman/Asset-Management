<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\User;
use App\Enums\AssetCondition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_assets_via_ajax()
    {
        $user = User::factory()->create();
        
        $asset = Asset::create([
            'asset_code' => 'AST-999',
            'name' => 'Test Laptop',
            'serial_number' => 'SN99999',
            'condition' => AssetCondition::GOOD,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/manage-assets');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'asset_code' => 'AST-999',
            'name' => 'Test Laptop',
        ]);
    }

    public function test_authenticated_user_can_create_asset()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/manage-assets', [
                'asset_code' => 'AST-001',
                'name' => 'New Monitor',
                'serial_number' => 'SN12345',
                'purchase_price' => 500.50,
                'purchase_date' => '2026-01-01',
                'condition' => 'Excellent',
                'notes' => 'Some testing notes',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['message', 'asset']);
        
        $this->assertDatabaseHas('assets', [
            'asset_code' => 'AST-001',
            'name' => 'New Monitor',
            'purchased_price' => 500.50,
            'condition' => 'Excellent',
        ]);
    }

    public function test_authenticated_user_can_show_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::create([
            'asset_code' => 'AST-111',
            'name' => 'Office Chair',
            'serial_number' => 'SN654321',
            'condition' => AssetCondition::FAIR,
        ]);

        $response = $this->actingAs($user)
            ->getJson("/manage-assets/{$asset->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'asset_code' => 'AST-111',
            'name' => 'Office Chair',
        ]);
    }

    public function test_authenticated_user_can_update_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::create([
            'asset_code' => 'AST-111',
            'name' => 'Office Chair',
            'serial_number' => 'SN654321',
            'condition' => AssetCondition::FAIR,
        ]);

        $response = $this->actingAs($user)
            ->putJson("/manage-assets/{$asset->id}", [
                'asset_code' => 'AST-111-UPD',
                'name' => 'Updated Office Chair',
                'serial_number' => 'SN654321-NEW',
                'purchase_price' => 250.00,
                'condition' => 'Poor',
            ]);

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'asset_code' => 'AST-111-UPD',
            'name' => 'Updated Office Chair',
            'purchased_price' => 250.00,
            'condition' => 'Poor',
        ]);
    }

    public function test_authenticated_user_can_delete_asset()
    {
        $user = User::factory()->create();
        $asset = Asset::create([
            'asset_code' => 'AST-111',
            'name' => 'Office Chair',
            'serial_number' => 'SN654321',
            'condition' => AssetCondition::FAIR,
        ]);

        $response = $this->actingAs($user)
            ->deleteJson("/manage-assets/{$asset->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('assets', [
            'id' => $asset->id,
        ]);
    }
}
