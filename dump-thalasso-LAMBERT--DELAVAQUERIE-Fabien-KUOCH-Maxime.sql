DROP TABLE IF EXISTS Objectif CASCADE;
DROP TABLE IF EXISTS Soin CASCADE;
DROP TABLE IF EXISTS Etape CASCADE;
DROP TABLE IF EXISTS Weekend CASCADE;
DROP TABLE IF EXISTS Saison_Annee CASCADE;
DROP TABLE IF EXISTS Situation_logement CASCADE;
DROP TABLE IF EXISTS Type_chambre CASCADE;
DROP TABLE IF EXISTS Prestation CASCADE;
DROP TABLE IF EXISTS Cure CASCADE;
DROP TABLE IF EXISTS Avoir_Obj CASCADE;
DROP TABLE IF EXISTS Comporter_Soins CASCADE;
DROP TABLE IF EXISTS Comporter_Etapes CASCADE;
DROP TABLE IF EXISTS Annee CASCADE;
DROP TABLE IF EXISTS Loger CASCADE;
DROP TABLE IF EXISTS Critere CASCADE;
DROP TABLE IF EXISTS Reserver CASCADE;


CREATE TABLE Objectif(
	libelle_obj varchar(20),
	PRIMARY KEY (libelle_obj)
);

CREATE TABLE Soin(
	code_soin char(5),
	libelle_soin varchar(30),
	type_soin varchar(20),
	duree_soin time,
	effectif_max integer DEFAULT 1,
	PRIMARY KEY (code_soin)
);

CREATE TABLE Etape(
	code_etape char(3),
	description_etape text,
	duree_etape time,
	PRIMARY KEY (code_etape)
);

CREATE TABLE Weekend(
	num_weekend integer,
	duree integer NOT NULL DEFAULT 2,
	PRIMARY KEY(num_weekend)
);

CREATE TABLE Annee(
	numero integer,
	PRIMARY KEY (numero)
);

CREATE TABLE Saison_Annee(
	libelle_saison_annee varchar(30),
	annee integer,
	PRIMARY KEY (libelle_saison_annee),
	FOREIGN KEY (annee) REFERENCES Annee(numero)
);

CREATE TABLE Situation_logement(
	code_situation char(3),
	vue varchar(20),
	PRIMARY KEY (code_situation)
);

CREATE TABLE Type_chambre(
	code_chambre char(3),
	type varchar(30),
	PRIMARY KEY (code_chambre)
);

CREATE TABLE Prestation(
	code_presta char(4) NOT NULL,
	type varchar(20) NOT NULL,
	num_weekend integer DEFAULT NULL,
	PRIMARY KEY(code_presta),
	CONSTRAINT FK_weekend FOREIGN KEY (num_weekend) REFERENCES Weekend(num_weekend)
);

CREATE TABLE Cure(
	nom_cure varchar(20),
	description text,
	code_presta char(4),
	PRIMARY KEY(nom_cure),
	CONSTRAINT FK_presta FOREIGN KEY (code_presta) REFERENCES Prestation(code_presta)
);



CREATE TABLE Avoir_Obj(
	nom_cure varchar(20),
	libelle_obj varchar(20),
	PRIMARY KEY(nom_cure,libelle_obj),
	FOREIGN KEY(nom_cure) REFERENCES Cure(nom_cure),
	FOREIGN KEY(libelle_obj) REFERENCES Objectif(libelle_obj)
);

CREATE TABLE Critere(
	code_critere char(5),
	libelle_critere varchar(30),
	nom_cure varchar(20),
	PRIMARY KEY(code_critere),
	CONSTRAINT FK_critere FOREIGN KEY (nom_cure) REFERENCES Cure(nom_cure)
);



CREATE TABLE Comporter_Soins(
	code_presta char(4),
	code_soin char(5),
	PRIMARY KEY (code_presta,code_soin),
	FOREIGN KEY (code_presta) REFERENCES Prestation(code_presta),
	FOREIGN KEY (code_soin) REFERENCES Soin(code_soin)
);


CREATE TABLE Comporter_Etapes(
	code_etape char(3),
	code_soin char(5),
	numero_etape integer,
	PRIMARY KEY (code_etape,code_soin),
	FOREIGN KEY (code_soin) REFERENCES Soin(code_soin),
	FOREIGN KEY (code_etape) REFERENCES Etape(code_etape)
	
);


CREATE TABLE Reserver(
	code_presta char(4),
	periode varchar(30),
	tarif_presta integer,
	PRIMARY KEY (code_presta,periode),
	FOREIGN KEY (code_presta) REFERENCES Prestation(code_presta),
	FOREIGN KEY (periode) REFERENCES Saison_Annee(libelle_saison_annee)
);


CREATE TABLE Loger(
	periode varchar(20),
	code_chambre char(3),
	code_situation char(3),
	tarif_hebergement integer,
	PRIMARY KEY (periode,code_situation,code_chambre),
	FOREIGN KEY (periode) REFERENCES Saison_Annee(libelle_saison_annee),
	FOREIGN KEY (code_situation) REFERENCES Situation_logement(code_situation),
	FOREIGN KEY (code_chambre) REFERENCES Type_chambre(code_chambre)
);

INSERT INTO Objectif VALUES('Remise en forme');
INSERT INTO Objectif VALUES('Détente');
INSERT INTO Objectif VALUES('Sportif');
INSERT INTO Objectif VALUES('Médical');
INSERT INTO Objectif VALUES('Beauté');

INSERT INTO Soin VALUES('SAM83','Enveloppement d algues','Collectif','2:00:00',12);
INSERT INTO Soin VALUES('SBT72','Douche à jet','Individuel','1:00:00',1);
INSERT INTO Soin VALUES('SAM72','Massage sous affusion','Individuel','0:50:00',1);
INSERT INTO Soin VALUES('SRE13','SPA Relaxation','Collectif','1:30:00',8);
INSERT INTO Soin VALUES('BAM22','Bain à mousse','Individuel','1:00:00',1);
INSERT INTO Soin VALUES('MAH11','Massage à haute pression','Individuel','1:45:00',1);
INSERT INTO Soin VALUES('BHM56','Bain hydromassant','Collectif','0:45:00',5);

INSERT INTO Etape VALUES('SA3','Application des algues','0:20:00');
INSERT INTO Etape VALUES('SM2','Application de la boue pour massage','0:10:00');
INSERT INTO Etape VALUES('BT2','Douche à jet fort','0:30:00');
INSERT INTO Etape VALUES('BT4','Rinçage dans l eau chaude','0:20:00');

INSERT INTO Weekend VALUES(12);
INSERT INTO Weekend VALUES(17,4);
INSERT INTO Weekend VALUES(10);
INSERT INTO Weekend VALUES(4,3);
INSERT INTO Weekend VALUES(1);

INSERT INTO Annee VALUES( 2000 );
INSERT INTO Annee VALUES( 2010 );
INSERT INTO Annee VALUES( 2007 );
INSERT INTO Annee VALUES( 2008 );

INSERT INTO Saison_Annee VALUES('Hiver_2008',2008);
INSERT INTO Saison_Annee VALUES('Hiver_2007',2007);
INSERT INTO Saison_Annee VALUES('Basse_saison_2007',2007);
INSERT INTO Saison_Annee VALUES('Haute_saison_2007',2007);
INSERT INTO Saison_Annee VALUES('Basse_saison_2008',2008);
INSERT INTO Saison_Annee VALUES('Haute_saison_2008',2008);
INSERT INTO Saison_Annee VALUES('Haute_saison_2000',2000);
INSERT INTO Saison_Annee VALUES('Hiver_2000',2000);
INSERT INTO Saison_Annee VALUES('Basse_saison_2000',2000);
INSERT INTO Saison_Annee VALUES('Moyenne_saison_2008',2008);

INSERT INTO Situation_logement VALUES ('VUJ','vue jardin');
INSERT INTO Situation_logement VALUES ('VUM','vue mer');
INSERT INTO Situation_logement VALUES ('VUR','vue rue');

INSERT INTO Type_chambre VALUES ('CSS','Chambre Standard Simple');
INSERT INTO Type_chambre VALUES ('CSD','Chambre Standard Double');
INSERT INTO Type_chambre VALUES ('CCS','Chambre Confort Simple');
INSERT INTO Type_chambre VALUES ('CCD','Chambre Confort Double');
INSERT INTO Type_chambre VALUES ('SSS','Suite');

INSERT INTO Prestation VALUES ('BE55','Cure',NULL);
INSERT INTO Prestation VALUES ('SA55','Weekend',4);
INSERT INTO Prestation VALUES ('MI55','Cure',NULL);
INSERT INTO Prestation VALUES ('SP55','Weekend',12);

INSERT INTO Cure VALUES ('Bien-etre','Destiné à toute personne recherchant forme, vitalité et bien etre, ce programme va contribuer à un ressourcement
général. Après une période d activité intense, qu elle soit d ordre psychique, physique ou intellectuelle, le corps a besoin
de retrouver son énergie. Dans cette cure, les soins à base d eau de mer et d algues seront privilégiés afin de vous faire
profiter au maximum des oligo-éléments et vitamines qu ils contiennent. Massages et relaxation seront également au
programme pour vous apporter cette détente indispensable à une remise en condition optimale.','BE55');
INSERT INTO Cure VALUES('Minceur','Envie d’une semaine tonifiante pour réapprendre les bons réflexes et renouer avec votre jean fétiche ? Reprenez le contrôle sur votre corps grâce à ce séjour thalasso minceur. Coaching sportif, séances exclusives d’aquabiking et soins thalassos ciblés vous permettent de sculpter peu à peu votre ligne et de perdre du poids, à votre rythme.','MI55');
INSERT INTO Cure VALUES('Sport',NULL,'SP55');
INSERT INTO Cure VALUES('Santé',NULL,'SA55');

INSERT INTO Avoir_Obj VALUES('Bien-etre','Détente');
INSERT INTO Avoir_Obj VALUES('Bien-etre','Remise en forme');
INSERT INTO Avoir_Obj VALUES('Minceur','Beauté');

INSERT INTO Critere VALUES('AM551','tour de taille','Bien-etre');
INSERT INTO Critere VALUES('AM552','tour de hanche','Bien-etre');
INSERT INTO Critere VALUES('AM553','tour de cuisse', 'Bien-etre');
INSERT INTO Critere VALUES('SP551','indice de masse corporelle','Sport');
INSERT INTO Critere VALUES('SA551','handicap','Santé');

INSERT INTO Comporter_Soins VALUES('SA55','SAM83');
INSERT INTO Comporter_Soins VALUES('SA55','SAM72');
INSERT INTO Comporter_Soins VALUES('MI55','SAM72');
INSERT INTO Comporter_Soins VALUES('SP55','SBT72');
INSERT INTO Comporter_Soins VALUES('BE55','SRE13');

INSERT INTO Comporter_Etapes VALUES('SA3','SAM83',3);
INSERT INTO Comporter_Etapes VALUES('SM2','SAM72',2);
INSERT INTO Comporter_Etapes VALUES('BT4','SBT72',5);
INSERT INTO Comporter_Etapes VALUES('BT4','SRE13',3);
INSERT INTO Comporter_Etapes VALUES('BT2','SBT72',4);

INSERT INTO Reserver VALUES('BE55','Hiver_2008',440);
INSERT INTO Reserver VALUES('BE55','Haute_saison_2008',570);
INSERT INTO Reserver VALUES('BE55','Moyenne_saison_2008',520);
INSERT INTO Reserver VALUES('BE55','Basse_saison_2008',480);
INSERT INTO Reserver VALUES('MI55','Basse_saison_2008',520);
INSERT INTO Reserver VALUES('BE55','Hiver_2007',330);
INSERT INTO Reserver VALUES('MI55','Haute_saison_2000',520);

INSERT INTO Loger VALUES('Hiver_2008','CSS','VUM',200);
INSERT INTO Loger VALUES('Hiver_2008','CSS','VUJ',190);
INSERT INTO Loger VALUES('Hiver_2008','CSS','VUR',175);
INSERT INTO Loger VALUES('Hiver_2008','CSD','VUM',150);
INSERT INTO Loger VALUES('Hiver_2008','CSD','VUJ',140);
INSERT INTO Loger VALUES('Hiver_2008','CSD','VUR',125);
INSERT INTO Loger VALUES('Hiver_2008','CCS','VUM',230);
INSERT INTO Loger VALUES('Hiver_2008','CCS','VUJ',220);
INSERT INTO Loger VALUES('Hiver_2008','CCS','VUR',200);
INSERT INTO Loger VALUES('Hiver_2008','CCD','VUM',175);
INSERT INTO Loger VALUES('Hiver_2008','CCD','VUJ',165);
INSERT INTO Loger VALUES('Hiver_2008','CCD','VUR',150);
INSERT INTO Loger VALUES('Hiver_2008','SSS','VUM',400);
INSERT INTO Loger VALUES('Hiver_2008','SSS','VUJ',375);
INSERT INTO Loger VALUES('Basse_saison_2008','CSS','VUM',220);
INSERT INTO Loger VALUES('Basse_saison_2008','CSS','VUJ',200);
INSERT INTO Loger VALUES('Basse_saison_2008','CSS','VUR',190);
INSERT INTO Loger VALUES('Basse_saison_2008','CSD','VUM',170);
INSERT INTO Loger VALUES('Basse_saison_2008','CSD','VUJ',150);
INSERT INTO Loger VALUES('Basse_saison_2008','CSD','VUR',140);
INSERT INTO Loger VALUES('Basse_saison_2008','CCS','VUM',250);
INSERT INTO Loger VALUES('Basse_saison_2008','CCS','VUJ',230);
INSERT INTO Loger VALUES('Basse_saison_2008','CCS','VUR',220);
INSERT INTO Loger VALUES('Basse_saison_2008','CCD','VUM',195);
INSERT INTO Loger VALUES('Basse_saison_2008','CCD','VUJ',175);
INSERT INTO Loger VALUES('Basse_saison_2008','CCD','VUR',165);
INSERT INTO Loger VALUES('Basse_saison_2008','SSS','VUM',425);
INSERT INTO Loger VALUES('Basse_saison_2008','SSS','VUJ',400);