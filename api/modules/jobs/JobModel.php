<?php

namespace api\modules\jobs;

use api\app\Model;
use api\app\Error;
use Exception;
use PDOException;

class JobModel
{
    use Model;

    private $boroughName;
    private $arrCategories;
    private $arrTypes;

    public function insertJob($jobsData, $jobCategoryId)
    {
        try {
            $this->connect()->beginTransaction();
            $insertJob = "INSERT INTO jobs (job_title, company_id, borough_id, salary, hours, summary, closing_date, 
											partner_id, reference, job_type, author_id)
						  VALUES (:job_title, :company_id, :borough_id, :salary, :hours, :summary, :closing_date, 
						  		  :partner_id, :reference, :job_type, :author_id)";
            $stmt = $this->connect()->prepare($insertJob);
            foreach ($jobsData as $param => $value)
                $stmt->bindValue(":{$param}", $value);
            $stmt->execute();
            $id = $this->connect()->lastInsertId();

            $insertJobsCategoryId = "INSERT INTO jobs_category_has_jobs (jobs_category_id, jobs_id) 
									 VALUES (:jobs_category_id, :jobs_id)";
            $stmt = $this->connect()->prepare($insertJobsCategoryId);
            $stmt->execute([':jobs_category_id' => $jobCategoryId, ':jobs_id' => $id]);

            $this->connect()->commit();
            return $id;
        } catch (Exception $e) {
            Error::setMessage("Error insert a job ({$e->getMessage()})");
            return false;
        }
    }

    public function insJob($jobsData, $jobCategoryId, $jobTypeId)
    {
        try {
            $this->connect()->beginTransaction();
            $insertJob = "INSERT INTO jobs (job_title, company_id, borough_id, salary, hours, summary, closing_date, reference, author_id)
						  VALUES (:job_title, :company_id, :borough_id, :salary, :hours, :summary, :closing_date, :reference, :author_id)";
            $stmt = $this->connect()->prepare($insertJob);
            foreach ($jobsData as $param => $value)
                $stmt->bindValue(":{$param}", $value);
            $stmt->execute();
            $id = $this->connect()->lastInsertId();

            $insertJobsCategoryId = "INSERT INTO jobs_category_has_jobs (jobs_category_id, jobs_id) 
									 VALUES (:jobs_category_id, :jobs_id)";
            $stmt = $this->connect()->prepare($insertJobsCategoryId);
            $stmt->execute([':jobs_category_id' => $jobCategoryId, ':jobs_id' => $id]);

            foreach ($jobTypeId as $item) {
                $insertJobsTypeId = "INSERT INTO jobs_type_has_jobs (jobs_type_id, jobs_id) VALUES (:jobs_type_id, :jobs_id)";
                $stmt = $this->connect()->prepare($insertJobsTypeId);
                $stmt->execute([':jobs_type_id' => $item, ':jobs_id' => $id]);
            }

            $this->connect()->commit();
            return $id;
        } catch (Exception $e) {
            Error::setMessage("Error insert a job ({$e->getMessage()})");
            return false;
        }
    }

    public function insertApplyJob($data)
    {
        $insertApplyJob = "INSERT INTO applies (first_name, surname, email, phone, message, jobs_id, cv_name)
                           VALUES (:first_name, :surname, :email, :phone, :message, :jobs_id, :cv_name)";
        $stmt = $this->connect()->prepare($insertApplyJob);
        foreach ($data as $param => $value)
            $stmt->bindValue(":{$param}", $value);
        $stmt->execute();
        $id = $this->connect()->lastInsertId();
        return $id;
    }

    public function updateJob($fields, $category, $types, $id)
    {
        try {
            $this->connect()->beginTransaction();
            $stmt = $this->connect()->prepare("UPDATE jobs SET {$fields} WHERE id = {$id}");
            $stmt->execute();

            $stmt = $this->connect()->prepare("UPDATE jobs_category_has_jobs SET jobs_category_id = '{$category}' WHERE jobs_id = {$id}");
            $stmt->execute();

            $stmt = $this->connect()->prepare("DELETE FROM jobs_type_has_jobs WHERE jobs_id = {$id}");
            $stmt->execute();

            foreach ($types as $item) {
                $insertJobsTypeId = "INSERT INTO jobs_type_has_jobs (jobs_type_id, jobs_id) VALUES (:jobs_type_id, :jobs_id)";
                $stmt = $this->connect()->prepare($insertJobsTypeId);
                $stmt->execute([':jobs_type_id' => $item, ':jobs_id' => $id]);
            }
            return $this->connect()->commit();
        } catch (PDOException $exception) {
            Error::setMessage($exception->getMessage());
            return false;
        }
    }

    public function getJob($id)
    {
        $query = "SELECT * FROM v_get_job WHERE id = :id";
        $res = $this->read($query, $id, false, true);
        $res['types'] = $this->read("SELECT jobs_type_id FROM jobs_type_has_jobs WHERE jobs_id = :id", $id, true, true);
        $res['cat'] = $this->read("SELECT jobs_category_id FROM jobs_category_has_jobs WHERE jobs_id = :id", $id, false, true);
        return $res;
    }

    public function getJobs($id)
    {
        return $this->read("SELECT jobs.id, jobs.job_title, jobs.filled, jobs.closing_date FROM jobs WHERE author_id = :id ORDER BY id DESC", $id, true, false);
    }

    public function findJob($data, $limit)
    {
        $this->boroughName = (isset($data['borough_id'])) ? $data['borough_id'] : '';
        $this->arrCategories = (isset($data['jobs_category_id'])) ? $data['jobs_category_id'] : [];
        $this->arrTypes = (isset($data['job_type'])) ? $data['job_type'] : [];
        $queryString = $this->borough($this->boroughName) . $this->categories($this->arrCategories) . $this->types($this->arrTypes);
	    $filled = ($queryString !== '') ? " AND filled = 0" : "WHERE filled = 0";
	    $query = "SELECT SQL_CALC_FOUND_ROWS DISTINCT job_id, Job_Title, Borough, Closing_Date, Company
				  FROM v_find_jobs {$queryString}{$filled} ORDER BY job_id DESC LIMIT {$limit['start']}, {$limit['finish']}";
        return [
            'jobs' => $this->read($query, [], true, false),
            'count' => $this->read("SELECT FOUND_ROWS() as count", [], false, false),
        ];
    }

    private function borough($borough)
    {
        return (isset($borough) && $borough !== '') ? " WHERE (borough_id = {$borough}) " : "";
    }

    private function categories($categories)
    {
        $cat = '';
        if (!empty($categories)) {
            if (isset($this->boroughName) && $this->boroughName !== '') {
                for ($i = count($categories); $i--;) {
                    $cat = " AND (jobs_category_id = " . implode(" OR jobs_category_id = ", $categories) . ")";
                }
            } else {
                for ($i = count($categories); $i--;) {
                    $cat = " WHERE (jobs_category_id = " . implode(" OR jobs_category_id = ", $categories) . ")";
                }
            }
        }
        return $cat;
    }

    private function types($types)
    {
        $type = '';
        if (!empty($types)) {
            if ((isset($this->boroughName) && $this->boroughName !== '') || !empty($this->arrCategories)) {
                for ($i = count($types); $i--;) {
                    $type = " AND (job_type = " . implode(" OR job_type = ", $types) . ")";
                }
            } else {
                for ($i = count($types); $i--;) {
                    $type = " WHERE (job_type = " . implode(" OR job_type = ", $types) . ")";
                }
            }
        }
        return $type;
    }

    public function getJobsCategories()
    {
        $query = "SELECT jobs_category_id, jobs_category.name FROM jobs_category_has_jobs
              INNER JOIN jobs_category ON jobs_category_has_jobs.jobs_category_id = jobs_category.id";
        return $this->read($query, [], true, false);
    }

    public function deleteJob($id)
    {
        return $this->delete("DELETE FROM jobs WHERE id = :id", $id);
    }

    public function getCategories()
    {
        return $this->read("SELECT id, `name` FROM jobs_category", [], true, false);
    }

    public function getTypes()
    {
        return $this->read("SELECT id, `name` FROM jobs_type", [], true, false);
    }

    public function getTypesByJobs($id)
    {
        return $this->read("SELECT id FROM jobs_type_has_jobs", $id, true, false);
    }

    public function getBoroughs()
    {
        return $this->read("SELECT id, `name` FROM borough", [], true, false);
    }

    public function getPartners()
    {
        return $this->read("SELECT id, `name` FROM partners", [], true, false);
    }

    public function getCompanies($data)
    {
        $name = "%{$data}%";
        return $this->read("SELECT id, `name` FROM companies WHERE name LIKE :name", ["name"=> $name], true, false);
    }

    public function filledJob($param)
    {
        $stmt = $this->connect()->prepare("UPDATE `jobs` SET `filled` = :filled WHERE `id` = :id");
        $stmt->execute($param);
        return $stmt->rowCount();
    }
}
