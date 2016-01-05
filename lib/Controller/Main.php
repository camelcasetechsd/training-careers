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
}
