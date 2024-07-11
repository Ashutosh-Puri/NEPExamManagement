<?php

namespace Tests\Feature\Livewire\Student\StudentExamForm;

use Tests\TestCase;
use Livewire\Livewire;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Livewire\Student\StudentExamForm\FillStudentExamForm;

class FillStudentExamFormTest extends TestCase
{   
    // public function test_login_screen_can_be_rendered(): void
    // {
    //     $response = $this->get('student/login');

    //     $response->assertStatus(200);
    // }

    // public function test_exam_form_screen_renders(): void
    // {
    //     $response = $this->post('student/login', [
    //         'email' => 'sakshi@gmail.com',
    //         'password' => '123456789',
    //     ]);

    //     $this->assertAuthenticated('student');
    //     // $response->assertStatus(200);
    //     $response->assertRedirect('student/dashboard');
        
    //     // Livewire::test(FillStudentExamForm::class)->assertStatus(200);

    //     $response = Livewire::test(FillStudentExamForm::class)
    //     ->set('medium_instruction', 'E')
    //     ->call('student_exam_form_save');
    //     // ->assertStatus(200);
       
    // }


}
