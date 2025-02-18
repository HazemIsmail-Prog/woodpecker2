<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            [
                'id' => 1,
                'name' => 'Arshad',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 2, 
                'name' => 'Anil',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Sakib',
                'type' => 'supervisor', 
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'Tarsem',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 5,
                'name' => 'Parmjeet',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 6,
                'name' => 'Kamar',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 7,
                'name' => 'Sathyam',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 8,
                'name' => 'Kamaldev',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 9,
                'name' => 'Sahvez',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 10,
                'name' => 'Shariq',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 11,
                'name' => 'Zakir',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 12,
                'name' => 'Dante',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 13,
                'name' => 'Manjay',
                'type' => 'supervisor',
                'is_active' => true,
            ],
            [
                'id' => 15,
                'name' => 'Koshal',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 16,
                'name' => 'Jaggu',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 17,
                'name' => 'Sanjay',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 18,
                'name' => 'Lildhari',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 19,
                'name' => 'Sajith',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 20,
                'name' => 'Parvez',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 21,
                'name' => 'Laxman',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 22,
                'name' => 'Raju',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 23,
                'name' => 'Narayan',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 24,
                'name' => 'Julie',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 25,
                'name' => 'Sarvesh',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 26,
                'name' => 'Lildhari',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 27,
                'name' => 'Amrit',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 28,
                'name' => 'MD Arshad',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 29,
                'name' => 'Rahiman',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 30,
                'name' => 'Santosh',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 31,
                'name' => 'Arjun',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 32,
                'name' => 'Thakur',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 33,
                'name' => 'Brindera',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 34,
                'name' => 'Daleep',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 35,
                'name' => 'Farman',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 36,
                'name' => 'Albert',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 37,
                'name' => 'Atif',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 38,
                'name' => 'Ankit',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 39,
                'name' => 'Mahesh',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 40,
                'name' => 'Brinder brhi',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 41,
                'name' => 'Dilshad',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 42,
                'name' => 'Bishnu',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 43,
                'name' => 'Suresh',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 44,
                'name' => 'Pritham lal',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 45,
                'name' => 'Ramesh',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 46,
                'name' => 'Opinder',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 47,
                'name' => 'Alwin',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 48,
                'name' => 'Marlon',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 49,
                'name' => 'Parmoud',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 50,
                'name' => 'Shamshdeen ',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 51,
                'name' => 'Juber ',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 52,
                'name' => 'Surya (driver )',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 53,
                'name' => 'sanjeev',
                'type' => 'technician',
                'is_active' => true,
            ],  
            [
                'id' => 54,
                'name' => 'rizwan',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 55,
                'name' => 'shanawaz',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 56,
                'name' => 'SUB CONTARCTOR 1',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 57,
                'name' => 'SUB CONTARCTOR 2',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 58,
                'name' => 'MAINTINNACE 1',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 59,
                'name' => 'MAINTINNACE 2',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 60,
                'name' => 'MAINTINCE 3',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 61,
                'name' => 'MAITINNACE 4',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 62,
                'name' => 'Rambhjn',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 63,
                'name' => 'SAME TEAM ABOVE',
                'type' => 'technician',
                'is_active' => true,
            ],
            [
                'id' => 64,
                'name' => 'alex',
                'type' => 'technician',
                'is_active' => true,
            ],
            

            
        ];

        Employee::insert($employees);
    }
} 