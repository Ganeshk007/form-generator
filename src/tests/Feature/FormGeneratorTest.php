<?php

namespace Tests\Feature;

use Ganesh\FormGenerator\Models\FormTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormGeneratorTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_create_a_form_template()
    {
        $form = FormTemplate::create([
            'name' => 'Test Form',
            'slug' => 'test_form',
            'fields' => [
                ['type' => 'text', 'name' => 'full_name', 'id' => 'full_name', 'class' => 'form-control'],
            ],
        ]);

        $this->assertDatabaseHas('forms', ['name' => 'Test Form']);
    }
}
