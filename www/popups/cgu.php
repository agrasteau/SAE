<?php
    require "language.php" ; 
?>
<div class="popup">
    <div class="contenuPopupAlt">
        <form method="post">
				<input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
		</form>
        <p class="titrePopup"><?php echo $htmlCondGenUti; ?></p>
        <div>
            <div class="formPopup">
                <p class="text">
                    <?php echo $htmlTxtCGU; ?>
                </p>
            </div>
        </div>  
    </div>
</div>
