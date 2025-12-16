<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['code' => 'DPT-001', 'name' => 'Purchasing', 'is_active' => true],
            ['code' => 'DPT-002', 'name' => 'Production', 'is_active' => true],
            ['code' => 'DPT-003', 'name' => 'Maintenance', 'is_active' => true],
            ['code' => 'DPT-004', 'name' => 'Engineering', 'is_active' => true],
            ['code' => 'DPT-005', 'name' => 'Finance', 'is_active' => true],
            ['code' => 'DPT-006', 'name' => 'HRD', 'is_active' => true],
            ['code' => 'DPT-007', 'name' => 'IT', 'is_active' => true],
            ['code' => 'DPT-008', 'name' => 'Warehouse', 'is_active' => true],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}