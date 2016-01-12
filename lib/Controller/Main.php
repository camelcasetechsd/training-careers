<?php

class Main
{

    protected $_data;
    protected $_config;

    public function __construct($requestData, $config)
    {
        $this->_data = $requestData;
        $this->_config = $config;
    }

    public function utf8_converter($array)
    {
        array_walk_recursive($array, 'Main::stringEncode');

        return $array;
    }

    public static function stringEncode(&$item, $key) {
        if (!mb_detect_encoding($item, 'utf-8', true)) {
            $item = utf8_encode($item);
        }
    }

    public function listCareers()
    {
        $db = new Career($this->_config);
        $result = $db->getCareers();
        $encodedResult = $this->utf8_converter($result);
        $careers = json_encode($encodedResult);
        echo $careers;
    }

    public function listCourses()
    {
        $db = new Course($this->_config);
        $result = $db->getCourses();
        $encodedResult = $this->utf8_converter($result);
        $courses = json_encode($encodedResult);
        echo $courses;
    }

    public function listCategories()
    {
        $db = new Category($this->_config);
        $result = $db->getCategories();
        $encodedResult = $this->utf8_converter($result);
        $categories = json_encode($encodedResult);
        echo $categories;
    }

    public function getCategoryByCourse()
    {
        $conn = new Course($this->_config);
        $result = $conn->getCourseBy($this->_data['target'], $this->_data['value']);
        $encodedResult = $this->utf8_converter($result);
        $course = json_encode($encodedResult);
        echo $course;
    }

    public function getOutlinesByCourse()
    {
        $conn = new Outline($this->_config);
        $result = $conn->getOutlinesByCourse($this->_data['listing_type_id']);
        $encodedResult = $this->utf8_converter($result);
        $outlines = json_encode($encodedResult);
        echo $outlines;
    }

    public function getOutlinesByCategory()
    {
        $conn = new Outline($this->_config);
        $result = $conn->getOutlinesByCategory($this->_data['listing_type_id']);
        $encodedResult = $this->utf8_converter($result);
        $outlines = json_encode($encodedResult);
        echo $outlines;
    }

    public function getCoursesByCategory()
    {
        $conn = new Course($this->_config);
        $result = $conn->getCourseBy($this->_data['target'], $this->_data['value']);
        $encodedResult = $this->utf8_converter($result);
        $courses = json_encode($encodedResult);
        echo $courses;
    }

    public function applyToCareer()
    {
        $values = array(
            $this->_data['career_id'],
            $this->_data['outline_id']
        );
        //appling outline to career
        $conn = new CareerOutline($this->_config);
        $result = $conn->assignOutlineToCareer($values);
        echo json_encode($result);
    }

    public function applyToAllCareers()
    {
        //remove course outline to prevent duplication
//        $this->removeCourseOutline();

        $conn = new Career($this->_config);
        $careerIds = $conn->getCareerIds();

        //getting outline duration
        $conn = new Outline($this->_config);
        $outline = $conn->getOutlineBy('duration', 'id', $this->_data['outline_id']);
        //
        $newConn = new CareerOutline($this->_config);

        for ($i = 0; $i < count($careerIds); $i++) {
            $values = array(
                $careerIds[$i]['id'],
                $this->_data['outline_id']
            );
            // assign outline to career each time
            $result = $newConn->assignOutlineToCareer($values);
            
            if ($careerIds[$i]['id'] == $this->_data['career_id']) {
                $duration = $result;
            }            
            
        }

        echo json_encode($duration);
    }

    public function removeCourseOutline()
    {
        $values = array(
            $this->_data['career_id'],
            $this->_data['outline_id']
        );

        $conn = new CareerOutline($this->_config);
        $result = $conn->unassignOutlineToCareer($values);
        echo json_encode($result);
    }

    public function removeOutlineFromAll()
    {
        $conn = new Career($this->_config);
        $careerIds = $conn->getCareerIds();

        //getting outline duration
        $conn = new Outline($this->_config);
        $outline = $conn->getOutlineBy('duration', 'id', $this->_data['outline_id']);

        $newConn = new CareerOutline($this->_config);
        $duration = 0;
        for ($i = 0; $i < count($careerIds); $i++) {
            $values = array(
                $careerIds[$i]['id'],
                $this->_data['outline_id']
            );
            // assign outline to career each time
            $result = $newConn->unassignOutlineToCareer($values);

            if ($careerIds[$i]['id'] == $this->_data['career_id']) {
                $duration = $result;
            }            
            
        }

        echo json_encode($duration);
    }

    public function removeOutlineFromCareer()
    {

        $values = array(
            $this->_data['career_id'],
            $this->_data['outline_id']
        );
        // remove outline from this career
        $newConn = new CareerOutline($this->_config);
        $result = $newConn->unassignOutlineToCareer($values);
        echo json_encode($result);
    }
    
    public function getCareer()
    {
        $db = new Career($this->_config);
        $result = $db->getCareers($this->_data['career_id']);
        return $encodedResult = $this->utf8_converter($result);
    }

    public function getOutlineById()
    {
        $db = new Outline($this->_config);
        $result = $db->getOutlineBy('*', 'id', $this->_data['outline_id']);
        return $encodedResult = $this->utf8_converter($result);
    }

    public function saveOutlineData()
    {
        $db = new Outline($this->_config);
        $result = $db->saveOutline(array('name', 'duration'), array($this->_data['name'],$this->_data['duration']), $this->_data['outline_id']);
        $encodedResult = $this->utf8_converter($result);
		echo json_encode($encodedResult);
    }

}
