<?php

return [
    'newApplies' => [
        'id' => '',
        'first_name' => 'required',
        'surname' => 'required',
        'email' => 'required',
        'phone' => '',
        'message' => '',
        'cv_name' => 'file:text.max_size.1',
        'jobs_id' => '',
        'date' => '',
    ],
];
