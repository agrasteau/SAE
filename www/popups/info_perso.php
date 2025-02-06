<?php
    require "language.php" ; 
?>
<?php
        if (isset($_POST['formClicked'])){
            unset($_POST['formClicked']);
            require 'traitements/update_user_info.php';
            $_SESSION['actualiser'] = true;
        }
        if(isset($_POST['deconnexion'])){
            unset($_POST['deconnexion']);
            require 'traitements/log_out.php';
            $_SESSION['actualiser'] = true;
        }
        ?>
    <div class="popup">
    <div class="contenuPopup">
        <div style="display:flex;justify-content:space-between;">
            <form method="post">
				<input class="lienPopup" type="submit" value="<?php echo $htmlSeDeconnecter?>" name="formClicked">
                <input type="hidden" value='info_perso' name="popup">
                <input type="hidden" name="deconnexion">
		    </form>
            <form method="post">
				<input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
		    </form>
        </div>
        <p class="titrePopup"><?php echo $htmlInformationsPersonelles?></p>
        <div>
        <?php
        require 'traitements/chargement_info_perso.php';
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {?>
                <form class="formPopup" action='traitements/update_user_info.php' method="post">
                    <input type="hidden" value='info_perso' name="popup">
                    <!--  Set default values to current user information -->
                    <div>
                        <label for="new_nom"><?php echo $htmlNomDeuxPoints?></label>
                        <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_nom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value=<?php echo ($row["Nom_Uti"]) ?>>
                    </div>
                    <div>
                        <label for="new_prenom"><?php echo $htmlPrénomDeuxPoints?></label>
                        <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_prenom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value=<?php echo ($row["Prenom_Uti"]) ?>>
                    </div>
                    <div>
                        <label><?php echo $htmlAdrPostDeuxPoints?></label>
                        <label><?php echo ($row["Adr_Uti"])?></label>
                    </div>
                    <div>
                        <label for="rue"><?php echo $htmlRueDeuxPoints?></label>
                        <input class="zoneDeTextePopup" type="text" name="rue" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}"  title="<?php echo $htmlConditionsRue; ?>" required>
                    </div>
                    <div>
                            <label for="code"><?php echo $htmlCodePostDeuxPoints?></label>
                            <input class="zoneDeTextePopup" type="text" name="code" pattern="^\d{5}$" title="<?php echo $htmlConditionsCodePostal; ?>" required>
                    </div>
                    <div>
                        <label for="ville"><?php echo $htmlVilleDeuxPoints?></label>
                        <input class="zoneDeTextePopup" type="text" name="ville" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsVille; ?>" required>
                    </div>
                    <div>
                        <label for="ville"> mot de passe actuel </label>
                        <input class="zoneDeTextePopup" type="password" name="pwd" required>
                    </div>
                    <div>
                        <?php
                        if (isset($_SESSION['erreur'])) {
                            $erreur = $_SESSION['erreur'];
                            echo '<p class="erreur">'.$erreur.'</p>';
                        }
                        ?>
                    </div>
                    <input class="boutonPopup" type="submit" name="formClicked" value="<?php echo $htmlModifier?>">
                </form>
                <a href="traitements/del_acc.php"><button><?php echo $htmlSupprimerCompte?></button></a>
                
                <?php if((isset($_SESSION['isProd']) and $_SESSION['isProd'])){?> 
                <a href="./addProfilPicture.php"><button><?php echo 'ajouter une photo de profil'?></button></a>
                <?php } ?>
                <?php
            }
        } else {
            ?><p><?php echo $htmlAucunResultatCompte?></p><?php
        }
        ?>
        </div>
    </div>
</div>
