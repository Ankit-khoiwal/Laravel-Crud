<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['role_name' => 'Admin'],
            ['role_name' => 'Manager'],
            ['role_name' => 'Supervisor'],
            ['role_name' => 'Employee'],
            ['role_name' => 'Intern'],
            ['role_name' => 'HR']
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
