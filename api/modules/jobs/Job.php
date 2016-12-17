<?php

namespace api\modules\jobs;

use api\app\filters\Clear;
use api\app\MainController;
use api\app\QueryString;
use api\app\Range;
use api\app\Error;
use api\app\validators\Confirm;
use api\app\View;

class Job extends MainController
{
    private $model;
    use View;
    use QueryString;
    use Range;

    /**
     * Job constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->model = new JobModel();
    }

    public function findJobsAction()
    {
        $this->view("find_job");
    }

    public function previewJobsAction()
    {
        $this->view("preview_job");
    }

    public function detailsJobsAction()
    {
        $this->view("current_job");
    }

    public function applyJobAction()
    {
        $this->view("apply_job");
    }

    public function newJobAction()
    {
        $this->view("submit_job");
    }

    public function editJobsAction()
    {
        $this->view("edit_job");
    }

    public function articleAction()
    {
        $this->view("standart");
    }


    /**** API ***/
    public function getJobApi($id)
    {
        $job = $this->model->getJob(['id' => Clear::run($id)]);
        return (!$job) ? http_response_code(404) : print json_encode($job);
    }

    public function getJobsApi()
    {
        $id = Clear::run($_COOKIE['user_id']);
        print json_encode($this->model->getJobs(["id" => Clear::run($id)]));
    }

    public function deleteJobApi($id)
    {
        $del = $this->model->deleteJob(Clear::run($id));
        if (!$del) {
            http_response_code(204);
            return false;
        }
    }

    public function findJobsApi()
    {
        $params = $limit = null;
        if (!isset($_SERVER["HTTP_RANGE"])) return http_response_code(416);
        !empty($_SERVER["QUERY_STRING"]) && ($params = $this->getQueryString($_SERVER["QUERY_STRING"]));
        $limit = $this->getRage($_SERVER["HTTP_RANGE"]);
        $finish = ($limit['finish'] - $limit['start']) + 1;
        $foundJobs = $this->model->findJob($params, ['start' => $limit['start'], 'finish' => $finish]);
        $countFoundJobs = $foundJobs['count']['count'];
//        if ((($limit['finish'] + $limit['start']) - $limit['start']) > $countFoundJobs) return http_response_code(416);
        header("Content-Range: {$limit['start']}-{$limit['finish']}/{$countFoundJobs}");
        return print json_encode($foundJobs['jobs']);
    }

    public function updateJobApi($id)
    {
        $data = [];
        $fields = '';
        parse_str(file_get_contents('php://input'), $data);
        if (!$this->validator->run($data, $this->getFormRules('jobs')['updateJob'])) return http_response_code(400);
        $validatedData = Clear::run($data);
        $jobId = Clear::run($id);
        $types = Clear::run($data['job_type_id']);
        $category = $validatedData['jobs_category_id'];
        unset($data['job_type_id']);
        unset($validatedData['jobs_category_id']);
        unset($validatedData['company']);

        foreach ($validatedData as $item => $value) {;
            $fields .= "{$item} = '{$value}',";
        }
        $update = $this->model->updateJob(substr($fields, 0, -1), $category, $types, (int)$jobId);
        return (!$update) ? http_response_code(204) : $this;
    }

    public function filledJobApi($id)
    {
        $data = [];
        parse_str(file_get_contents('php://input'), $data);
        $filled = $this->model->filledJob(["filled" => Clear::run($data['filled']), "id" => Clear::run($id)]);
        return (!$filled) ? http_response_code(404) : $this;
    }

    public function newJobApi()
    {
        if (empty($_POST)) return http_response_code(400);
        if (!$this->validator->run($_POST, $this->getFormRules("jobs")['newJob'])) return http_response_code(400);
        $validatedJob = Clear::run($_POST);
        $validatedJob['job_type_id'] = Clear::run($_POST['job_type_id']);
        $validatedJob['author_id'] = Clear::run($_COOKIE['user_id']);
        $jobCategoryId = $validatedJob['jobs_category_id'];
        $jobTypeId = $validatedJob['job_type_id'];
        unset($validatedJob['jobs_category_id']);
        unset($validatedJob['job_type_id']);
        unset($validatedJob['company']);
        $newJob = $this->model->insJob($validatedJob, $jobCategoryId, $jobTypeId);
        if (!$newJob) return http_response_code(400);
        http_response_code(201);
        header("Location: /jobs/{$newJob}");
        return $this;
    }

    public function getBoroughsApi()
    {
        $boroughs = $this->model->getBoroughs();
        return !$boroughs ? http_response_code(404) : print json_encode($boroughs);
    }

    public function getCategoriesApi()
    {
        $categories = $this->model->getCategories();
        return !$categories ? http_response_code(404) : print json_encode($categories);
    }

    public function getTypesApi()
    {
        $types = $this->model->getTypes();
        return !$types ? http_response_code(404) : print json_encode($types);
    }

    public function getTypesByJobApi($id)
    {
        $types = $this->model->getTypesByJobs($id);
        return !$types ? http_response_code(404) : print json_encode($types);
    }

    public function getPartnersApi()
    {
        $partners = $this->model->getPartners();
        return !$partners ? http_response_code(404) : print json_encode($partners);
    }

    public function getCompaniesApi()
    {
        $params = [];
        !empty($_SERVER["QUERY_STRING"]) && ($params = $this->getQueryString($_SERVER["QUERY_STRING"]));
        $name = Clear::run($params['partners']);
        if (mb_strlen($name) < 3) return false;
        $companies = $this->model->getCompanies($name);
        return print json_encode($companies);
    }

    public function applyJobApi()
    {
        if (empty($_POST)) return http_response_code(400);
        $validatedData = Clear::run($_POST);
        if (!empty($_FILES)) $validatedData['file'] = $_FILES;
        if (!$this->validator->run($validatedData, $this->getFormRules("jobs")['applyJob'])) return http_response_code(400);
        if (!Confirm::run($validatedData['email'], $validatedData['confirm_email'])) {
            Error::setMessage("Email are not identical");
            return http_response_code(400);
        }
        if (isset($validatedData['confirm_email'])) unset($validatedData['confirm_email']);
        $validatedData['cv_name'] = $this->saveFile($validatedData['file']['cv_name']);
        unset($validatedData['file']);
        $newApply = $this->model->insertApplyJob($validatedData);
        if ($newApply) {
            http_response_code(201);
            header("Location: /apply-job/{$newApply}");
            return $this;
        } else return http_response_code(400);
    }

    private function saveFile($file)
    {
        $imgName = $file['name'];
        $imgTmpName = $file['tmp_name'];
        $imgExt = explode(".", $imgName);
        $imgExt = strtolower(end($imgExt));
        $newImage = uniqid("cv_", true) . "." . $imgExt;
        $destination = "public/cv/{$newImage}";
        if (move_uploaded_file($imgTmpName, $destination)) {
            return $newImage;
        } else return false;
    }
}
