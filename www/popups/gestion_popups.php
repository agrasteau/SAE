<?php
if (isset($_POST['popup'])){
    switch ($_POST['popup']) {
        case '':
            unset($_POST['popup']);
            unset($_SESSION['tempIsProd']);
            unset($_SESSION['tempIsAdmin']);
            break;

        case 'sign_up_client':
            $_SESSION['tempIsProd'] = false;
            $_POST['popup'] = 'sign_up';
            require $_POST['popup'].".php";
            break;

        case 'sign_up_prod':
            $_SESSION['tempIsProd'] = true;
            $_POST['popup'] = 'sign_up';
            require $_POST['popup'].".php";
            break;

        case 'sign_in_client':
            if(isset($_SESSION['Mail_Uti'])){
                $_POST['popup'] = 'info_perso';
                require $_POST['popup'].".php";
            }else{
            $_SESSION['tempIsAdmin'] = false;
            require "sign_in.php";
            }
            break;

        case 'sign_in_admin':
            $_SESSION['tempIsAdmin'] = true;
            require "sign_in.php";
            break;
        
        default:
            require $_POST['popup'].".php";
            break;
    }
    if (isset($_SESSION['actualiser']) and $_SESSION['actualiser']){
        $_SESSION['actualiser'] = false;
        $_SESSION['tempPopup'] = $_POST['popup'];
        //header('refresh:0');
    }
}
?>