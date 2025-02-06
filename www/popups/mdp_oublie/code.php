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
            <p class="titrePopup"><?php echo $htmlValidationCode; ?></p>
        </div>
        <form class="formPopup" method="post">
            <input type="text" id="code" name="code" required>
            <label for="code"><?php echo $htmlCodeReinDeuxPoints; ?></label>
            <input type="hidden" value="mdp_oublie/code" name="popup" >
            <?php
            if (isset($_SESSION['erreur'])) {
                $erreur = $_SESSION['erreur'];
                echo '<p class="erreur">'.$erreur.'</p>';
            }
            ?>
            <input name="formClicked" type="submit" value="<?php echo $htmlVerifierCode; ?>" class="boutonPopup">
        </form>
    </div>
</div>
<?php
if (isset($_POST['formClicked'])){
    unset($_POST['formClicked']);
    require 'traitements/mdp_oublie/code.php';
    $_SESSION['actualiser'] = true;
}
?>