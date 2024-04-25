<?php

namespace Maksatsaparbekov\KuleshovAuth\factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Maksatsaparbekov\KuleshovAuth\Models\FakeUser;

class FakeUserFactory extends BaseFactory
{
    protected $model = FakeUser::class;

    public function definition()
    {
        return [
            'name' => fake()->name,
            'email' => fake()->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // You may adjust this as per your requirements
            'remember_token' => Str::random(10),
            'parent_id' => null,
            'status' => 1,
            'device_token' => fake()->text,
            'role' => fake()->text,
            'location' => fake()->text,
            'organization' => ['name' => fake()->company, 'address' => fake()->address],
            'additioal_phone' => fake()->phoneNumber,
            'testing' => fake()->boolean,
            'activity_date' => fake()->date,
            'version_app' => fake()->word,
            'model_app' => fake()->text,
            'grade' => fake()->numberBetween(0, 5),
            'insurance_companies_id' => null,
            'profi_timer' => fake()->dateTime(),
            'push_on' => fake()->boolean,
            'onlain' => fake()->boolean,
            'city_id' => null,
            'auction' => fake()->boolean,
            'last_name' => fake()->lastName,
            'middle_name' => fake()->firstName,
            'date_of_birth' => fake()->date,
            'address' => fake()->address,
            'resident' => fake()->boolean,
            'verification' => fake()->word,
            'passport_series' => fake()->lexify('??? ######'),
            'passport_issuer' => fake()->company,
            'date_of_passport_issue' => fake()->date,
            'passport_registration_address' => fake()->address,
            'departament_code' => fake()->numerify('######-###'),
            'face_photo' => fake()->imageUrl(),
            'photo_with_passport' => fake()->imageUrl(),
            'photo_passport' => fake()->imageUrl(),
            'photo_passport_registration' => fake()->imageUrl(),
            'legal_name' => fake()->company,
            'legal_inn' => fake()->numerify('##########'),
            'legal_address' => fake()->address,
            'legal_kpp' => fake()->numerify('######'),
            'legal_bank_name' => fake()->company,
            'legal_bik' => fake()->numerify('#########'),
            'legal_contact_person' => fake()->name,
            'legal_checking_account' => fake()->numerify('##########'),
            'legal_contact_phone' => fake()->phoneNumber,
            'legal_correspondent_account' => fake()->numerify('##########'),
            'seller' => fake()->boolean,
            'deposit' => fake()->boolean,
            'auth_identifier' => Str::random(32),
        ];
    }
}