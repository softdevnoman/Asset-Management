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

    public function test_searching_assets()
    {
        $user = User::factory()->create();
        
        Asset::create([
            'asset_code' => 'AST-SEARCH-1',
            'name' => 'Matching Laptop',
            'serial_number' => 'SN001',
            'condition' => AssetCondition::GOOD,
        ]);
        Asset::create([
            'asset_code' => 'AST-OTHER-2',
            'name' => 'Generic Desk',
            'serial_number' => 'SN002',
            'condition' => AssetCondition::FAIR,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/manage-assets?search=Matching');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment([
            'asset_code' => 'AST-SEARCH-1'
        ]);
    }

    public function test_sorting_assets()
    {
        $user = User::factory()->create();
        
        Asset::create([
            'asset_code' => 'AST-A',
            'name' => 'Apple MacBook',
            'serial_number' => 'SN-A',
            'condition' => AssetCondition::GOOD,
        ]);
        Asset::create([
            'asset_code' => 'AST-Z',
            'name' => 'Zebra Printer',
            'serial_number' => 'SN-Z',
            'condition' => AssetCondition::GOOD,
        ]);

        // Test sorting by name ASC
        $responseAsc = $this->actingAs($user)
            ->getJson('/manage-assets?sort_by=name&sort_dir=asc');
        $responseAsc->assertStatus(200);
        $dataAsc = $responseAsc->json();
        $this->assertEquals('Apple MacBook', $dataAsc[0]['name']);
        $this->assertEquals('Zebra Printer', $dataAsc[1]['name']);

        // Test sorting by name DESC
        $responseDesc = $this->actingAs($user)
            ->getJson('/manage-assets?sort_by=name&sort_dir=desc');
        $responseDesc->assertStatus(200);
        $dataDesc = $responseDesc->json();
        $this->assertEquals('Zebra Printer', $dataDesc[0]['name']);
        $this->assertEquals('Apple MacBook', $dataDesc[1]['name']);
    }
}
