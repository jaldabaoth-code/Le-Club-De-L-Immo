/* CREATE TABLES AND INSERT FOR TEST */

/* Create feature */
CREATE TABLE `feature` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `flaticonName` varchar(50) NOT NULL,
    CONSTRAINT `C12` PRIMARY KEY (`id`)
);
INSERT INTO `feature` (`flaticonName`, `name`)
VALUES ('flaticon-rooms', 'pièces'),
('flaticon-surface', 'm²'),
('flaticon-bathrooms', 'salle de bains'),
('flaticon-toilets', 'wc'),
('flaticon-kitchen', 'cuisine'),
('flaticon-lift', 'ascenseur'),
('flaticon-parking-space', 'parking'),
('flaticon-bedrooms', 'chambres');

/* Create propertyType */
CREATE TABLE `propertyType` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    CONSTRAINT `C4` PRIMARY KEY (`id`)
);
INSERT INTO `propertyType` (`name`)
VALUES ('Maison'),
('Appartement'),
('Villa'),
('Terrain'),
('Studio'),
('Autre');

/* Create sector */
CREATE TABLE `sector` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255),
    `lat` float,
    `lon` float,
    CONSTRAINT `C5` PRIMARY KEY (`id`)
);
INSERT INTO `sector` (`name`, `lat`, `lon`)
VALUES ('Dunois', 47.9080, 1.8943),
('Madeleine', 47.8999, 1.8812),
('Orléans Centre', 47.9029, 1.9039),
('Orléans Nord', 47.9466, 1.8906),
('Argonne', 47.9115, 1.9283),
('Saint-Marceau', 47.8869, 1.9069),
('La Source', 47.8457, 1.9234);

/* Create property */
CREATE TABLE `property` (
    `id` int AUTO_INCREMENT NOT NULL,
    `reference` varchar(25),
    `transaction` varchar(25),
    `address` varchar(255),
    `price` int,
    `energy_performance` char(1),
    `greenhouse_gases` char(1),
    `description` text,
    `property_type_id` int,
    `sector_id` int,
    CONSTRAINT `C2` FOREIGN KEY (`sector_id`) REFERENCES `sector` (`id`) on delete cascade,
    CONSTRAINT `C3` FOREIGN KEY (`property_type_id`) REFERENCES `propertyType` (`id`) on delete cascade,
    CONSTRAINT `C1` PRIMARY KEY (`id`)
);
INSERT INTO `property` (`reference`, `property_type_id`, `sector_id`, `price`, `transaction`, `energy_performance`, `greenhouse_gases`, `address`)
VALUES (1, 2, 1, 850, 'A Louer', 'A', 'B', '10 Rue de la Chaussure'),
(2, 1, 2, 975, 'A Louer', '', 'A', '5 Rue de Patay'),  
(3, 2, 3, 370000, 'A Vendre', 'A', 'C', '2 Rue de la Pompe'),
(4, 2, 4, 750, 'A Louer', 'B', 'B', '10 Rue de Verneuil'),
(5, 3, 5, 500000, 'A Vendre', 'D', 'B', '9 Rue des Boulets'),
(6, 1, 4, 170000, 'A Vendre', '', 'B', '20 Rue du Commerce'),
(7, 1, 2, 1100, 'A Louer', 'D', 'D', '3 Rue du Commerce'),
(8, 1, 3, 220000, 'A Vendre', 'A', 'D', '7 Rue de Patay'),
(9, 1, 4, 1350, 'A Louer', 'A', 'B', '8 Rue des Boulets'),
(10, 1, 2, 1000, 'A Louer', 'C', 'B', '15 Rue de la Chaussure'); 

/* Create property_feature */
CREATE TABLE `property_feature` (
    `property_id` int,
    `feature_id` int,
    number int,
    CONSTRAINT `C13` PRIMARY KEY (`property_id`, `feature_id`),
    CONSTRAINT `C14` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) on delete cascade,
    CONSTRAINT `C15` FOREIGN KEY (`feature_id`) REFERENCES `feature` (`id`) on delete cascade
);
INSERT INTO `property_feature` (`property_id`, `feature_id`, `number`)
VALUES (1,1,4), (1,2,50), (1,3,1), (1,4,1), (1,5,1), (1,6,1), (1,7,1), (1,8,3),               
(2,1,5), (2,2,50), (2,3,1), (2,4,1), (2,5,1), (2,6,0), (2,7,1), (2,8,3),
(3,1,3), (3,2,750), (3,3,2), (3,4,1), (3,5,1), (3,6,1), (3,7,1), (3,8,2),
(4,1,5), (4,2,95), (4,3,1), (4,4,1), (4,5,1), (4,6,1), (4,7,1), (4,8,3),
(5,1,4), (5,2,150), (5,3,1), (5,4,1), (5,5,1), (5,6,0), (5,7,1), (5,8,2),
(6,1,4), (6,2,170), (6,3,2), (6,4,1), (6,5,1), (6,6,0), (6,7,1), (6,8,2),
(7,1,4), (7,2,50), (7,3,1), (7,4,1), (7,5,1), (7,6,0), (7,7,1), (7,8,2),
(8,1,3), (8,2,75), (8,3,1), (8,4,1), (8,5,1), (8,6,0), (8,7,1), (8,8,2),
(9,1,3), (9,2,90), (9,3,1), (9,4,1), (9,5,1), (9,6,0), (9,7,1), (9,8,2),
(10,1,4), (10,2,80), (10,3,1), (10,4,1), (10,5,1), (10,6,0), (10,7,0), (10,8,2);

Update `property`
SET description = "LA GARENNE COLOMBES, dans un environnement verdoyant et calme tout en étant proche des commodités, bel Appartement lumineux traversant 4 pièces de 67 m², au 1er étage, comprenant : une Entrée, un Séjour exposé sud, une Cuisine ouverte et équipée, 3 Chambres, une Salle de bains et un WC séparé. Une grande cave et Une place de stationnement complètent ce bien. Possibilité de louer en plus un box pour 40euros/mois. Copropriété de 50 lots. Charges: 208 euros/mois. Honoraires charge acquéreur de 3.85%. inclus dans le prix. Cet appartement familial clair et fonctionnel dispose d'un plan optimal. Son emplacement conjugue tout les atouts de la proximité du centre-ville, à 2 min à pied du tramway T2."
WHERE `id` = 3;

Update `property`
SET description = "LA GARENNE COLOMBES, dans un environnement verdoyant et calme tout en étant proche des commodités, bel Appartement lumineux traversant 4 pièces de 67 m², au 1er étage, comprenant : une Entrée, un Séjour exposé sud, une Cuisine ouverte et équipée, 3 Chambres, une Salle de bains et un WC séparé. Une grande cave et Une place de stationnement complètent ce bien. Possibilité de louer en plus un box pour 40euros/mois. Copropriété de 50 lots. Charges: 208 euros/mois. Honoraires charge acquéreur de 3.85%. inclus dans le prix. Cet appartement familial clair et fonctionnel dispose d'un plan optimal. Son emplacement conjugue tout les atouts de la proximité du centre-ville, à 2 min à pied du tramway T2."
WHERE `id` = 4;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 1;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 2;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 5;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 6;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 7;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 8;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 9;

Update `property`
SET description = "15 minutes à pieds de petits commerces, dans un environnement calme et privilégié, charmante maison en chaûme du 19ème siècle. Cette chaumière comprend au rdc, 1 entrée, 1 salle à mangé très lumineuse, 1 salon et sa cheminée en pierre ainsi que sa cuisine et son coin lingerie, wc. Au 1er étage, 3 chambres, 1 salle de bain et un wc. Un sous sol partiel, 1 cave et un vide sanitaire. Le tout posé sur un bel écrin de verdure d'environ 1200m². 1 garage avec arrivée d'eau et électricité. Votre agence terres et demeures de normandie reste à votre disposition pour la visite de ce joli bien! Nous vous rappelons que suite à l'article l.561-5 du code monétaire et financier, la copie de la pièce d'identité de tous les visiteurs vous sera demandée avant la visite."
WHERE `id` = 10;

/* Create photo */
CREATE TABLE `photo` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(255),
    `property_id` int,
    CONSTRAINT `C6` PRIMARY KEY (`id`),
    CONSTRAINT `C7` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`) on delete cascade
);
INSERT INTO `photo` (`property_id`, `name`) 
VALUES (1, 'exemple01.jpg'),
(2, 'exemple02.jpg'),
(3, 'exemple03.jpg'),
(4, 'exemple04.jpg'),
(5, 'exemple05.jpg'),
(6, 'exemple06.jpg'),
(7, 'exemple07.jpg'),
(8, 'exemple08.jpg'),
(9, 'exemple09.jpg'),
(10, 'exemple10.jpg');

INSERT INTO `photo` (`property_id`, `name`) 
VALUES (1, 'exemple10.jpg'),
(2, 'exemple09.jpg'),
(3, 'exemple08.jpg'),
(4, 'exemple07.jpg'),
(5, 'exemple06.jpg'),
(6, 'exemple05.jpg'),
(7, 'exemple04.jpg'),
(8, 'exemple03.jpg'),
(9, 'exemple02.jpg'),
(10, 'exemple01.jpg');