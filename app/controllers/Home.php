<?php 
    session_start();
    class Home extends Controller {
        private $userModel;

        public function __construct(){
            $this->userModel = $this->model('Utilisateur', null, '', '', '', '', null);
        }

        public function index() {
            $this->view('home/index', []);
        }

        public function logout(){
            if (isset($_SESSION['user_id'])) {
                session_unset();
                session_destroy();
                header("Location: index");  
            }
        }

        public function login(){
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
                if (isset($_POST['email']) && isset($_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                    $email = trim($_POST['email']);
                    $password = $_POST['password'];
                    $user = Utilisateur::login($email);
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id_utilisateur'];
                        $_SESSION['role_id'] = $user['id_role'];
                        $_SESSION['user_name'] = $user['nom'];
                        $_SESSION['isactive'] = true;
                        if ($_SESSION['role_id'] == 1) {
                            header("Location: " . APPROOT . "admin/dashboard");
                        } else if ($_SESSION['role_id'] == 2){
                            header("Location: " . APPROOT . "teacher/dashboard");
                        } else {
                            header("Location: ../CoursController/allcours");
                        }
                } else {
                    echo "<script>alert('Le mot de passe est incorrecte !');</script>";
                    header("Refresh: 0; URL=index");
                }
            } else {
                echo "<script>alert('Veuillez remplir tous les champs !');</script>";
                header("Refresh: 0; URL=index");
            }
        }
    }
    public function register(){
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
            // Validation des entrées
            if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['type_user'])) {
                echo "Tous les champs sont obligatoires.";
                exit;
            }
        
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $type_user = $_POST['type_user'];
        
            try {
                if ($type_user == '3') {
                    $status = 'active';
                    $user = $this->model('Etudiant',null, $nom, $prenom, $email, $password, $status);
                } elseif ($type_user == '2') {
                    $status = 'inactive';
                    $user = $this->model('Enseignant',null, $nom, $prenom, $email, $password, $status);
                } else {
                    throw new Exception("Type d'utilisateur non valide");
                }
        
                if ($user->register()) {
                    session_start();
                    $_SESSION['user_id'] = $user->get_id();
                    $_SESSION['role_id'] = $user->get_role_id();
                    $_SESSION['user_name'] = $user->get_nom();
        
                    header("Location: index");
                    
                } else {
                    throw new Exception("Échec de l'enregistrement");
                }
            } catch (Exception $e) {
                echo "Erreur : " . $e->getMessage();
            }
        }
    }
}
?>