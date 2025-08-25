<?php

use App\Models\Personnel;

// Create sample personnel records
$personnel = [
    [
        'first_name' => 'John',
        'last_name' => 'Smith',
        'position' => 'Senior Inspector',
        'department' => 'Quality Assurance',
        'employee_id' => 'EMP001',
        'email' => 'john.smith@company.com',
        'phone' => '+1-555-0101',
        'supervisor' => 'Alice Johnson',
        'hire_date' => '2020-01-15',
        'experience_years' => 12,
        'qualifications' => 'ASNT Level III, API 510, ACCP Level 2',
        'certifications' => 'NDT Level 3 (UT, RT, MT, PT), API 570, NACE Level 2',
        'is_active' => true,
    ],
    [
        'first_name' => 'Sarah',
        'last_name' => 'Johnson',
        'position' => 'Lead Inspector',
        'department' => 'NDT Services',
        'employee_id' => 'EMP002',
        'email' => 'sarah.johnson@company.com',
        'phone' => '+1-555-0102',
        'supervisor' => 'Michael Brown',
        'hire_date' => '2019-03-20',
        'experience_years' => 8,
        'qualifications' => 'ASNT Level II, LEEA Inspector',
        'certifications' => 'NDT Level 2 (UT, MT, PT), LEEA Lifting Inspector',
        'is_active' => true,
    ],
    [
        'first_name' => 'Michael',
        'last_name' => 'Chen',
        'position' => 'Quality Technician',
        'department' => 'Quality Control',
        'employee_id' => 'EMP003',
        'email' => 'michael.chen@company.com',
        'phone' => '+1-555-0103',
        'supervisor' => 'John Smith',
        'hire_date' => '2021-08-10',
        'experience_years' => 5,
        'qualifications' => 'ASNT Level I, AWS Certified',
        'certifications' => 'NDT Level 1 (MT, PT), AWS CWI',
        'is_active' => true,
    ],
    [
        'first_name' => 'Emma',
        'last_name' => 'Wilson',
        'position' => 'Lifting Equipment Inspector',
        'department' => 'Lifting Services',
        'employee_id' => 'EMP004',
        'email' => 'emma.wilson@company.com',
        'phone' => '+1-555-0104',
        'supervisor' => 'David Martinez',
        'hire_date' => '2022-02-14',
        'experience_years' => 6,
        'qualifications' => 'LEEA Qualified, LOLER Inspector',
        'certifications' => 'LEEA Crane Inspector, LOLER Competent Person',
        'is_active' => true,
    ],
    [
        'first_name' => 'David',
        'last_name' => 'Rodriguez',
        'position' => 'NDT Technician',
        'department' => 'NDT Services',
        'employee_id' => 'EMP005',
        'email' => 'david.rodriguez@company.com',
        'phone' => '+1-555-0105',
        'supervisor' => 'Sarah Johnson',
        'hire_date' => '2023-01-10',
        'experience_years' => 3,
        'qualifications' => 'ASNT Level I, SNT-TC-1A',
        'certifications' => 'NDT Level 1 (PT, MT), Visual Testing Level 2',
        'is_active' => true,
    ]
];

foreach ($personnel as $person) {
    Personnel::create($person);
}

echo "Created 5 personnel records successfully!\n";
