<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TemplateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/api/templates');

        $response->assertStatus(200);
    }

    public function testStore()
    {

        $data = [
            'name' => 'tt',
            'remark' => 'alskfjlaskjglkasjdf lkasjf dlkajsglkdj fgsag',
        ];

        $res = $this->post('/api/templates', $data);

        dump($res->getContent());

        $res->assertStatus(201);
    }
}
