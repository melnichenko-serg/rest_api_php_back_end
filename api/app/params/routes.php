<?php

return [

/*************** Views ***************/
    'home' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'indexAction'
        ],
    ],
    'find-jobs' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'findJobsAction'
        ],
    ],
    'job-details/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'detailsJobsAction'
        ],
    ],
    'apply-job/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'applyJobAction'
        ],
    ],
    'sign-in' => [
        'GET' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'signAction',
        ],
    ],
    'log-in' => [
        'GET' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'loginAction',
        ],
    ],
    'log-out' => [
        'GET' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'logoutAction',
        ],
    ],
    'dashboard' => [
        'GET' => [
            'auth' => true,
            'module' => 'users',
            'controller' => 'user',
            'action' => 'dashboardAction',
        ],
    ],
    'new-job' => [
        'GET' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'newJobAction',
        ],
    ],
    'preview-job' => [
        'GET' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'previewJobsAction',
        ],
    ],
    'edit-job/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'editJobsAction',
        ],
    ],
    'article/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'articleAction',
        ],
    ],
/*************** END Views ***************/


/*************** API ***************/

    /***** jobs *****/
    'api/jobs/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getJobApi'
        ],
        'PUT' => [
//            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'updateJobApi',
        ],
        'DELETE' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'deleteJobApi',
        ],
    ],
    'api/jobs/:id/filled' => [
        'params' => ['id' => 'int'],
        'PATCH' => [
//            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'filledJobApi',
        ],
    ],
    'api/jobs/user/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getJobsApi'
        ],
    ],
    'api/jobs' => [
        'POST' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'newJobApi',
        ],
    ],
    'api/jobs/borough' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getBoroughsApi'
        ],
    ],
    'api/jobs/categories' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getCategoriesApi'
        ],
    ],
    'api/jobs/find' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'findJobsApi'
        ],
    ],
    'api/jobs/partners' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getPartnersApi'
        ],
    ],
    'api/jobs/companies' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getCompaniesApi'
        ],
    ],
    'api/jobs/types' => [
        'GET' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getTypesApi'
        ],
    ],
    'api/jobs/applies' => [
        'POST' => [
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'applyJobApi'
        ],
    ],
    'api/user/dashboard/getJobs' => [
        'GET' => [
            'auth' => true,
            'module' => 'jobs',
            'controller' => 'job',
            'action' => 'getJobsApi',
        ],
    ],
    /***** End jobs *****/

    /***** users *****/
    'api/users/signup' => [
        'POST' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'signApi',
        ],
    ],
    'api/users/login' => [
        'POST' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'loginApi',
        ],
    ],
    'api/users/:id' => [
        'params' => [
            'id' => 'int'
        ],
        'GET' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'getUserApi'
        ],
        'DELETE' => [
            'module' => 'users',
            'controller' => 'user',
            'action' => 'deleteUserApi',
        ],
    ],
    /***** End users *****/

    /***** home *****/
    'api/home/references' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'getReferencesApi',
        ],
    ],
    'api/home/housing' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'getHousingAssocApi',
        ],
    ],
    'api/feedback' => [
        'POST' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'newFeedbackApi',
        ],
    ],
    'api/article/:id' => [
        'params' => ['id' => 'int'],
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'getArticleApi',
        ],
    ],
    'api/articles' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'getArticlesApi',
        ],
    ],
    'api/banners' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'home',
            'action' => 'getBannersApi',
        ],
    ],

    'api/test' => [
        'GET' => [
            'module' => 'home',
            'controller' => 'test',
            'action' => 'testApi',
        ],
    ],
    /***** End home *****/

/*************** END API ***************/
];
