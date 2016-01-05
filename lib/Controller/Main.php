<?php

class Main
{

    protected $_data;
    protected $_config;

    function __construct($requestData, $config)
    {
        $this->_data = $requestData;
        $this->_config = $config;
    }

    function utf8_converter($array)
    {
        array_walk_recursive($array, function(&$item, $key) {
            if (!mb_detect_encoding($item, 'utf-8', true)) {
                $item = utf8_encode($item);
            }
        });

        return $array;
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
        $result = $conn->getOutlinesByCourse($this->_data['course_id']);
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
        $conn = new CareerOutline($this->_config);
        $result = $conn->assignOutlineToCareer($values);
        return $result;
    }

    public function applyToAllCareers()
    {
        //remove course outline to prevent duplication
        $this->removeCourseOutline();

        $conn = new Career($this->_config);
        $careerIds = $conn->getCareerIds();

        $newConn = new CareerOutline($this->_config);
        foreach ($careerIds as $id) {
            $values = array(
                $id['id'],
                $this->_data['outline_id']
            );
            $result = $newConn->assignOutlineToCareer($values);
        }

        return $result;
    }

    public function removeCourseOutline()
    {
        $values = array(
            $this->_data['career_id'],
            $this->_data['outline_id']
        );

        $conn = new CareerOutline($this->_config);
        $result = $conn->unassignOutlineToCareer($values);
        return $result;
    }

    public function removeOutlineFromAll()
    {
        $conn = new Career($this->_config);
        $careerIds = $conn->getCareerIds();

        $newConn = new CareerOutline($this->_config);
        foreach ($careerIds as $id) {
            $values = array(
                $id['id'],
                $this->_data['outline_id']
            );
            $result = $newConn->unassignOutlineToCareer($values);
        }

        return $result;
    }

    public function removeOutlineFromCareer()
    {
        
        $newConn = new CareerOutline($this->_config);
        foreach ($careerIds as $id) {
            $values = array(
                $this->_data['career_id'],
                $this->_data['outline_id']
            );
            $result = $newConn->unassignOutlineToCareer($values);
        }
        return $result;
    }

}
