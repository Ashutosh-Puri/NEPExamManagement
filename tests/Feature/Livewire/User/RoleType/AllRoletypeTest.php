<?php

namespace Tests\Feature\Livewire\User\RoleType;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Roletype;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\User\RoleType\AllRoletype;

class AllRoletypeTest extends TestCase
{
//     use RefreshDatabase;

//     /** @test */
//     public function renders_successfully()
//     {
//         Livewire::test(AllRoletype::class)
//             ->assertStatus(200);
//     }

//     /** @test */
//     public function it_can_add_a_new_roletype()
//     {
//         // Livewire test
//         Livewire::test(AllRoletype::class)
//             ->set('roletype_name', 'New Roletype Name')
//             ->call('save')
//             ->assertStatus(200);

//         // Assert that the new Roletype is stored in the database
//         $this->assertDatabaseHas('roletypes', ['roletype_name' => 'New Roletype Name']);
//     }

//     /** @test */
//     public function it_can_edit_an_existing_roletype()
//     {
//         // Create a Roletype in the database
//         $roletype = Roletype::create(['roletype_name' => 'Original Roletype Name']);

//         // Livewire test
//         Livewire::test(AllRoletype::class, ['roletype' => $roletype])
//             ->call('edit', $roletype)
//             ->assertSet('roletype_id', $roletype->id)
//             ->assertSet('roletype_name', $roletype->roletype_name)
//             ->assertSet('mode', 'edit');

//     }

//     /** @test */
//     public function it_can_update_an_existing_roletype()
//     {
//         // Create a Roletype in the database
//         $roletype = Roletype::create(['roletype_name' => 'Original Roletype Name']);

//         // Livewire test
//         Livewire::test(AllRoletype::class, ['roletype' => $roletype])
//             ->set('roletype_name', 'Updated Roletype Name')
//             ->call('update', $roletype)
//             ->assertStatus(200);

//         // Assert that the Roletype is updated in the database
//         $this->assertDatabaseHas('roletypes', ['id' => $roletype->id, 'roletype_name' => 'Updated Roletype Name']);

//         // Assert that the original Roletype name is no longer in the database
//         $this->assertDatabaseMissing('roletypes', ['id' => $roletype->id, 'roletype_name' => 'Original Roletype Name']);
//     }

//     /** @test */
//     public function it_can_perform_soft_delete_on_roletype()
//     {
//         // Create a Roletype in the database
//         $roletype = Roletype::create(['roletype_name' => 'To be soft-deleted']);

//         // Livewire test
//         Livewire::test(AllRoletype::class)
//             ->call('softdelete', $roletype->id)
//             ->assertStatus(200);

//         // Assert that the Roletype is soft-deleted in the database
//         $this->assertSoftDeleted('roletypes', ['id' => $roletype->id]);
//     }

//     /** @test */
//     public function it_can_restore_a_soft_deleted_roletype()
//     {
//         // Create a soft-deleted Roletype in the database
//         $roletype = Roletype::create(['roletype_name' => 'To be restored']);
//         $roletype->delete();

//         // Livewire test
//         Livewire::test(AllRoletype::class)
//             ->call('restore', $roletype->id)
//             ->assertStatus(200);

//         // Assert that the Roletype is restored in the database
//         $this->assertDatabaseHas('roletypes', ['id' => $roletype->id, 'deleted_at' => null]);
//     }

//    /** @test */
//     public function it_can_delete_an_existing_roletype()
//     {
//         // Create a Roletype in the database
//         $roletype = Roletype::create(['roletype_name' => 'To be deleted']);

//         // Livewire test
//         Livewire::test(AllRoletype::class, ['roletype' => $roletype])
//             ->call('deleteconfirmation', $roletype->id)
//             ->assertStatus(200);
//     }

}
