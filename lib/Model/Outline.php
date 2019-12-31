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
                'name',
                'category_id'
            );

            $outlines = $this->connection->select("ASSOCIATIVE", "outline", "*", FALSE, "course_id", $course_id, "=");

            for ($i = 0; $i < count($outlines); $i++) {
                // adding course name to outlines object
                $courseData = $this->connection->select("ASSOCIATIVE", "course", $requirements, FALSE, "id", $course_id, "=");
                $outlines[$i]['course_name'] = $courseData[0]['name'];
                // adding category name to outlines object
                $categoryData = $this->connection->select("ASSOCIATIVE", "category", "name", FALSE, "id", $courseData[0]["category_id"], "=");
                $outlines[$i]['category_name'] = $categoryData[0]['name'];
                // adding skill name to outlines object
                $skillData = $this->connection->select("ASSOCIATIVE", "skill", "COALESCE(name,'-') AS name", FALSE, "id", $outlines[$i]["skill_id"], "=");
                $outlines[$i]['skill_name'] = empty($skillData[0]['name']) ? '-' : $skillData[0]['name'];
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
            $outline = $this->connection->select("ASSOCIATIVE", "outline", $columns, FALSE, $target, $value, "=");

            // adding course name to outlines object
            $courseData = $this->connection->select("ASSOCIATIVE", "course", 'category_id', FALSE, "id", $outline[0]['course_id'] , "=");
            $outline[0]['category_id'] = $courseData[0]['category_id'];
            return $outline;
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
                // adding skill name to outlines object
                $skillData = $this->connection->select("ASSOCIATIVE", "skill", "COALESCE(name,'-') AS name", FALSE, "id", $outlines[$i]["skill_id"], "=");
                $outlines[$i]['skill_name'] = empty($skillData[0]['name']) ? '-' : $skillData[0]['name'];
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

    public function getOutlines($categoryId = null, $courseId = null, $skillId = null)
    {
        if (isset($this->connection)) {
            
            $whereColumns = '';
            $whereValues = '';
            $whereOperators = '';
            $whereLogical = NULL;

            if (!empty($categoryId) && empty($courseId) && empty($skillId)) {
                $whereColumns = 'category_id';
                $whereValues = $categoryId;
                $whereOperators = '=';
            } else if (!empty($categoryId) && !empty($courseId) && empty($skillId)) {
                $whereColumns = 'course_id';
                $whereValues = $courseId;
                $whereOperators = '=';
            } else if (!empty($categoryId) && empty($courseId) && !empty($skillId)) {
                $whereColumns = array('category_id', 'skill_id');
                $whereValues = array($categoryId, $skillId);
                $whereOperators = array('=', '=');
                $whereLogical = array('AND');
            } else if (empty($categoryId) && empty($courseId) && !empty($skillId)) {
                $whereColumns = 'skill_id';
                $whereValues = $skillId;
                $whereOperators = '=';
            } else if (!empty($categoryId) && !empty($courseId) && !empty($skillId)) {
                $whereColumns = array('course_id', 'skill_id');
                $whereValues = array($courseId, $skillId);
                $whereOperators = array('=', '=');
                $whereLogical = array('AND');
            }
            
            $outlines = $this->connection->select("ASSOCIATIVE", "outline ol JOIN course crs ON ol.course_id = crs.id JOIN category cat ON crs.category_id = cat.id LEFT JOIN skill sk ON ol.skill_id = sk.id", "ol.*, crs.name AS course_name, cat.name AS category_name, COALESCE(sk.name,'-') AS skill_name", FALSE,
                $whereColumns, $whereValues, $whereOperators, $whereLogical);
            
            for ($i = 0; $i < count($outlines); $i++) {
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
                                if (!($columns[0] == 'status' && $values[0] == 0)) {
                                    $this->careerOutline->assignOutlineToCareer(array($careerOutlines[$i]['career_id'], $id));
                                }
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
