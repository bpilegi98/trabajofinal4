<?php

namespace Controllers;

use DAO\UserRepository as UserRepository;
use Models\User as User;

class UserController
{


    public function signUpForm()
    {
        require_once(VIEWS_PATH . "signup.php");
    }

    public function userProfile()
    {
        require_once(VIEWS_PATH . "profile.php");
    }

    public function signUp($username, $password, $firstname, $lastname, $email, $dni)
    {
        $add = true;

        $userRepo = new UserRepository();
       foreach($userRepo->getAll() as $values){
           if($values->getEmail() == $email|| $values->getUserName() == $username){
               $add=false;
           }
        } 
        if($add){
            $user = new User(); //crea el nuevo usuario y setea los datos
            $user->setUserName($username);
            $user->setPassword($password);
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setPermissions(2);
            $user->setDni($dni);
            $userRepo->Add($user);

            $_SESSION["loggedUser"] = $user; //se setea el usuario en sesion a la variable session  
            require_once(VIEWS_PATH . "index.php"); //vista del home
        }
        else
        {   
            echo "No se ha podido registrar el usuario. Inténtelo de nuevo." . "<br>";
            $this->signUpForm(); //si no se pudo registrar el usuario se redirecciona al formulario para volver a ingresar datos
        }
    }



    public function logInForm()
    {
        require_once(VIEWS_PATH . "login.php");
    }

    public function logIn($user = null, $password = null)
    {

        $login = false;
        $userRepo = new UserRepository();
        $userList=$userRepo->getAll(); //levantar todos los usuarios registrados en el json hasta el momento (comprobado)
        $view = null;
        $i = 0;
        foreach ($userList as $values)
        {

            if (($values->getUserName() == $user) && ($values->getPassword() == $password)) 
            {   


                $login = true;
                $loggedUser = new User();
                $loggedUser->setId($values->getId());
                $loggedUser->setUserName($user);
                $loggedUser->setPassword($password);
                $loggedUser->setFirstname($values->getFirstName());
                $loggedUser->setLastname($values->getLastName());
                $loggedUser->setEmail($values->getEmail());
                $loggedUser->setPermissions($values->getPermissions());
                $loggedUser->setDni($values->getDni());
                $_SESSION["loggedUser"] = $loggedUser; //se setea el usuario en sesion a la variable session
                require_once(VIEWS_PATH . "index.php");
           }
        }
        
        if($login == false){
            $this->logInForm(); //al estar incorrectos los datos se redirecciona al formulario para volverlos a ingresar

            ?>
            <script>
                alert("Los datos ingresados son incorrectos. Intente nuevamente.");
            </script>
            <?php

        }
    }

    public function logOut()
    {
        unset($_SESSION["loggedUser"]); //se vacia la variable global
        //echo "Ha cerrado sesion correctamente"; ponerlo de forma mas bonita visualmente
        //var_dump($_SESSION["loggedUser"]);
        require_once(VIEWS_PATH . "index.php"); //vista del home
    }

    // public function checkSession($user=null)
    // {   
    //     if($user==null)
    //     {
    //         return false;
    //     }
    //     else
    //     {
    //         $userRepo = new UserRepository();
    //         $userRepo->getAll();

    //         while($flag==false && $i <count($userRepo))
    //         { 
    //             if($user->getUserName()==$userList->userNameAt($i))
    //             {
    //                 if($user->getPassword() == $userList->passwordAt($i)){ 
    //                     return $user;    
    //                 }
    //                 $flag=true;
    //             }
    //             $i++;         
    //         }

    //     }
    //     return false;
    // }
    public function checkSession()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        if (isset($_SESSION['loggedUser'])) {
            $userRepo = new UserRepository();

            $user = $userRepo->read($_SESSION['loggedUser']->getEmail());

            if ($user->getPassword() == $_SESSION['loggedUser']->getPassword())
                return $user;
        } else {
            return false;
        }
    }

    public function modifyUser($firstname, $lastname, $email, $username, $password)
    {
       
        
        $newUser = new User();
        $newUser->setId($_SESSION['loggedUser']->getId());
        $newUser->setFirstname($firstname);
        $newUser->setLastname($lastname);
        $newUser->setEmail($email);
        $newUser->setUsername($username);
        $newUser->setPassword($password);
         $userList = new UserRepository();
        $userList->edit($newUser);
        $this->logOut();
      // require_once(VIEWS_PATH . "profile.php");
    }
}
