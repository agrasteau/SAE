
-- III) PROCÉDURES

-- 1) Procédure qui envoie un message à tous les utilisateurs
DELIMITER $$
CREATE OR REPLACE PROCEDURE broadcast_Utilisateur(
  IN emetteur INT,
  IN contenuMsg VARCHAR(4096)
)
BEGIN
  DECLARE idUti INT;
  DECLARE nbMessage INT;

  -- utilisation d'une boucle pour parcourir tous les utilisateurs, sauf l'émetteur
  DECLARE loop_finished INT DEFAULT 0;
  DECLARE cursor_Broadcast_Utilisateur CURSOR FOR
    SELECT Id_Uti FROM UTILISATEUR
    WHERE Id_Uti != emetteur;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loop_finished=1;

  -- la variable @nbMessage va permettre de créer un identifiant unique qui s'incrémente au fur et à mesure
  SET nbMessage = (SELECT MAX(Id_Msg) FROM MESSAGE) + 1;

  OPEN cursor_Broadcast_Utilisateur;
  FETCH cursor_Broadcast_Utilisateur INTO idUti;
  
  WHILE loop_finished=0 DO
    -- Insertion du message dans la table avec l'identifiant, la date d'envoi, la date d'expiration (date d'envoi + 12 mois par défaut), le contenu du message, l'émetteur et 		le récepteur
    INSERT INTO MESSAGE(Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
    VALUES (nbMessage, NOW(), DATE_ADD(NOW(), INTERVAL 12 MONTH), contenuMsg, emetteur, idUti);

    -- Incrémentation de l'identifiant
    SET nbMessage = nbMessage + 1;
	FETCH cursor_Broadcast_Utilisateur INTO idUti;
  END WHILE;
  CLOSE cursor_Broadcast_Utilisateur;
  
END $$
DELIMITER ;

-- CALL broadcast_Utilisateur(1, 'test');


-- #########################


-- 2) Procédure qui envoie un message à tous les producteurs

DELIMITER $$
CREATE OR REPLACE PROCEDURE broadcast_Producteur(
  IN emetteur INT,
  IN contenuMsg VARCHAR(4096)
)
BEGIN
  DECLARE idProd INT;
  DECLARE nbMessage INT;

  -- utilisation d'une boucle pour parcourir tous les producteurs, sauf l'émetteur
  DECLARE loop_finished INT DEFAULT 0;
  DECLARE cursor_Broadcast_Producteur CURSOR FOR
    SELECT Id_Prod FROM PRODUCTEUR
    WHERE Id_Prod != emetteur;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loop_finished=1;

  -- la variable @nbMessage va permettre de créer un identifiant unique qui s'incrémente au fur et à mesure
  SET nbMessage = (SELECT MAX(Id_Msg) FROM MESSAGE) + 1;

  OPEN cursor_Broadcast_Producteur;
  FETCH cursor_Broadcast_Producteur INTO idProd;
  
  WHILE loop_finished=0 DO
    -- Insertion du message dans la table avec l'identifiant, la date d'envoi, la date d'expiration (date d'envoi + 12 mois par défaut), le contenu du message, l'émetteur et le récepteur
    INSERT INTO MESSAGE(Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
    VALUES (nbMessage, NOW(), DATE_ADD(NOW(), INTERVAL 12 MONTH), contenuMsg, emetteur, idProd);

    -- Incrémentation de l'identifiant
    SET nbMessage = nbMessage + 1;
	FETCH cursor_Broadcast_Producteur INTO idProd;
  END WHILE;
  CLOSE cursor_Broadcast_Producteur;
  
END $$
DELIMITER ;

-- CALL broadcast_Producteur(1, 'testProducteur');



-- #########################


-- 3) Procedure prenant en paramètre un identifiant d'utilisateur et qui affiche tous les autres utilisateurs avec qui il est en contact

DELIMITER $$
CREATE OR REPLACE PROCEDURE listeContact(IN id_uti INT)
BEGIN
        SELECT UTILISATEUR.Id_Uti, Nom_Uti, Prenom_Uti, MAX(Date_Msg) as Date_Msg
        FROM UTILISATEUR
        JOIN MESSAGE 
            ON UTILISATEUR.Id_Uti=MESSAGE.Emetteur 
            OR UTILISATEUR.Id_Uti=MESSAGE.Destinataire
        WHERE 
            UTILISATEUR.Id_Uti IN (SELECT Destinataire FROM MESSAGE WHERE Emetteur=id_uti)
            OR UTILISATEUR.Id_Uti in (SELECT Emetteur FROM MESSAGE WHERE Destinataire=id_uti)
        GROUP BY UTILISATEUR.Id_Uti, Nom_Uti, Prenom_Uti
        ORDER BY Date_Msg DESC;
END $$
DELIMITER ;


-- call listeContact(2);


-- #########################


-- 4) Procédure qui renvoie la discussion entre deux utilisateurs

DELIMITER $$
-- conversation prend en paramètre deux identifiants d'utilisateurs
CREATE OR REPLACE PROCEDURE conversation(IN moi INT, IN autrePersonne INT)
BEGIN
        SELECT Contenu_Msg, Date_Msg, Emetteur 
        FROM MESSAGE 
        WHERE 
            (Emetteur=moi AND Destinataire=autrePersonne)
            OR (Destinataire=moi AND Emetteur=autrePersonne) 
        ORDER BY Date_Msg ASC;
END $$
DELIMITER;

-- call conversation(2,6);



-- #########################


-- 5) deleteMsg est une procédure qui supprime tous les messages dont la date est expirée

DELIMITER $$
-- deleteMsg ne prend pas de paramètres
CREATE OR REPLACE PROCEDURE deleteMsg()
BEGIN
  -- Il faut supprimer tous les messages dont la date d'expiration est passée.
  -- Pour ce faire, nous comparons la différence entre la date d'expiration et l'heure actuelle. Si la différence est négative, le délai est dépassé.
  DELETE FROM MESSAGE
  WHERE TIMESTAMPDIFF(MINUTE, NOW(), Date_Expi_Msg) <= 0;
END $$


-- INSERT INTO MESSAGE(Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire) VALUES (7777777, '2024-12-31 01:15:00', '2010-12-31 01:15:00', 'voici un estx', 1, 2);
-- CALL deleteMsg();
-- SELECT * FROM message;



-- #########################


-- 6) Chiffrement du mot de passe avec le chiffre de Vigenère (clef : conceptiondelabasededonnees)
-- C'est un système de chiffrement par substitution, comme le Code de César mais avec plusieurs lettres (= la clef).
-- Il permet ainsi de chiffrer de manière différente le même mot ou la même lettre en fonction de la clé.

-- La procédure prend en paramètre un mot de passe et un utilisateur
DELIMITER $$
CREATE OR REPLACE PROCEDURE chiffrementV(IN id_Uti INT, INOUT monMdp VARCHAR(50))
BEGIN
  -- Itérateur qui va parcourir le mot de passe
  DECLARE iterator INT DEFAULT 1;

  -- Clef de chiffrement
  DECLARE clef VARCHAR(50) DEFAULT 'conceptiondelabasededonnees';

  -- Lettres temporaires
  DECLARE lettreMdp INT;
  DECLARE lettreClef INT;

  -- Nouveau mot de passe crypté
  DECLARE mdpCrypte VARCHAR(50) DEFAULT '';

  -- Tant que le mot de passe n'est pas totalement parcouru
  WHILE (iterator <= LENGTH(monMdp)) DO
    -- On récupère le code ACSII de la lettre du mot de passe à l'emplacement i
    SET lettreMdp = ASCII(SUBSTRING(monMdp, iterator, 1));
    -- On récupère le code ACSII de la lettre de la clef à l'emplacement i, modulo[taille de la clef] si cette dernière est trop petite par rapport au mot de passe.
    SET lettreClef = ASCII(SUBSTRING(clef, (iterator - 1) % LENGTH(clef) + 1, 1));


    -- On concatène au mot de passe crypté la somme des codes ACSII pour obtenir une nouvelle lettre codée 
    SET mdpCrypte = CONCAT(mdpCrypte, CHAR((((lettreMdp - 33) + (lettreClef - 33))%93)+33));

    -- On incrémente l'itérateur
    SET iterator = iterator + 1;
  END WHILE;

	SET monMdp = mdpCrypte;
END $$

DELIMITER ;


-- CALL chiffrementV(1, 'password123?!@!');
-- SELECT * FROM UTILISATEUR WHERE Id_Uti=1;

-- Procédure de vérification du mot de passe

-- La procédure prend en paramètre un mot de passe et un utilisateur
DELIMITER $$
CREATE OR REPLACE PROCEDURE verifMotDePasse(IN id_Uti INT, IN mdpAVerifier VARCHAR(50))
BEGIN
  -- Itérateur qui va parcourir le mot de passe
  DECLARE iterator INT DEFAULT 1;

  -- Clef de chiffrement
  DECLARE clef VARCHAR(50) DEFAULT 'conceptiondelabasededonnees';

  -- Lettres temporaires
  DECLARE lettreMdp INT;
  DECLARE lettreClef INT;

  -- Nouveau mot de passe crypté
  DECLARE mdpCrypte VARCHAR(50) DEFAULT '';

  -- Tant que le mot de passe n'est pas totalement parcouru
  WHILE (iterator <= LENGTH(mdpAVerifier)) DO
    -- On récupère le code ACSII de la lettre du mot de passe à l'emplacement i
    SET lettreMdp = ASCII(SUBSTRING(mdpAVerifier, iterator, 1));
    -- On récupère le code ACSII de la lettre de la clef à l'emplacement i, modulo[taille de la clef] si cette dernière est trop petite par rapport au mot de passe.
    SET lettreClef = ASCII(SUBSTRING(clef, (iterator - 1) % LENGTH(clef) + 1, 1));


    -- On concatène au mot de passe crypté la somme des codes ACSII pour obtenir une nouvelle lettre codée 
    SET mdpCrypte = CONCAT(mdpCrypte, CHAR((((lettreMdp - 33) + (lettreClef - 33))%93)+33));

    -- On incrémente l'itérateur
    SET iterator = iterator + 1;
  END WHILE;

	-- Renvoie 1 si correspond ou 0 si ne correspond pas
	IF (mdpCrypte=(SELECT Pwd_Uti FROM UTILISATEUR WHERE UTILISATEUR.Id_Uti=id_Uti)) THEN
		SELECT 1;
	ELSE
		SELECT 0;
	END IF;
    
END $$

DELIMITER ;


-- CALL verifMotDePasse(1, 'password123');
-- CALL verifMotDePasse(1, 'password125');



-- procedure pour envoyer un message 👍😁

DELIMITER $$
CREATE OR REPLACE PROCEDURE envoyerMessage(
  IN emetteur INT,
  IN destinataire INT,
  IN contenuMsg VARCHAR(4096)
)
BEGIN
	DECLARE nb INT;
	SET nb = (SELECT MAX(Id_Msg) FROM MESSAGE) + 1;
    INSERT INTO MESSAGE(Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
    VALUES (nb, NOW(), DATE_ADD(NOW(), INTERVAL 12 MONTH), contenuMsg, emetteur, destinataire);

  
END $$

-- procedure pour savoir si l'utilisateur est un producteur et nous renvoie sa profession si oui 👍😁

DELIMITER $$

CREATE OR REPLACE PROCEDURE isProducteur(
	IN Id_Uti INT
)
BEGIN
	IF Id_Uti IN (SELECT Id_Uti FROM PRODUCTEUR) THEN
    	SELECT concat(' - ', (SELECT Prof_Prod FROM PRODUCTEUR WHERE PRODUCTEUR.Id_Uti=Id_Uti)) as result;
    ELSE
    	SELECT '';
    END IF;
    
END $$

DELIMITER ;


-- ###########################################################################################################################################################


-- Procédure qui envoie un report de bug à tous les administrateurs
DELIMITER $$
CREATE OR REPLACE PROCEDURE broadcast_Admin(
  IN emetteur INT,
  IN contenuMsg VARCHAR(4096)
)
BEGIN
  DECLARE idAdmin INT;
  DECLARE nbMessage INT;

  -- utilisation d'une boucle pour parcourir tous les utilisateurs, sauf l'émetteur
  DECLARE loop_finished INT DEFAULT 0;
  DECLARE cursor_Broadcast_Admin CURSOR FOR
    SELECT Id_Uti FROM ADMINISTRATEUR
    WHERE Id_Uti != emetteur;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET loop_finished=1;

  -- la variable @nbMessage va permettre de créer un identifiant unique qui s'incrémente au fur et à mesure
  SET nbMessage = (SELECT MAX(Id_Msg) FROM MESSAGE) + 1;

  OPEN cursor_Broadcast_Admin;
  FETCH cursor_Broadcast_Admin INTO idAdmin;
  
  WHILE loop_finished=0 DO
    -- Insertion du message dans la table avec l'identifiant, la date d'envoi, la date d'expiration (date d'envoi + 12 mois par défaut), le contenu du message, l'émetteur et 		le récepteur
    INSERT INTO MESSAGE(Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
    VALUES (nbMessage, NOW(), DATE_ADD(NOW(), INTERVAL 12 MONTH), contenuMsg, emetteur, idAdmin);

    -- Incrémentation de l'identifiant
    SET nbMessage = nbMessage + 1;
	FETCH cursor_Broadcast_Admin INTO idAdmin;
  END WHILE;
  CLOSE cursor_Broadcast_Admin;
  
END $$
DELIMITER ;

CALL broadcast_Admin(7, 'ceci est un bogue');


-- ###########################################################################################################################################################

-- DÉCLENCHEURS

-- 1) Chiffrement du mot de passe lors de la création (insertion) d'un nouvel utilisateur

DELIMITER $$

CREATE OR REPLACE TRIGGER trigger_insert_verif_cryptage 
	BEFORE INSERT
    ON UTILISATEUR
	FOR EACH ROW
BEGIN
  DECLARE Id_Uti_temp INT;
  DECLARE Pwd_Uti_temp VARCHAR(50);

  -- Récupérer les valeurs insérées dans la table
  SET Id_Uti_temp = NEW.Id_Uti;
  SET Pwd_Uti_temp = NEW.Pwd_Uti;

  -- Appeler la procédure de chiffrement
  CALL chiffrementV(Id_Uti_temp, Pwd_Uti_temp);
  
  SET NEW.Pwd_Uti = Pwd_Uti_temp;
  
END $$

DELIMITER ;


DELIMITER $$

CREATE OR REPLACE TRIGGER trigger_update_verif_cryptage 
	BEFORE UPDATE
    ON UTILISATEUR
	FOR EACH ROW
BEGIN
  DECLARE Id_Uti_temp INT;
  DECLARE Pwd_Uti_temp VARCHAR(50);

  -- Récupérer les valeurs insérées dans la table
  SET Id_Uti_temp = NEW.Id_Uti;
  SET Pwd_Uti_temp = NEW.Pwd_Uti;

  -- Appeler la procédure de chiffrement
  CALL chiffrementV(Id_Uti_temp, Pwd_Uti_temp);
  
  SET NEW.Pwd_Uti = Pwd_Uti_temp;
  
END $$

DELIMITER ;
