# Cahier des charges

## Objectif
Fournir une plateforme de gestion de restaurant universitaire pour gerer menus, reservations, commandes, stock et statistiques.

## Roles
- Etudiant: consulte menus, reserve/commande, paie, laisse avis.
- Personnel: consulte menus, gere stock, prepare commandes.
- Admin: gere utilisateurs, menus, plats, categories, stock, statistiques.

## Fonctionnalites (obligatoires)
- CRUD menus, plats, categories
- Menus quotidiens avec services (midi/soir)
- Reservation et commande de repas
- Limitation des quantites par plat
- Decrementation automatique du stock
- Paiement simule (statut et reference)
- Commentaires et notes sur plats/menus
- Statistiques de frequentation
- Tableau de bord admin

## Regles de gestion
- Un menu est defini par une date et un service.
- Un plat peut etre propose sur plusieurs menus.
- Un plat peut avoir une limite de quantite par menu.
- Une reservation n est validee que si les quantites restantes sont suffisantes.
- Toute commande validee decremente le stock des ingredients.
- Les paiements sont simules mais traces avec un statut.

## Contraintes non fonctionnelles
- Authentification securisee (hash des mots de passe).
- Journalisation des operations sensibles.
- Temps de reponse < 1s pour la consultation des menus.
- RGPD: minimisation des donnees et suppression a la demande.

## Livrables
- Modele de donnees
- API REST
- Documentation utilisateur et admin
- Tableau de bord
