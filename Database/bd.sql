BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "i23_article" (
	"id"	INTEGER NOT NULL,
	"nom"	VARCHAR(100) DEFAULT NULL,
	"quantite"	INTEGER DEFAULT NULL,
	"prix"	INTEGER DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "i23_panier" (
	"id"	INTEGER NOT NULL,
	"article_id_id"	INTEGER NOT NULL,
	"user_id_id"	INTEGER NOT NULL,
	"quantite"	INTEGER DEFAULT NULL,
	CONSTRAINT "FK_856ECE098F3EC46" FOREIGN KEY("article_id_id") REFERENCES "i23_article"("id") NOT DEFERRABLE INITIALLY IMMEDIATE,
	CONSTRAINT "FK_856ECE099D86650F" FOREIGN KEY("user_id_id") REFERENCES "i23_utilisateur"("id") NOT DEFERRABLE INITIALLY IMMEDIATE,
	PRIMARY KEY("id" AUTOINCREMENT)
);
CREATE TABLE IF NOT EXISTS "i23_utilisateur" (
	"id"	INTEGER NOT NULL,
	"login"	VARCHAR(180) NOT NULL,
	"roles"	CLOB NOT NULL,
	"password"	VARCHAR(255) NOT NULL,
	"nom"	VARCHAR(100) DEFAULT NULL,
	"prenom"	VARCHAR(100) DEFAULT NULL,
	"birthdate"	DATE DEFAULT NULL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (1,'Pomme',12,120);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (2,'Toufaha',28,12);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (3,'Banane',15,130);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (4,'Ballon',5,10);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (5,'Peigne',10,50);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (6,'Corde a sauter',19,40);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (7,'Jus de fraise',16,10);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (8,'Banana',23,65);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (9,'Parapluie',2,89);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (10,'Voiture',3,20000);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (11,'Sac a dos',5,20);
INSERT INTO "i23_article" ("id","nom","quantite","prix") VALUES (12,'fève',10,15);
INSERT INTO "i23_utilisateur" ("id","login","roles","password","nom","prenom","birthdate") VALUES (1,'sadmin','["ROLE_SUPERADMIN"]','$2y$13$KkOLF.Bpv7fCX.chQedTsuGIMp2Gd49K8amYiWq.BCB9VqrCvSxb.','el rey','yer le','2018-01-01');
INSERT INTO "i23_utilisateur" ("id","login","roles","password","nom","prenom","birthdate") VALUES (2,'gilles','["ROLE_ADMIN"]','$2y$13$btB3lTDjKFe2.t47sQqAC.LwWJ149HyPcLaWchWFiX55F7xYL2sqO','gilles','sellig','1992-01-01');
INSERT INTO "i23_utilisateur" ("id","login","roles","password","nom","prenom","birthdate") VALUES (3,'rita','["ROLE_CLIENT"]','$2y$13$9dbrd6nx7IBna5Wdo5BLXuEhNg82fSwmlG7uDpKn1ciruiWXVPzSK','rita','atir','2018-01-01');
INSERT INTO "i23_utilisateur" ("id","login","roles","password","nom","prenom","birthdate") VALUES (4,'simon','["ROLE_CLIENT"]','$2y$13$w6OkgUXOjmdPZNFHH0Hb3eV/2J9cCX3M3qhY1O8MAEO9YBx42NdmW','simon','nomis','2018-01-01');
INSERT INTO "i23_utilisateur" ("id","login","roles","password","nom","prenom","birthdate") VALUES (6,'test1','["ROLE_CLIENT"]','$2y$13$56OCBAzFZojWNy5PBMfrA..Hw9fRVMM8zdkPlpruOcTqhdg4tlrJu','poups','poupstestà','1903-01-01');
CREATE INDEX IF NOT EXISTS "IDX_856ECE098F3EC46" ON "i23_panier" (
	"article_id_id"
);
CREATE INDEX IF NOT EXISTS "IDX_856ECE099D86650F" ON "i23_panier" (
	"user_id_id"
);
CREATE UNIQUE INDEX IF NOT EXISTS "UNIQ_2BE047B1AA08CB10" ON "i23_utilisateur" (
	"login"
);
COMMIT;
