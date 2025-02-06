<div class="popup">
    <div class="contenuPopup">
        <form method="post">
            <input type="submit" value="" class="boutonQuitPopup">
            <input type="hidden" name="popup" value="">
        </form>
        <p class="titrePopup"><?php echo $htmlSInscrire; ?></p>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" accept=".png" required>
            <input type="submit">
        </form>
    </div>
</div>