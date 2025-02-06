<?php
    require "language.php" ; 
?>
<div class="popup">
    <div class="contenuPopup">
        <div>
            <form method="post">
                    <input type="submit" value="" class="boutonQuitPopup">
                    <input type="hidden" name="popup" value="">
            </form>
            <p class="titrePopup"><?php echo $htmlNouveauMdp; ?></p>
        </div>
        <form class="formPopup" method="post">
            <label for="pwd1"><?php echo $htmlNouveauMdpDeuxPoints; ?></label>
            <input type="text" name="pwd1">
            <label for="pwd2"><?php echo $htmlResaisiMdp; ?></label>
            <input type="text" name="pwd2">
            
            <input type="hidden" value="mdp_oublie/update" name="popup" >
            <?php
            if (isset($_SESSION['erreur'])) {
                $erreur = $_SESSION['erreur'];
                echo '<p class="erreur">'.$erreur.'</p>';
            }
            ?>
            <input name="formClicked" type="submit" value="<?php echo $htmlChangerMdp; ?>" class="boutonPopup">
        </form>
    </div>
</div>
<?php
if (isset($_POST['formClicked'])){
    unset($_POST['formClicked']);
    require 'traitements/mdp_oublie/update.php';
    $_SESSION['actualiser'] = true;
}
?>



<form method="post">
    
</form>