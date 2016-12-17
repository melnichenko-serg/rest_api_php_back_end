<?php

return [
    'newJob' => [
        'id' => '',
        'job_title' => 'required',
        'salary' => 'integer',
        'hours' => 'integer',
        'summary' => 'required',
        'closing_date' => 'date',
        'company_id' => 'required|integer',
        'job_type_id' => 'arr',
        'jobs_category_id' => 'integer',
        'reference' => '',
        'author_id' => 'required|integer',
        'borough_id' => 'required|integer',
        'filled' => '',
        'partner_id' => 'integer',
        'company' => '',
    ],

    'updateJob' => [
        'id' => '',
        'job_title' => 'text',
        'jobs_category_id' => 'integer',
        'job_type_id' => 'arr',
        'borough_id' => 'integer',
        'salary' => 'integer',
        'hours' => 'integer',
        'summary' => 'text',
        'closing_date' => 'date',
        'company_id' => 'integer',
        'company' => '',
        'reference' => '',
        'author_id' => 'integer',
        'filled' => '',
    ],

    'applyJob' => [
        'first_name' => 'required',
        'surname' => 'required',
        'email' => 'email|required',
        'confirm_email' => 'email|required',
        'phone' => '',
        'message' => '',
        'jobs_id' => 'integer',
        'file' => 'file:text.noRequired'
    ],
];
