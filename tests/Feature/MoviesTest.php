<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    public function testRequiredFieldsForCreate()
    {
        $this->json('POST', 'movies', ['Accept' => 'application/json'])
        ->assertStatus(400)
        ->assertJson([
            "data" => null,
            "error_message" => [
                "title" => ["The title field is required."],
                "description" => ["The description field is required."],
                "rating" => ["The rating field is required."],
            ],
            "status" => 400,
        ]);
    }

    public function testTitleSame()
    {
        $data = [
            "title" => "Pengabdi Setan " . date("Y-m-d H:i:s"),
            "description" => "dalah sebuah film horor Indonesia tahun 2022 yang disutradarai dan ditulis oleh Joko Anwar sebagai sekuel dari film tahun 2017, Pengabdi Setan.",
            "rating" => 7.0,
            "image" => null
        ];

        $this->json('POST', 'movies', $data, ['Accept' => 'application/json'])
            ->assertStatus(400);
    }
}
