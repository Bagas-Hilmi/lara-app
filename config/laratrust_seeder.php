<?php
return [
    'create_users' => false,
    'truncate_tables' => true,
    'roles_structure' => [
        
        'super-admin' => [
            'task' => 'c,r,u,d,apv,ack',
            'user' => 'c,r,u,d'
        ],

        'admin' => [
            'task' => 'c,r,u,apv,ack',
            'user' => 'c,r,u'
        ],
        'manager' => [
            'task' => 'c,r,u,apv,ack',
            'user' => 'c,r,u'
        ],
        'superintendent' => [
            'task' => 'c,r,u,apv,ack'
        ],
        'senior-supervisor' => [
            'task' => 'c,r,u,apv,ack'
        ],
        'supervisor' => [
            'task' => 'c,r,u,apv'   
        ],
        'senior-staff' => [
            'task' => 'c,r,u,apv'
        ],
        'staff' => [
            'task' => 'c,r,u'
        ],
        'viewer' => [
            'task' => 'r'
        ]
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
        'apv' => 'approve',
        'ack' => 'acknowledge'
    ]

];



