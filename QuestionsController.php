<?php
require_once ("database/Database.php");

class QuestionsController
{

    private $connection;

    
    public function __construct()
    {
        
        $this->connection = (new Database())->getConnection();
    }


    public function createTest($teacher_id, $name, $start, $end, $total_points)
    {
            $stmt = $this->connection->prepare('INSERT INTO tests (teacher_id, name, time_opened, time_closed, total_points) VALUES (:teacher_id, :name, :time_opened, :time_closed, :total_points)');
            $stmt->bindParam(':teacher_id', $teacher_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':time_opened', $start);
            $stmt->bindParam(':time_closed', $end);
            $stmt->bindParam(':total_points', $total_points);
            
            $stmt->execute();
           

            return (int)$this->connection->lastInsertId();
            
        
    }
    public function getTeacherId($teacher_email){
        
            $stmt = $this->connection->prepare('SELECT id FROM teachers WHERE email = :email');
            $stmt->bindParam(':email', $teacher_email);
            $stmt->execute();
            $teacher_id = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$teacher_id['id'];
        
    }
    public function updateQuestionWithTestId($array_questions_id, $test_id){
        foreach ($array_questions_id as $question_id){
            $stmx = $this->connection->prepare('SELECT total_points FROM tests WHERE test_id = :test_id');
            $stmx->bindParam(':test_id', $test_id);
            $stmx->execute();
            $points = $stmx->fetch(PDO::FETCH_ASSOC);
            $points_question = (int)($points['total_points']/count($array_questions_id));
            $stmt = $this->connection->prepare('UPDATE questions SET test_id = :test_id, points = :points WHERE id = :id');
            $x = (int)$question_id;
            $stmt->bindParam(':test_id', $test_id);
            $stmt->bindParam(':id', $x);
            $stmt->bindParam(':points', $points_question);
            $stmt->execute();

        }
    }
    public function createQuestion($question, $name)
    {
        $stmt = $this->connection->prepare("INSERT INTO questions (question, name) VALUES (:question, :name)");
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        
        return (int)$this->connection->lastInsertId();
        
    }


    public function getAllTests()
    {
        $res = $this->connection->query('select * from tests');
        $tests = array();
        $a = $res->fetchAll();
        array_push($tests, $a);
        unset($stmt);
        return $tests;
    }

    public function getQuestionById($question_id)
    {
        $stmt = $this->connection->prepare('select * from questions where id = ?');
        $stmt->bindParam(':id', $question_id);
        $stmt->execute();
        $test = $stmt->fetch();
        unset($stmt);
        return $test;
    }

    public function getTestById($test_id)
    {
        $stmt = $this->connection->prepare('select * from tests where test_id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->execute();
        $test = $stmt->fetch();
        unset($stmt);
        return $test;
    }

    public function getTestQuestions($test_id)
    {
        $stmt = $this->connection->prepare('select * from questions where test_id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->execute();
        $res = $stmt->fetch();
        $questions = array();
        $a = $res->fetchAll();
        array_push($questions, $a);
        unset($stmt);
        return $questions;
    }

    private function updateTotalTestPoints($test_id, $points)
    {
        $stmt = $this->connection->prepare('update tests set total_points = (total_points + ?) where tests.id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->bindParam(':total_points', $points);
        $stmt->execute();
        unset($stmt);
    }


    private function createAnswer($question_id, $answer)
    {
        $stmt = $this->connection->prepare('insert into questions (question_id, answer) value (?, ?)');
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':answer', $answer);
        $stmt->execute();
        unset($stmt);
        return $this->connection->lastInsertId();
    }

    function latexParserToDatabase($file,$question_id){
        //parse sections
        //$sql = "INSERT INTO examples (section,task,solution,solution_clear,question_id) VALUES (?,?,?,?,?)";
        $pattern = '/section[*][{](.*?)(nd[{]solution){1}/s';
        preg_match_all($pattern, $file, $sections);
        foreach ($sections[0] as $match) {
            //parse sections numbers
            $pattern = '/((?<=section[*][{])(.*?)(?=})){1}/';
            preg_match($pattern, $match, $temp);
            //var_dump($temp[0]);
            $sectionNumbers=$temp[0];
            //parse tasks
            $pattern = '/((?<=begin[{]task[}])(.*?)(?=\\\end[{]task)){1}/s';
            preg_match($pattern, $match, $temp);
            $tasks=$temp[0];
            //parse solutions
            $pattern = '/((?<=begin[{]solution[}])(.*?)(?=\\\end[{]solution)){1}/s';
            preg_match($pattern, $match, $temp);
            $solutions=$temp[0];
            $solutionsClear=latexMathToTxtMath($temp[0]);

            $stmt = $this->connection->prepare("INSERT INTO examples (section,task,solution,solution_clear,question_id) VALUES (:section, :task, :solution, :solution_clear, :question_id)");
            $stmt->bindParam(':section', $sectionNumbers, PDO::PARAM_STR);
            $stmt->bindParam(':task', $tasks, PDO::PARAM_STR);
            $stmt->bindParam(':solution', $solutions, PDO::PARAM_STR);
            $stmt->bindParam(':question_id', $question_id);
            if(gettype($solutionsClear)=='array'){
            $stmt->bindParam(':solution_clear', $solutionsClear[0], PDO::PARAM_STR);
            }
            else{
                $stmt->bindParam(':solution_clear', $solutionsClear, PDO::PARAM_STR);
            }
            $stmt->execute();
            unset($stmt);
        }
    }
    
    function latexMathToTxtMath($latex){
      $pattern = '/((?<=begin[{]equation[*][}])(.*?)(?=\\\end{equation\*})){1}/s';
      preg_match($pattern, $latex, $temp);
      $search = ['\s', '\\left', '\\right','[',']','{','}','"','\\','/',' ',];
      $replace = ['', '', '','(',')','(',')','','','',''];
      $str = str_replace($search, $replace, $temp);
      $pattern = '/((?<=[=])(.*)(?=[=])){1}/s';
      preg_match($pattern, $str[0], $temp);
      if($temp==null){
        $pattern = '/((?<=[=])(.*)){1}/s';
        preg_match($pattern, $str[0], $temp);
        if($temp==null){
           $temp=$str[0];
        }
      }
          $str = preg_replace('/dfrac\((.+)\)\((.+)\)/', '(($1)/($2))', $temp);
          $str = preg_replace('/frac\((.+)\)\((.+)\)/', '(($1)/($2))', $str);
          $str = preg_replace('/(\))(\()/', '$1*$2', $str);
          $str = preg_replace('/(\))(\w+)/', '$1*$2', $str);
          $str = preg_replace('/(\w+)(\()/', '$1*$2', $str);
          $str = preg_replace('/([a-zA-Z]+)(\d)/', '$1*$2', $str);
          $str = preg_replace('/(\d)([a-zA-Z]+)/', '$1*$2', $str);
          $exclude = array("pi", "e", "squar","pi","sin","cos","log","tan","int","lim");
          $replaced = preg_replace_callback('/\b\w+\b/', function($match) use ($exclude) {
              $word = strtolower($match[0]);
              if (in_array($word, $exclude)) {
                  return $word;
              } else {
                  return preg_replace('/(\D+){1}/', '17.5', $match[0]);
              }
          }, $str);
          return $replaced;
        }
    
        function taskParse($latexTask){
          $pattern = '/((?<=begin[{]task[}])(.*?)\\\end{equation\*}){1}/s';
          preg_match($pattern, $latexTask, $temp);
          return $temp[0];
        }

}