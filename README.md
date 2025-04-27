# Watchlist-PHP

                                    **Bienvenue dans le tuto pour installer le site WatchlistFilm en local **


1) tu vas devoir cloner le projet sur ta machine, je vais te donner les etapes à suivre :
   
   - Ouvre ton terminal de commande et utiliser la commande suivant ->  git clone  git@github.com:Yazhall/Watchlist-PHP.git    
   - puis te deplacer dans ce dossier avec cette commande ->   cd Watchlist-PHP 


2)  installation de la BDD (base de données)
   - prés requis avoir docker d'installer sur ça machine
   - on va travailler avec docker 
   - dans le dossier du projet il y a un sous dossier qui se nomme BDD clique droite dessus et ouvre un terminal 
   - une fois le terminal ouvert lance cette commande ->  docker-compose up -d   / ça devrait créer le conteneur et le remplir avec le fichier  dump.sql
   - dernier étape  Accède au conteneur MariaDB avec cette commande -> docker exec -it mariadb_db mysql -u root -p

!! si ca ne marche pas je join le dossier complet de la BDD a installer le dossier s'appel  "MovieSave" !!!
    




                                             **Utiliser le projet **
- tu as 4 utilisateurs pour tester toute les feature developer dont un administrateur, je vais te donner toutes les infos pour te connecter sur ces comptes :
- login : Administrateur /   MPD : Administrateur  (pour toujours plus de securité )
- login: lyne / MPD : laurynemael 
- login : johndoe / MDP : password123
- login : janesmith / MDP : password456

Ce sont des utilisateurs deja crée avec une watchlist deja remplie, mais tu peux créer ton ou ta propre compte pour interactive avec tout le site. 
Tu vas pouvoir ajouter les films deja présent dans le catalogue à ta watchlist 
Tu vas pouvoir via n'importe quel compte crée un film que tu ajouteras à ta watchlist uniquement. 


Pour t'aider à créer des films, je vais te mettre à disposition un fichier avec des images de film et juste en dessous, je vais te donner de l'exemple de film à toi de voir si tu veux les utiliser ou prendre ta propre film.

1er film :
- noms :Django Unchained
- categorie : Western
- date de sortie : 16/01/2013
- Synopsis : Dans le sud des États-Unis, deux ans avant la guerre de Sécession, le Dr King Schultz, un chasseur de primes allemand, fait l’acquisition de Django, un esclave qui peut l’aider à traquer les frères Brittle, les meurtriers qu’il recherche.


2ᵉ film: 

- noms :Drive
- categorie : Action
- date de sortie :05/11/2011
- Synopsis : Un jeune homme solitaire, "The Driver", conduit le jour à Hollywood pour le cinéma en tant que cascadeur et la nuit pour des truands. Ultra professionnel et peu bavard, il a son propre code de conduite. 

3ᵉ film : 

- noms :Blade Runner 2049
- categorie :  Science Fiction
- date de sortie :04/10/2017
- Synopsis : En 2049, la société est fragilisée par les nombreuses tensions entre les humains et leurs esclaves créés par bioingénierie. L’officier K est un Blade Runner : il fait partie d’une force d’intervention d’élite chargée de trouver et d’éliminer ceux qui n’obéissent pas aux ordres des humains.



**Voilà à toi de faire des teste ! **