<?php

return [
    'signup' => [
        'id' => '',
        'first_name' => 'required',
        'surname' => 'required',
        'email' => 'email|required|unique:users.email',
        'password' => 'required|minLength:6',
        'confirm_password' => 'required|minLength:6',
        'gender' => 'gender',
    ],
	'update' => [
		'id' => '',
		'first_name' => 'text',
		'surname' => 'text',
		'email' => 'email|unique:users.email',
		'password' => 'required|minLength:6',
		'gender' => 'gender',
	],
    'login' => [
        'email' => 'email|required',
        'password' => 'minLength:6|required',
    ],
];
