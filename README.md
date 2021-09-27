<h1 align="center">Test Technique</h1>
</br>
</br>

## Prérequis et configuration
- Installer composer 
- Configurer votre fichier .env

### Liste des commandes 
- composer install
- npm install
- php artisan migrate:refresh --seed
- php artisan serve
- npm run watch

### Bon à savoir
<p align="justify">J'ai utilisé 'tiptap' pour la saisie des textes à trous, l'avantage c'est que l'on peut personnaliser comme on le souhaite et c'est également disponible pour react.js</p>

**Attention** 
<p align="justify">Lorsque vous utilisez l'évaluation de type texte à trous, ne pas laisser d'espace avant le premier mot et après le dernier (pour la détection de type expression). Lorsque vous cacher un mot, même chose, ne laissez aucun espace avant le premier caractère du mot et après le dernier caractère du mot</p>

</br>

## Connexion et inscription 
### Incription
<p align="justify">L'inscription sur la plateforme n'est pas possible, il faut donc passer par le seeder 'user' pour ajouter un compte</p>

### Connexion
#### Compte 'enseignant'
- email : teacher@test.fr
- mot de passe : test

### Compte 'étudiant'
- email : student@test.fr
- mot de passe : test