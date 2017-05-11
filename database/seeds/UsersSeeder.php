<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create three App\User instances...
        factory(App\Models\User::class, 1)->create([
            'first_name' => 'Admin',
            'last_name' => 'Administrator',
            'user_name' => 'admin',
            'email' => 'admin@paullaney.com.au',
            'password' => Hash::make('password'),
            'user_type' => 'ADMIN',
            'disabled' => 0,
            'permissions' => []
        ]);
    }
}
