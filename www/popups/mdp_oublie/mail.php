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
            <p class="titrePopup"><?php echo $htmlReinMdp; ?></p>
        </div>
        <form class="formPopup" method="post">
            <input type="hidden" name="popup" value="mdp_oublie/mail">
            <label><?php echo $htmlEntrerAdrMail; ?></label>
            <input type="email" id="email" pattern="[A-Za-z0-9._-]{1,20}@[A-Za-z0-9.-]{1,16}\.[A-Za-z]{1,4}" name="email" required>
            <label><?php echo $htmlCodeEnvoyePourRecupMdp; ?></label>
            <?php
            if (isset($_SESSION['erreur'])) {
                $erreur = $_SESSION['erreur'];
                echo '<p class="erreur">'.$erreur.'</p>';
            }
            ?>
            <input name="formClicked" type="submit" value="<?php echo $htmlEnvoiCode; ?>" class="boutonPopup">
        </form>
    </div>
</div>
<?php
if (isset($_POST['formClicked'])){
    unset($_POST['formClicked']);
    require 'traitements/mdp_oublie/mail.php';
    $_SESSION['actualiser'] = true;
}
?>
