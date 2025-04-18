--Un modérateur doit pouvoir accéder à la liste des utilisateurs bannis.

select utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur = sanctionner.id_utilisateur
where est_banis = 1 ;

--Un modérateur doit pouvoir accéder à la liste des utilisateurs bannis.

select utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur = sanctionner.id_utilisateur
where est_banis = 1 ;

--Il doit également pouvoir accéder aux sanctions qui concernent un utilisateur, si le compte de cet utilisateur est en étude afin d’être banni.
select sanctionner.est_banis, sanctionner.raison, utilisateur.id_utilisateur
from utilisateur
inner join sanctionner on utilisateur.id_utilisateur=sanctionner.id_utilisateur;

--La liste des sanctions faites par un modérateur est historisée
select sanctionner.est_banis, sanctionner.raison
from sanctionner
inner join moderateur on sanctionner.id_utilisateur=moderateur.id_utilisateur;

--Voir tous les signalements non validés
SELECT signaler.id_arg
from signaler
where signaler.id_arg not in (select id_arg
                              from valider);

--Liste des débats non validés.
SELECT debat.desc_d, debat.date_creation
from debat
where statut = 'Attente';

--Liste des sous-arguments qui dépasse l’argument principal en nombre de votes pour le valider.
SELECT argument.id_arg, argument.texte, argument.id_camp
from argument
inner join valider on argument.id_arg=valider.id_arg
inner join voter on valider.id_arg=voter.id_arg
where ?je bloque


SELECT A1.id_arg
FROM Argument A1 JOIN Voter Vo1 ON A1.id_arg = Vo1.id_arg
                LEFT OUTER JOIN Valider Va ON A1.id_arg = Va.id_arg
                LEFT OUTER JOIN Signaler S ON A1.id_arg = S.id_arg 
WHERE Va.id_arg IS NULL -- N'est pas déjà validé
AND (S.id_arg IS NULL OR NOT S.est_valide) -- N'a pas de signalement validé
AND A1.id_arg_principal IS NOT NULL -- Est un sous-argument
GROUP BY A1.id_arg
HAVING COUNT(*) > (
    SELECT COUNT(*)
    FROM Argument A2 JOIN Voter Vo2 ON A2.id_arg = Vo2.id_arg
    WHERE A2.id_arg = A1.id_arg_principal -- Lien avec le premier argument
    GROUP BY A2.id_arg
);

--Administrateur

--CRUD(Récupération, suppression, ajout, modif) sur la liste des modérateurs.
--Création :
INSERT INTO utilisateur (date_création,email,id_utilisateur,mdp,pseudo,role) VALUES ('02/02/2020','mimi@gmail.com',6,'1234abcd','mimi123','3')

--Lecture :
Select * 
from utilisateur
where role = 3;

--Modification d'un pseudo :
Update utilisateur
set pseudo = 'mumu'
where id-utilisateur = 6;

--Suppression d'un utilisateur :
Delete from utilisateur
where id_utilisateur = 6;

--CRUD(Récupération, suppression, ajout, modif) sur la liste des catégories.
--Création : 
INSERT INTO categorie (desc_c,nom_c) VALUES ('plantes tropicales','Plantes')

--Lecture :
Select * 
from categorie ;

--Modification d'un pseudo :
Update categorie
set desc_c = 'plantes des tropiques'
where desc_c like 'plantes tropicales';

--Suppression :
Delete from categorie
where desc_c like 'plantes des tropiques';
