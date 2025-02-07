<?php 
    class CoursController extends Controller{

        public function __construct(){
        }
        public function allcours(){
            $coursesPerPage = 6; 
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
            $offset = ($page - 1) * $coursesPerPage; 

            $courseObj = $this->model('Cours_text', null, "", "", "", "", "");

            $courses = [];
            $totalCourses = 0;

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $courses = $courseObj->rechercherCours($_GET['search'], $coursesPerPage, $offset);
                $totalCourses = $courseObj->countCoursBySearch($_GET['search']);
            } else {
                $courses = $courseObj->listerTousCours($coursesPerPage, $offset);
                $totalCourses = $courseObj->countAllCours();
            }

            $totalPages = ceil($totalCourses / $coursesPerPage); 
            $data[] = [
                'coursesPerPage' => $coursesPerPage,
                'page' => $page,
                'offset' => $offset,
                'courses' => $courses,
                'totalCourses' => $totalCourses,
                'totalPages' => $totalPages
            ];
            $this->view('allcoursView', $data);
        }

        public function cours($id){

            $courseObj = $this->model('Cours_text', null, "", "", "", "", "");
            $cours = $courseObj->afficherCours($id);
        
            $data[] = [
                'cours' => $cours
            ];
            $this->view('coursView', $data);
        }
        public function mesCours(){
            session_start();
            if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3 ) {
                header('Location: ../home/index');
                exit();
            }
            
            $etudiant = $this->model('Etudiant',$_SESSION['user_id'], '', '', '', '', 'active');
            // var_dump($etudiant);
            $mesCours = $etudiant->getMesCours();
            // print_r($mesCours);
            $data = [
                'mesCours' => $mesCours
            ];
            $this->view('mesCoursView', $data);
        }
        public function inscrire(){
            session_start();
            if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
                header('Location: ../home/index');
                exit();
            }
            
            if (!isset($_POST['course_id']) || empty($_POST['course_id'])) {
                header('Location: allcours');
                exit();
            }
            $course_id = (int)$_POST['course_id'];

            $etudiant = $this->model('Etudiant', $_SESSION['user_id'], '', '', '', '', 'active');
            try {
                if ($etudiant->inscrireCours($course_id)) {
                    header("Location: cours/" . $course_id . "&success=1");
                } else {
                    header("Location: cours/" . $course_id . "&error=1");
                }
            } catch (Exception $e) {
                header("Location: cours/" . $course_id . "&error=" . urlencode($e->getMessage()));
            }

        }
    }
?>