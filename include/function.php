<?php
  function sanitizeValue(&$value){       // Pour nettoyer les saisie de l'utilisateur ( balise , injection et autres espaces)
    $value = trim(strip_tags($value));  // http://php.net/manual/en/function.strip-tags.php // http://php.net/manual/en/function.trim.php
                                         // Supprime les balise (strips_tags) et autres espaces saisie (trim);
  }
  function sanitizeArray(array $array){
    array_walk($array, 'sanitizeValue');   // http://php.net/manual/en/function.array-walk.php // utilse la fonction déclaré précedament et l'applique a chaque
  }                                        // ligne du tableau par reference

  function sanitizePost(){
        sanitizeArray($_POST)  ;
}

 function displayErrorClass($nomChamp, array $errors){
   if(isset($errors[$nomChamp])) {
     echo 'has-error';
   }
 }

 function displayErrorMsg($nomChamp, array $errors){
   if(isset($errors[$nomChamp])) {
     echo '<span class="help-block">'. $errors[$nomChamp] . '</span>';
   }
 }
 function isUserConnected() {
   return isset($_SESSION['utilisateur']);
 }
 function getUserFullName(){
   if(isUserConnected()) {
   return $_SESSION['utilisateur']['prenom'] . ' ' .
   $_SESSION['utilisateur']['nom'] ;
  }
  return '';
 }
 function isUserAdmin(){
   return isUserConnected() && $_SESSION['utilisateur']['statuts'] == 'admin';
 }
 function adminSecurity(){
   if(!isUserAdmin()){
     if(isUserConnected()) {
       header('Location: ' . RACINE_WEB . 'index.php');
     }else{
       header('Location: ' . RACINE_WEB . 'connexion.php');
     }
     die;
   }
 }
 function setFlashMessage($message, $type = "success")
{
    $_SESSION['flashMessage'] = ['message'=>$message, 'type'=>$type];
}

function displayFlashMessage()
{
    if(isset($_SESSION['flashMessage']))

   {
        $type = ($_SESSION['flashMessage']['type'] == "error") ? 'danger' : $_SESSION['flashMessage']['type'];
        $alert = '<div class="alert alert-'.$type.' role="alert">'.'<strong>'.$_SESSION['flashMessage']['message'].'</strong>'.'</div>';

       echo $alert;
        //supression du message de la session pour affichage one shot
        unset($_SESSION['flashMessage']);
    }
}
 ?>
