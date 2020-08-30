<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class RegisterContactTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRegisterContact(){

        $json = '{
            "idDocument":"22222221",
            "firstName":"Ruben",
            "middleName":"Alberto",
            "lastName":"Pulido",
            "birthdate":"2020-02-02",
            "phonePersonal":"44080927",
            "phoneWork":"44080927",
            "email":"rubenpul@gmail.com",
            "idCountry":"US",
            "idState":"DE",
            "idCity":"3004373",
            "street":"reverendo padre ansaldo",
            "number":"651",
            "zipCode":"1744",
            "companyName":"Kin+Carta",
            "imageProfile":"none"
        }';

        Storage::fake('local');
        $image = UploadedFile::fake()->create('image.jpg');

        $response = $this->json('POST', '/contact/register', ['json' => $json,'image0' => $image]);
        
        $response->assertStatus(200);

    }
}
