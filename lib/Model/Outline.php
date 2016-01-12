<?php

/**
 * Description of Course
 *
 * 
 */
class Outline
{

    protected $id;
    protected $name;
    protected $course_id;
	protected $careerOutline;

    public function __construct($configs)
    {
        $this->connection = MySQLiQuery::getObject($configs['host'], $configs['username'], $configs['pass'], $configs['DB']);
		$this->careerOutline = new CareerOutline($configs);
    }

    public function getOutlinesByCourse($course_id)
    {

        if (isset($this->connection)) {
            $requirements = array(
                'code',
                'category_id'
            );

//            $categoryCode = $this->connection->select("ASSOCIATIVE", "category", "code", FALSE, "id", $course_id, "=");
            $outlines = $this->connection->select("ASSOCIATIVE", "outline", "*", FALSE, "course_id", $course_id, "=");

            for ($i = 0; $i < count($outlines); $i++) {
                // adding course code to outlines object
                $courseData = $this->connection->select("ASSOCIATIVE", "course", $requirements, FALSE, "id", $course_id, "=");
                $outlines[$i]['course_code'] = $courseData[0]['code'];
                // adding category code to outlines object
                $categoryData = $this->connection->select("ASSOCIATIVE", "category", "code", FALSE, "id", $courseData[0]["category_id"], "=");
                $outlines[$i]['category_code'] = $categoryData[0]['code'];
                // adding array of careers for each outline
                $careerData = $this->connection->select("ASSOCIATIVE", "career_outlines", "career_id", FALSE, "outline_id", $outlines[$i]['id'], "=");
                $outlines[$i]['careers'] = $careerData;
            }
            
            return $outlines;
        }
        else {
            return "Database connection Error";
        }
    }

    public function getOutlineBy($columns, $target, $value)
    {
        if (isset($this->connection)) {
            return $this->connection->select("ASSOCIATIVE", "outline", $columns, FALSE, $target, $value, "=");
        }
        else {
            return "Database connection Error";
        }
    }

    public function getOutlinesByCategory($category_id)
    {

        if (isset($this->connection)) {
            
            $requirements = array(
                'code',
                'category_id'
            );
            
            $outlines = $this->connection->select("ASSOCIATIVE", "outline ol JOIN course crs ON ol.course_id = crs.id", "ol.*", FALSE, "category_id", $category_id, "=");
        
            for ($i = 0; $i < count($outlines); $i++) {
                // adding course code to outlines object
                $courseData = $this->connection->select("ASSOCIATIVE", "course", $requirements, FALSE, "id", $outlines[$i]['course_id'], "=");
                $outlines[$i]['course_code'] = $courseData[0]['code'];
                // adding category code to outlines object
                $categoryData = $this->connection->select("ASSOCIATIVE", "category", "code", FALSE, "id", $courseData[0]["category_id"], "=");
                $outlines[$i]['category_code'] = $categoryData[0]['code'];
                // adding array of careers for each outline
                $careerData = $this->connection->select("ASSOCIATIVE", "career_outlines", "career_id", FALSE, "outline_id", $outlines[$i]['id'], "=");
                $outlines[$i]['careers'] = $careerData;
            }
            
            
            return $outlines;
        }
        else {
            return "Database connection Error";
        }
    }

    public function saveOutline($columns, $values, $id)
    {
        if (isset($this->connection)) {
            $result 		= $this->connection->update('outline', $columns, $values, 'id', $id, '=');
            $careerOutlines = $this->connection->select("ASSOCIATIVE", "career_outlines", "career_id", FALSE, "outline_id", $id, "=");

			for ($i = 0; $i < count($careerOutlines); $i++) {
				$this->careerOutline->unassignOutlineToCareer(array($careerOutlines[$i]['career_id'], $id));
				$this->careerOutline->assignOutlineToCareer(array($careerOutlines[$i]['career_id'], $id));
			}

			if ($result !== FALSE) {
				return array('result' => 'success', 'message' => 'Outline has been updated successfully');
			} else {
				return array('result' => 'error', 'message' => 'Fail to update outline');
			}
        }
        else {
            return "Database connection Error";
        }
    }

}
