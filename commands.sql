/*Per crear usuari*/
/*1: Crear amb el UX WIKI normal*/
/*2n: Afegir sergio -> alumnos*/
INSERT INTO user_groups (ug_user, ug_group)
VALUES 
((SELECT user_id FROM user WHERE user_name = 'Sergio'), 'alumnos');

/*Afegir alumne amb ID 3 -> alumnos */
insert INTO user_groups (ug_group, ug_user) VALUES ("alumnos",3)

/*Donam tots els usuaris i grup al que pertanyen*/
SELECT user.user_name AS Alumno, user_groups.ug_group AS Grupo
FROM user
INNER JOIN user_groups ON user.user_id = user_groups.ug_user;

/*Donam el nom dels professors i el seu grup*/
SELECT u.user_name AS Profesor, ug.ug_group AS Grupo
FROM user u
JOIN user_groups ug ON u.user_id = ug.ug_user
WHERE ug.ug_group = 'profesores';

/*Donam el nom dels alumnes i el seu grup*/
SELECT u.user_name AS Profesor, ug.ug_group AS Grupo
FROM user u
JOIN user_groups ug ON u.user_id = ug.ug_user
WHERE ug.ug_group = 'alumnos';

/*DONAM PAGINES APROVADES*/
SELECT p.page_id, p.page_title, p.page_namespace, ar.rev_id AS approved_revision
FROM approved_revs ar
JOIN page p ON ar.page_id = p.page_id;
