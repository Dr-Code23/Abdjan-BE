<?php
return [
    'permissions' => [
        'product_management',
        'service_management',
        'role_management',
        'user_management',
        'project_management',
        'project_payment_management',
        'project_expenses_management',
        'general_expenses_management',
        'my_shop_management',
        'about_us_management',
        'contact_us_management',
        'brand_management',
        'attribute_management',
        'unit_management',
        'category_management',
    ],
    'roles' => [
        // Roles Has All Roles Except in the array
        'super_admin' => [],
        'admin' => ['role_management'],
        'employee' => ['role_management' , 'user_management']
    ]
];
