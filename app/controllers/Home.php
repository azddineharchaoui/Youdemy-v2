<?php 
    require_once("../models/Utilisateur.php");
    session_start();
    class Home extends Controller {
        public function index($name = '') {
            // $user = $this->model('Utilisateur', 
            //     null,           
            //     $name,          
            //     '',            
            //     '',            
            //     '',            
            //     null           
            // );
    
            $this->view('home/index', []);
        }
        // public function login()

        public function logout(){
            
        
            if (isset($_SESSION['user_id'])) {
                session_unset();
                session_destroy();
                header("Location: home/index");  
                exit();
            }
        }
        public function login(){

            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
                if (isset($_POST['email'], $_POST['password'])) {
                    $email = trim($_POST['email']);
                    $password = $_POST['password'];
                    $user = Utilisateur::login($email);
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id_utilisateur'];
                        $_SESSION['role_id'] = $user['id_role'];
                        $_SESSION['user_name'] = $user['nom'];
                        $_SESSION['isactive'] = true;
                        header("Location: ");
                } else {
                    echo "Veuillez remplir tous les champs.";
                    // header("Location: home/index");
                }
            }
        }
    }
?>