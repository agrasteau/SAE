DROP TABLE IF EXISTS ADMINISTRATEUR;
DROP TABLE IF EXISTS CONTENU;
DROP TABLE IF EXISTS MESSAGE;
DROP TABLE IF EXISTS PRODUIT;
DROP TABLE IF EXISTS TYPE_DE_PRODUIT;
DROP TABLE IF EXISTS UNITE;
DROP TABLE IF EXISTS COMMANDE;
DROP TABLE IF EXISTS PRODUCTEUR;
DROP TABLE IF EXISTS STATUT;
DROP TABLE IF EXISTS UTILISATEUR;





CREATE TABLE UTILISATEUR(
   Id_Uti INT,
   Prenom_Uti VARCHAR(50) NOT NULL,
   Nom_Uti VARCHAR(50) NOT NULL,
   Mail_Uti VARCHAR(50) NOT NULL,
   Adr_Uti VARCHAR(200) NOT NULL,
   Pwd_Uti VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Uti),
   UNIQUE(Mail_Uti)
);

CREATE TABLE PRODUCTEUR(
   Id_Prod INT,
   Id_Uti INT NOT NULL,
   Prof_Prod VARCHAR(50),
   PRIMARY KEY(Id_Prod),
   UNIQUE(Id_Uti),
   FOREIGN KEY(Id_Uti) REFERENCES UTILISATEUR(Id_Uti)
);

CREATE TABLE ADMINISTRATEUR(
   Id_Admin INT,
   Id_Uti INT NOT NULL,
   PRIMARY KEY(Id_Admin),
   UNIQUE(Id_Uti),
   FOREIGN KEY(Id_Uti) REFERENCES UTILISATEUR(Id_Uti)
);

CREATE TABLE UNITE(
   Id_Unite INT,
   Nom_Unite VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Unite)
);

CREATE TABLE STATUT(
   Id_Statut INT,
   Desc_Statut VARCHAR(50) NOT NULL,
   PRIMARY KEY(Id_Statut)
);

CREATE TABLE TYPE_DE_PRODUIT(
   Id_Type_Produit INT,
   Desc_Type_Produit VARCHAR(50),
   PRIMARY KEY(Id_Type_Produit)
);

CREATE TABLE MESSAGE(
   Id_Msg INT,
   Emetteur INT NOT NULL,
   Destinataire INT NOT NULL,
   Date_Msg DATETIME NOT NULL,
   Date_Expi_Msg DATETIME,
   Contenu_Msg VARCHAR(4096),
   PRIMARY KEY(Id_Msg),
   FOREIGN KEY(Emetteur) REFERENCES UTILISATEUR(Id_Uti),
   FOREIGN KEY(Destinataire) REFERENCES UTILISATEUR(Id_Uti)
);

CREATE TABLE COMMANDE(
   Id_Commande VARCHAR(50),
   Id_Statut INT NOT NULL,
   Id_Prod INT NOT NULL,
   Id_Uti INT NOT NULL,
   PRIMARY KEY(Id_Commande),
   FOREIGN KEY(Id_Prod) REFERENCES PRODUCTEUR(Id_Prod),
   FOREIGN KEY(Id_Statut) REFERENCES STATUT(Id_Statut),
   FOREIGN KEY(Id_Uti) REFERENCES UTILISATEUR(Id_Uti)
);

CREATE TABLE PRODUIT(
   Id_Produit INT,
   Id_Prod INT NOT NULL,
   Nom_Produit VARCHAR(50) NOT NULL,
   Id_Type_Produit INT NOT NULL,
   Qte_Produit DECIMAL(15,2) NOT NULL,
   Id_Unite_Stock INT NOT NULL,
   Prix_Produit_Unitaire DECIMAL(15,2) NOT NULL,
   Id_Unite_Prix INT NOT NULL,
   PRIMARY KEY(Id_Produit),
   FOREIGN KEY(Id_Prod) REFERENCES PRODUCTEUR(Id_Prod),
   FOREIGN KEY(Id_Unite_Prix) REFERENCES UNITE(Id_Unite),
   FOREIGN KEY(Id_Unite_Stock) REFERENCES UNITE(Id_Unite),
   FOREIGN KEY(Id_Type_Produit) REFERENCES TYPE_DE_PRODUIT(Id_Type_Produit)
);

CREATE TABLE CONTENU(
   Id_Commande VARCHAR(50),
   Id_Produit INT,
   Qte_Produit_Commande INT NOT NULL,
   Num_Produit_Commande INT NOT NULL,
   PRIMARY KEY(Id_Commande, Id_Produit),
   FOREIGN KEY(Id_Commande) REFERENCES COMMANDE(Id_Commande),
   FOREIGN KEY(Id_Produit) REFERENCES PRODUIT(Id_Produit)
);


-- Script d'exploitation de la base de données sous MySQL : 
-- I - Rôles
-- II - Vues
-- III - Procédures Stockées
-- IV - Déclencheurs


-- #############################################################################################################################################################

 
-- I - RÔLES : 

-- 1) Rôle permettant de modifier ses informations personnelles

-- DROP ROLE IF EXISTS modif_info_perso;
CREATE OR REPLACE ROLE modif_info_perso;

GRANT SELECT, UPDATE ON UTILISATEUR TO modif_info_perso;


-- #############################################################################################################################################################


-- II - VUES : 

-- 1) Vue info_producteur
DROP VIEW IF EXISTS info_producteur;

-- Vue permettant de voir toutes les informations producteur, sauf le mot de passe.
-- Utilisation lors de l'affichage des informations d'un producteur
CREATE VIEW info_producteur
	AS 
	SELECT UTILISATEUR.Id_Uti, Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti, Prof_Prod
	FROM UTILISATEUR 
	JOIN PRODUCTEUR ON UTILISATEUR.Id_Uti=PRODUCTEUR.Id_Uti;

SELECT * FROM info_producteur;


-- #########################

-- 2) Vue info_utilisateur
DROP VIEW IF EXISTS info_utilisateur;

-- Vue permettant de voir toutes les informations utilisateur, sauf le mot de passe
-- Utilisation lors de l'affichage des informations de l'utilisateur
CREATE VIEW info_utilisateur
	AS 
	SELECT Id_Uti, Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti FROM UTILISATEUR;

SELECT * FROM info_utilisateur;



-- #########################


-- 3) Vue info_connection
DROP VIEW IF EXISTS info_connection;

-- Vue permettant de limiter l'accès des donnée au stict nécessaire pour se connecter
-- utilisation lors de la connection d'un untilisateur
CREATE VIEW info_connection
	AS 
	SELECT Id_Uti, Mail_Uti, Pwd_Uti FROM UTILISATEUR;

SELECT * FROM info_connection;



-- #########################


-- 4) Vue produits_commandes
DROP VIEW IF EXISTS produits_commandes;

-- Vue permettant de voir les informations des produits contenus dans les commandes
-- Utilisation lors de l'affichage des commandes
CREATE VIEW produits_commandes
	AS 
	SELECT COMMANDE.Id_Commande, COMMANDE.Id_Prod, Id_Uti, Nom_Produit, Qte_Produit_Commande, Unite_Stock.Nom_Unite as Nom_Unite_Stock, Prix_Produit_Unitaire, Unite_Prix.Nom_Unite as Nom_Unite_Prix, Qte_Produit_Commande*Prix_Produit_Unitaire as 'Prix_Total'
	FROM CONTENU JOIN PRODUIT ON CONTENU.Id_Produit=PRODUIT.Id_Produit
	JOIN COMMANDE ON CONTENU.Id_Commande=COMMANDE.Id_Commande
	JOIN UNITE as Unite_Stock ON PRODUIT.Id_Unite_Stock=Unite_Stock.Id_Unite
	JOIN UNITE as Unite_Prix ON PRODUIT.Id_Unite_Prix=Unite_Prix.Id_Unite;

SELECT * FROM produits_commandes;



-- #########################


-- 5) Vue tout_les_producteurs
DROP VIEW IF EXISTS tout_les_producteurs;
-- Vue permettant de voir les informations de tous les producteurs
-- Utilisation lors de l'affichage et la recherche de producteurs
CREATE VIEW tout_les_producteurs
	AS 
	SELECT PRODUCTEUR.Id_Uti, Nom_Uti, Prenom_Uti, Prof_Prod, Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti=UTILISATEUR.Id_Uti;

SELECT * FROM tout_les_producteurs;


-- #########################


-- 6) Vue Produits_d_un_producteur
DROP VIEW IF EXISTS Produits_d_un_producteur;

-- Vue permettant de voir tous les produits et leur informations.
-- utilisation lors de l'affichage des differents produits d'un producteur
CREATE VIEW Produits_d_un_producteur
	AS 
	SELECT Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Unite_Prix.Nom_Unite as Nom_Unite_Prix, Qte_Produit, Unite_Stock.Nom_Unite  as Nom_Unite_Stock
	FROM PRODUIT 
	JOIN TYPE_DE_PRODUIT ON PRODUIT.Id_Type_Produit=TYPE_DE_PRODUIT.Id_Type_Produit
	JOIN UNITE as Unite_Stock ON PRODUIT.Id_Unite_Stock=Unite_Stock.Id_Unite
	JOIN UNITE as Unite_Prix ON PRODUIT.Id_Unite_Prix=Unite_Prix.Id_Unite;

SELECT * FROM Produits_d_un_producteur;



-- #############################################################################################################################################################

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


INSERT INTO UTILISATEUR (Id_Uti, Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti, Pwd_Uti)
VALUES 
    ('1', 'John', 'Doe', 'johndoe1@gmail.com', '7 rue des Fleurs, 37000 TOURS', 'password123'),
    ('2', 'Alice', 'Smith', 'alicesmith2@gmail.com', '36 rue du Dome, 92100 BOULOGNE BILLANCOURT', 'securepass'),
    ('3', 'Maria', 'Garcia', 'mariagarcia3@gmail.com', '9 Place Comte de Bendern, 78170 LA CELLE ST CLOUD', 'securepassword'),
    ('4', 'David', 'Wilson', 'davidwilson4@gmail.com', '98/108 Rue Petit, 75019 PARIS', 'strongpass123'),
    ('5', 'Sophie', 'Brown', 'sophiebrown5@gmail.com', '16 Avenue Vauban, 83000 TOULON ', 'userpass567'),
    ('6', 'Michael', 'Davis', 'michaeldavis6@gmail.com', '168 Grande Rue, 93250 VILLEMOMBLE ', 'michaelpass'),
    ('7', 'Linda', 'Johnson', 'lindajohnson7@gmail.com', '9 rue Colbert, 13001 MARSEILLE ', 'lindapassword'),
    ('8', 'Robert', 'Martinez', 'robertmartinez8@gmail.com', '21 Boulevard Françoise Duparc, 13004 MARSEILLE ', 'robertpass123'),
    ('9', 'Lisa', 'Taylor', 'lisataylor9@gmail.com', '35 Rue Berger, 75001 PARIS ', 'lisapassword'),
    ('10', 'William', 'Anderson', 'williamanderson10@gmail.com', '10 Avenue Amiral Courbet, 30000 NIMES', 'williampass'),
    ('11', 'Sophia', 'Garcia', 'sophiagarcia1@gmail.com', 'Avenue Jean Jaurès, 77550 MOISSY CRAMAYEL ', 'sophiapassword'),
    ('12', 'Ethan', 'Brown', 'ethanbrown2@gmail.com', '54 Rue des Docteurs Calmette et Guérin, 53000 LAVAL', 'ethanpass123'),
    ('13', 'Olivia', 'Davis', 'oliviadavis3@gmail.com', '111 Rue de Buzenval, 92380 GARCHES', 'oliviapass567'),
    ('14', 'Noah', 'Smith', 'noahsmith4@gmail.com', '35 Av du Général Leclerc, 92100 BOULOGNE', 'noahpass123'),
    ('15', 'Aria', 'Wilson', 'ariawilson5@gmail.com', '105 rue Lecourbe, 75015 PARIS', 'ariapassword'),
    ('16', 'Logan', 'Martinez', 'loganmartinez6@gmail.com', '93 av. de Verdun, 92130 ISSY LES MOULINEAUX', 'loganpass567'),
    ('17', 'Amelia', 'Garcia', 'ameliagarcia7@gmail.com', '81 Rue Jules Guesde, 92300 LEVALLOIS PERRET ', 'ameliapassword'),
    ('18', 'Jackson', 'Brown', 'jacksonbrown8@gmail.com', '39 boulevard Voltaire, 92600 ASNIERES', 'password'),
    ('20', 'Ella', 'Anderson', 'ellaanderson10@gmail.com', '22 Esplanade Fléchambault, 51100 REIMS', 'ellapassword'),
    ('21', 'Oliver', 'Smith', 'oliversmith1@gmail.com', '54 Rue Erevan des Epinettes, 92130 ISSY LES MOULINEAUX ', 'oliverpass123'),
    ('22', 'Ava', 'Garcia', 'avagarcia2@gmail.com', '157 rue du Rouet, 13008 MARSEILLE', 'avapassword'),
    ('23', 'Lucas', 'Brown', 'lucasbrown3@gmail.com', '47 Rue Etats Généraux, 78000 VERSAILLES ', 'lucaspass567'),
    ('24', 'Ella', 'Davis', 'elladavis4@gmail.com', '102 Av du gal de Gaulle, 94700 MAISONS ALFORT', 'ellapass123'),
    ('25', 'Chloe', 'Smith', 'chloesmith5@gmail.com', '128 Rue Henri Champion, 72000 LE MANS', 'chloepassword'),
    ('26', 'Mason', 'Martinez', 'masonmartinez6@gmail.com', '133 Av. de Rangueil, 31400 TOULOUSE', 'masonpass567'),
    ('27', 'Harper', 'Garcia', 'harpergarcia7@gmail.com', '221 Rue des Charmilles, 1100 OYONNAX', 'harperpassword'),
    ('28', 'Ethan', 'Wilson', 'ethanwilson8@gmail.com', '19 Rue des Yvrollets, 1500 AMBRONAY', 'ethanpass123'),
    ('29', 'Ella', 'Taylor', 'ellataylor9@gmail.com', '40 Rue du Lynx, 1710 THOIRY', 'ellapassword'),
    ('30', 'Liam', 'Martinez', 'liammartinez10@gmail.com', 'Place Augustin Laurent, 59033 LILLE', 'liampassword'),
    ('31', 'Chloe', 'Garcia', 'chloegarcia1@gmail.com', '29 Rue de Strasbourg, 44000 NANTES', 'chloepass567'),
    ('32', 'Benjamin', 'Wilson', 'benjaminwilson2@gmail.com', '1 parc de l\'étoile, 67076 STRASBOURG', 'benjaminpassword'),
    ('33', 'Mia', 'Taylor', 'miataylor3@gmail.com', '130 avenue Daumesnil, 75012 PARIS', 'miapassword'),
    ('34', 'William', 'Davis', 'williamdavis4@gmail.com', '24 Rue Edmond Charlot, 72150 SAINT VINCENT DU LOROUER', 'williampass123'),
    ('35', 'Sophia', 'Smith', 'sophiasmith5@gmail.com', '4 Av. Georges Auric, 72000 LE MANS', 'sophiapassword'),
    ('36', 'Elijah', 'Brown', 'elijahbrown6@gmail.com', '4 Chemin des Hautes Frogeries, 53640 LE RIBAY', 'elijahpass567'),
    ('37', 'Amelia', 'Anderson', 'ameliaanderson7@gmail.com', '2 Rue du Britais, 53000 LAVAL', 'ameliapassword'),
    ('38', 'James', 'Johnson', 'jamesjohnson8@gmail.com', '19 Rue des Forges, 53470 COMMER', 'jamespassword'),
    ('39', 'Ava', 'Martinez', 'avamartinez9@gmail.com', '315 Rue des Mauprés, 88220 HADOL', 'avapass123'),
    ('40', 'Sophia', 'Garcia', 'sophiagarcia10@gmail.com', '9 Rue du General Marion, 88130 CHARMES', 'sophiapass567'),
    ('41', 'Daniel', 'Smith', 'danielsmith1@gmail.com', '7 Hameau Saint-Nicol, 14600 ABLON', 'danielpass123'),
    ('42', 'James', 'Brown', 'jamesbrown52@gmail.com', '4 Place de l’Eglise, 14800 CANAPVILLE', 'jamespass567'),
    ('43', 'Amelia', 'Garcia', 'ameliagarcia3@gmail.com', '24 Rue Joseph Levilly, 14860	BAVENT', 'ameliapass567'),
    ('44', 'Elijah', 'Davis', 'elijahdavis44@gmail.com', '12 Rue du Château, 39120 RAHON', 'elijahpassword'),
    ('45', 'Luna', 'Smith', 'lunasmith6@gmail.com', '110 Rue René Descartes, 39100 DOLE', 'lunapass123'),
    ('46', 'Benjamin', 'Martinez', 'benjaminmartinez46@gmail.com', '8 Rue du Bourg Neuf, 39120 ANNOIRE', 'benjaminpass567'),
    ('47', 'Aria', 'Garcia', 'ariagarcia7@gmail.com', '18 Rue des Pelerins, 68800 THANN', 'ariapassword'),
    ('48', 'Oliver', 'Wilson', 'oliverwilson8@gmail.com', '69 Avenue d\'Alsace, 68000 COLMAR', 'oliverpass123'),
    ('49', 'Chloe', 'Davis', 'chloedavis9@gmail.com', '71 Rue de la Fontaine, 68480 BETTLACH', 'chloepass567'),
    ('50', 'Benjamin', 'Wilson', 'benjaminwilson10@gmail.com', '396 La Varenne, 45230 ADON', 'benjaminpass123'),
    ('51', 'Chloe', 'Smith', 'chloesmith1@gmail.com', '123 Rue de Coulmiers, 45000 ORLÉANS', 'chloepass567'),
    ('52', 'James', 'Brown', 'jamesbrown2@gmail.com', '352 La Vaudelle, 45250 BRIARE', 'jamespass123'),
    ('53', 'Amelia', 'Garcia', 'ameliagarcia35@gmail.com', '10 Route de Garnay, 28500 ALLAINVILLE', 'ameliapass567'),
    ('54', 'Elijah', 'Davis', 'elijahdavis4@gmail.com', '12 Rue Amoreau, 28100 DREUX', 'elijahpassword'),
    ('55', 'Luna', 'Smith', 'lunasmith5@gmail.com', '1 Miton, 18160 TOUCHAY', 'lunapass123'),
    ('56', 'Benjamin', 'Martinez', 'benjaminmartinez6@gmail.com', '2 Rue des Crias, 18290 CIVRAY', 'benjaminpass567');

INSERT INTO PRODUCTEUR (Id_Prod, Id_Uti, Prof_Prod)
VALUES
    (1, '1', 'Agriculteur'),
    (2, '2', 'Agriculteur'),
    (3, '3', 'Vigneron'),
    (4, '4', 'Vigneron'),
    (5, '5', 'Maraîcher'),
    (6, '6', 'Apiculteur'),
    (7, '7', 'Maraîcher'),
    (8, '8', 'Éleveur de volaille'),
    (9, '9', 'Viticulteur'),
    (10, '10', 'Pépiniériste');

INSERT INTO ADMINISTRATEUR (Id_Admin, Id_Uti)
VALUES (1, '24');

INSERT INTO ADMINISTRATEUR (Id_Admin, Id_Uti)
VALUES (2, '35'); 


INSERT INTO UNITE (Id_Unite, Nom_Unite)
VALUES (1, 'Kg'),
 (2, 'l'),
 (3, 'm²'),
 (4, 'unité'); 



INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('1', '2023-10-23 09:00:00', '2024-04-23 09:00:00', "Bonjour, j'aimerais acheter des légumes frais.", '2', '6'),
    ('2', '2023-10-23 09:05:00', '2024-04-23 09:05:00', 'Bonjour, nous avons une variété de légumes disponibles. Que recherchez-vous en particulier ?', '6', '2'),
    ('3', '2023-10-23 09:10:00', '2024-04-23 09:10:00', 'Je suis intéressé par des carottes et des tomates. Pouvez-vous me donner les détails ?', '2', '6'),
    ('4', '2023-10-23 09:15:00', '2024-04-23 09:15:00', 'Bien sûr ! Nos carottes sont fraîches du jour. Les tomates sont également en stock. Souhaitez-vous les acheter en quantité ?', '6', '2'),
    ('5', '2023-10-23 09:20:00', '2024-04-23 09:20:00', 'Je prendrais 2 kg de carottes et 1 kg de tomates. Quel est le prix ?', '2', '6');

INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('6', '2023-10-24 10:00:00', '2024-04-24 10:00:00', 'Bonjour, avez-vous des fraises en ce moment ?', '4', '5'),
    ('7', '2023-10-24 10:05:00', '2024-04-24 10:05:00', 'Bonjour ! Oui, nous avons des fraises fraîches en stock. Combien de kilos souhaitez-vous ?', '5', '4'),
    ('8', '2023-10-24 10:10:00', '2024-04-24 10:10:00', 'Je voudrais 3 kg de fraises. Pouvez-vous les livrer demain ?', '4', '5'),
    ('9', '2023-10-24 10:15:00', '2024-04-24 10:15:00', 'Bien sûr ! La livraison sera planifiée pour demain. Les frais de livraison sont de 5€. Acceptez-vous ?', '5', '4'),
    ('10', '2023-10-24 10:20:00', '2024-04-24 10:20:00', "D'accord, je suis d'accord pour la livraison. Merci !", '4', '5');


INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('11', '2023-10-25 11:00:00', '2024-04-25 11:00:00', 'Bonjour, je cherche des pommes biologiques. En avez-vous ?', '8', '7'),
    ('12', '2023-10-25 11:05:00', '2024-04-25 11:05:00', 'Bonjour ! Oui, nous avons des pommes biologiques. Combien en voulez-vous ?', '7', '8'),
    ('13', '2023-10-25 11:10:00', '2024-04-25 11:10:00', 'Je prendrais 5 kg de pommes biologiques. Pouvez-vous me donner le prix ?', '8', '7'),
    ('14', '2023-10-25 11:15:00', '2024-04-25 11:15:00', 'Certainement ! Le prix des pommes biologiques est de 2,5€ par kg. Voulez-vous que je les réserve pour vous ?', '7', '8'),
    ('15', '2023-10-25 11:20:00', '2024-04-25 11:20:00', "Oui, s'il vous plaît, réservez-les pour moi. Je passerai les chercher demain.", '8', '7');

-- Conversation 4 entre un client et un maraîcher
INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('16', '2023-10-26 12:00:00', '2024-04-26 12:00:00', 'Bonjour, je suis à la recherche de miel local. En avez-vous ?', '10', '6'),
    ('17', '2023-10-26 12:05:00', '2024-04-26 12:05:00', 'Bonjour ! Oui, nous produisons du miel local de haute qualité. Combien de pots souhaitez-vous ?', '6', '10'),
    ('18', '2023-10-26 12:10:00', '2024-04-26 12:10:00', 'Je prendrais 3 pots de miel local. Pouvez-vous me donner le prix ?', '10', '6'),
    ('19', '2023-10-26 12:15:00', '2024-04-26 12:15:00', 'Bien sûr ! Le prix par pot de miel local est de 10€. Puis-je organiser la livraison ?', '6', '10'),
    ('20', '2023-10-26 12:20:00', '2024-04-26 12:20:00', "D'accord, organisez la livraison. Merci !", '10', '6');

INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('21', '2023-10-27 13:00:00', '2024-04-27 13:00:00', 'Bonjour, je recherche des oeufs frais de poule. En avez-vous en stock ?', '3', '8'),
    ('22', '2023-10-27 13:05:00', '2024-04-27 13:05:00', 'Bonjour ! Oui, nous avons des oeufs frais de poule. Combien de douzaines souhaitez-vous ?', '8', '3'),
    ('23', '2023-10-27 13:10:00', '2024-04-27 13:10:00', "Je prendrais 2 douzaines d'oeufs frais de poule. Pouvez-vous me donner le prix ?', '3', '8'),
    ('24', '2023-10-27 13:15:00', '2024-04-27 13:15:00', 'Certainement ! Le prix par douzaine d'oeufs frais de poule est de 3€. Voulez-vous que je les livre ?", '8', '3'),
    ('25', '2023-10-27 13:20:00', '2024-04-27 13:20:00', "D'accord, je suis d'accord pour la livraison. Merci !", '3', '8');

INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('45', '2023-10-28 14:00:00', '2024-04-28 14:00:00', 'Bonjour, je trouve le prix de vos fraises un peu élevé. Pouvez-vous faire une réduction ?', '4', '5'),
    ('46', '2023-10-28 14:05:00', '2024-04-28 14:05:00', 'Bonjour ! Nous fixons nos prix en fonction de la qualité de nos produits. Nous pouvons envisager une petite réduction pour une commande régulière.', '5', '4'),
    ('456', '2023-10-28 14:10:00', '2024-04-28 14:10:00', 'Je comprends, mais je reste sur ma position. Pouvez-vous réduire le prix de 10 % ?', '4', '5'),
    ('465', '2023-10-28 14:15:00', '2024-04-28 14:15:00', "D'accord, nous pouvons réduire de 5 % pour cette commande.", '5', '4'),
    ('789', '2023-10-28 14:20:00', '2024-04-28 14:20:00', "Cela me convient. Merci pour l'effort !", '4', '5');

INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('897', '2023-10-29 15:00:00', '2024-04-29 15:00:00', "Bonjour, je suis insatisfait de la qualité des pommes de terre que j'ai achetées. Elles ne sont pas fraîches.", '3', '8'),
    ('434', '2023-10-29 15:05:00', '2024-04-29 15:05:00', "Je suis désolé d'apprendre cela. Nous nous efforçons de fournir des produits de haute qualité. Pouvez-vous me donner plus de détails ?", '8', '3'),
    ('756', '2023-10-29 15:10:00', '2024-04-29 15:10:00', 'Les pommes de terre sont molles et ont un goût étrange. Je souhaite un remboursement.', '3', '8'),
    ('126', '2023-10-29 15:15:00', '2024-04-29 15:15:00', 'Je suis vraiment désolé pour cette expérience. Nous procéderons au remboursement complet.', '8', '3');
    
INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('132', '2023-10-30 16:00:00', '2024-04-30 16:00:00', 'Le prix de vos oeufs de poule est trop élevé. Pouvez-vous réduire le prix ?', '2', '7'),
    ('7564', '2023-10-30 16:05:00', '2024-04-30 16:05:00', 'Nous vendons des oeufs de haute qualité. Le prix reflète la qualité de nos produits. Nous ne pouvons pas réduire davantage.', '7', '2');
    
INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('37', '2023-10-25 17:00:00', '2024-04-25 17:00:00', "Les carottes que j'ai achetées ne sont pas fraîches et ont un goût étrange. Je veux un remboursement.", '10', '2'),
    ('38', '2023-10-25 17:05:00', '2024-04-25 17:05:00', 'Je suis désolé pour cette expérience. Nous vérifierons la qualité de nos produits et procéderons au remboursement complet.', '2', '10');
    
INSERT INTO MESSAGE (Id_Msg, Date_Msg, Date_Expi_Msg, Contenu_Msg, Emetteur, Destinataire)
VALUES
    ('39', '2023-11-01 18:00:00', '2024-05-01 18:00:00', 'Le prix de vos tomates est élevé. Pouvez-vous faire une réduction pour une commande en grande quantité ?', '7', '9'),
    ('40', '2023-11-01 18:05:00', '2024-05-01 18:05:00', 'Nous offrons des tarifs spéciaux pour les commandes en grande quantité. Vous bénéficiez déjà de la réduction.', '9', '7');


INSERT INTO STATUT (Id_Statut, Desc_Statut)
VALUES (1, 'En cours'),
(2, 'prête'),
(3, 'annulée'),
(4, 'livrée');



INSERT INTO COMMANDE (Id_Commande, Id_Prod, Id_Uti, Id_Statut)
VALUES ('1', 1, '1', 1),
('2', 2, '2', 2),
('4', 3, '5', 3),
('5', 1, '12', 1),
('6', 1, '32', 2),
('7', 2, '42', 3),
('8', 1, '15', 4),
('9', 3, '18', 4),
('10', 3, '2', 4),
('11', 1, '3', 2),
('12', 2, '7', 4);

INSERT INTO TYPE_DE_PRODUIT (Id_Type_Produit, Desc_Type_Produit)
VALUES (1, 'Fruits'),
(2, 'Légumes'),
(3, 'Grains'),
(4, 'Viande'),
(5, 'Vin'),
(6, 'Animaux'),
(7, 'Plances');

-- Pour un agriculteur
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(12, 'Blé', 6, 6, '100', 1, '2.00', 1),
(13, 'Maïs', 6, 6, '80', 1, '1.50', 1);

-- Pour un vigneron
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(14, 'Vin rouge', 7, 7, '10', 1, '15.00', 1),
(15, 'Vin blanc', 7, 7, '8', 1, '12.00', 1);

-- Pour un maraîcher
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(5, 'Tomates', 5, 4, '20', 1, '2.00', 1),
(6, 'Poivrons', 5, 4, '10', 1, '1.50', 1),
(7, 'Courgettes', 5, 4, '15', 1, '1.75', 1),
(8, 'Carottes', 2, 4, '18', 1, '1.40', 1),
(9, 'Aubergines', 5, 4, '12', 1, '2.25', 1);

-- Pour un apiculteur
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(16, 'Miel', 3, 8, '5', 1, '10.00', 1),
(17, 'Cire d''abeille', 4, 8, '2', 1, '5.00', 1);

-- Pour un éleveur de volaille
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(18, 'Poulet entier', 6, 9, '5', 1, '6.00', 1),
(19, 'oeufs de poule', 7, 9, '30', 1, '0.50', 1);

-- Pour un viticulteur
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(20, 'Chardonnay', 7, 10, '12', 1, '18.00', 1),
(21, 'Merlot', 7, 10, '15', 1, '16.00', 1);

-- Pour un pépiniériste
INSERT INTO PRODUIT (Id_Produit, Nom_Produit, Id_Type_Produit, Id_Prod, Qte_Produit, Id_Unite_Stock, Prix_Produit_Unitaire, Id_Unite_Prix)
VALUES
(22, 'Rosiers', 5, 1, '10', 1, '7.50', 1),
(23, 'Sapins', 5, 1, '8', 1, '9.00', 1);


INSERT INTO CONTENU (Id_Commande, Id_Produit, Qte_Produit_Commande, Num_Produit_Commande) VALUES ('1', '16', '12', '2'), ('1', '13', '320', '3'), ('2', '9', '560', '1'), ('2', '17', '12', '7'), ('4', '21', '36', '8'), ('5', '20', '21', '9'), ('7', '15', '12', '11'), ('12', '12', '1250', '12'), ('12', '6', '3', '14'), ('8', '5', '1', '16');
