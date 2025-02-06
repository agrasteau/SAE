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
            <p class="titrePopup"><?php echo $htmlChoisirProfil; ?></p>
        </div>
        <div class="formPopup">
            <div class="alignementCentreCoteACote">
                <form method="post">
                        <input type="submit" value="<?php echo $htmlJeSuisClient; ?>" class="boutonPopup">
                        <input type="hidden" name="popup" value="sign_up_client">
                </form>
                <form method="post">
                        <input type="submit" value="<?php echo $htmlJeSuisProducteur; ?>" class="boutonPopup">
                        <input type="hidden" name="popup" value="sign_up_prod">
                </form>
            </div>
        </div>
    </div>
</div>
