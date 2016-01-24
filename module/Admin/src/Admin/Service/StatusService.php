<?php

 namespace Admin\Service;
 
 use Admin\Model\Category;
 use Admin\Model\Course;
 use Admin\Model\Skill;
 use Admin\Model\Career;

 
 class StatusService
 {
    protected $serviceLocator;

    public function __construct($serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }

    public function deactivateCategory($id)
    {
       $categoryTable    = $this->serviceLocator->get('Admin\Model\CategoryTable');
       $category         = new Category();
       $category->id     = $id;
       $category->status = 0;
       
       $categoryTable->save($category, array('status'));


       $courseTable         = $this->serviceLocator->get('Admin\Model\CourseTable');
       $courses             = $courseTable->get(0, array('category_id' => $id));

       $defaultCategory     = $categoryTable->get(0, array('default' => 1));
       foreach ($courses as $course) {
          $course->category_id = $defaultCategory[0]->id;
          $courseTable->save($course);
       }

       return true;
    }

    public function activateCategory($id)
    {
       $categoryTable = $this->serviceLocator->get('Admin\Model\CategoryTable');
       $category         = new Category();
       $category->id     = $id;
       $category->status = 1;

       $categoryTable->save($category, array('status'));

       return true;
    }

    public function deactivateSkill($id)
    {
       $skillTable    = $this->serviceLocator->get('Admin\Model\SkillTable');
       $skill         = new Skill();
       $skill->id     = $id;
       $skill->status = 0;

       $skillTable->save($skill, array('status'));


       $outlineTable = $this->serviceLocator->get('Admin\Model\OutlineTable');
       $outlines     = $outlineTable->get(0, array('skill_id' => $id));

       foreach ($outlines as $outline) {
          $outline->skill_id = 0;
          $outlineTable->save($outline);
       }

    }

    public function activateSkill($id)
    {
       $skillTable    = $this->serviceLocator->get('Admin\Model\SkillTable');
       $skill         = new Skill();
       $skill->id     = $id;
       $skill->status = 1;

       $skillTable->save($skill, array('status'));
    }

    public function deactivateCourse($id)
    {
       $courseTable    = $this->serviceLocator->get('Admin\Model\CourseTable');
       $course         = new Course();
       $course->id     = $id;
       $course->status = 0;

       $courseTable->save($course, array('status'));


       $outlineTable       = $this->serviceLocator->get('Admin\Model\OutlineTable');
       $careerOutlineTable = $this->serviceLocator->get('Admin\Model\CareerOutlineTable');

       $outlines           = $outlineTable->get(0, array('course_id' => $id));

       foreach ($outlines as $outline) {
          $outline->status = 0;
          $outlineTable->save($outline);

          $careerOutlineTable->delete(0, array('outline_id' => $outline->id));
       }
    }

    public function activateCourse($id)
    {
       $courseTable    = $this->serviceLocator->get('Admin\Model\CourseTable');
       $course         = new Course();
       $course->id     = $id;
       $course->status = 1;

       $courseTable->save($course, array('status'));


       $outlineTable       = $this->serviceLocator->get('Admin\Model\OutlineTable');

       $outlines           = $outlineTable->get(0, array('course_id' => $id));

       foreach ($outlines as $outline) {
          $outline->status = 1;
          $outlineTable->save($outline);
       }
    }
    
    public function deactivateCareer($id)
    {
       $careerTable    = $this->serviceLocator->get('Admin\Model\CareerTable');

       $career                 = new Career();
       $career->id             = $id;
       $career->status         = 0;
       $career->total_duration = 0;


       $careerTable->save($career, array('status', 'total_duration'));

       $careerOutlineTable = $this->serviceLocator->get('Admin\Model\CareerOutlineTable');
       $careerOutlineTable->delete(0, array('career_id' => $id));
    }

    public function activateCareer($id)
    {
       $careerTable    = $this->serviceLocator->get('Admin\Model\CareerTable');
       $career         = new Career();
       $career->id     = $id;
       $career->status = 1;

       $careerTable->save($career, array('status'));
    }

    //to be used later when moving completely to use Zend
    //to calculate the career duration after deactivation of the outline
    protected function calculateCareerDuration($outlineId = NULL)
    {
//       $duration  = 0;
//
//       $careerOutlineTable = $this->serviceLocator->get('Admin\Model\CareerOutlineTable');
//       $outlineTable       = $this->serviceLocator->get('Admin\Model\OutlineTable');
//       $careerOutlines     = $careerOutlineTable->get(0, array('outline_id' => $outlineId));
//
//
//       foreach ($careerOutlines as $careerOutline) {
//           $outline   = $outlineTable->get($careerOutline->outline_id);
//           $duration -= $outline->duration;
//       }
//
//
//       var_dump($duration);exit;
//       return $duration;
    }
 }