<?php

return [
    [
        'pattern' => '/account/me',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'getAuthenticatedUser'
    ],
    [
        'pattern' => '/account/customer/registration',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'customerOtp'
    ],
    [
        'pattern' => '/account/customer/registration/{phone}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'customerRegister'
    ],
    [
        'pattern' => '/account/customer/login',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'customerLogin'
    ],
    [
        'pattern' => '/account/technician/registration',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'technicianOtp'
    ],
    [
        'pattern' => '/account/technician/registration/{apitu_member_id}',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'registration',
        'action' => 'technicianRegister'
    ],
    [
        'pattern' => '/account/technician/login',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'technicianLogin'
    ],
    [
        'pattern' => '/account/admin/login',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'login',
        'action' => 'adminLogin'
    ],

    // user management
    [
        'pattern' => '/account_management/head_of_dpc',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'setHeadOfDpc'
    ],
    [
        'pattern' => '/account_management/head_of_dpc',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'setHeadOfDpcSearch'
    ],
    [
        'pattern' => '/account_management/head_of_dpc_list',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'getHeadOfDpcList'
    ],
    [
        'pattern' => '/account_management/head_of_dpp',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'setHeadOfDpp'
    ],
    [
        'pattern' => '/account_management/head_of_dpp',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'setHeadOfDppSearch'
    ],
    [
        'pattern' => '/account_management/head_of_dpp_list',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'getHeadOfDppList'
    ],
    [
        'pattern' => '/account_management/technician/unverified',
        'method' => 'GET',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'getUnverifiedTechnicianList'
    ],
    [
        'pattern' => '/account_management/technician/approval',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'approveTechnician'
    ],
    [
        'pattern' => '/account_management/technician/rejection',
        'method' => 'POST',
        'namespace' => 'A7Pro\Account\Presentation\Controllers',
        'controller' => 'accountManagement',
        'action' => 'rejectTechnician'
    ],
];
