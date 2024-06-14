# Projet Symfony API

## Description
Ce projet est une API construite avec le framework Symfony. Il fournit plusieurs endpoints pour la gestion des utilisateurs, incluant l'enregistrement des nouveaux utilisateurs.

## Versions utilisées
- Symfony CLI : 5.9.1
- Composer : 2.7.7
- PHP : 8.3.8

## Installation sur Linux

### 1. Installer PHP 8.3

Pour installer la version exacte de PHP nécessaire pour ce projet :

```sh
sudo apt install php8.3
```

Ensuite, configurez votre système pour utiliser cette version de PHP par défaut :

```sh
sudo update-alternatives --set php /usr/bin/php8.3
```

### 2. Installer Symfony CLI

Téléchargez et installez Symfony CLI :

```sh
wget https://get.symfony.com/cli/installer -O - | bash
```

Ajoutez Symfony CLI au PATH en ajoutant la ligne suivante dans votre fichier `~/.bashrc` :

```sh
export PATH="$HOME/.symfony5/bin:$PATH"
```

Puis, rechargez votre configuration de shell :

```sh
source ~/.bashrc
```

### 3. Installer Composer

Pour installer Composer, suivez les étapes ci-dessous :

Téléchargez le script d'installation de Composer :

```sh
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

Vérifiez l'intégrité du script téléchargé :

```sh
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```

Installez Composer :

```sh
php composer-setup.php
```

Supprimez le script d'installation :

```sh
php -r "unlink('composer-setup.php');"
```

Déplacez le binaire de Composer pour qu'il soit accessible globalement :

```sh
sudo mv composer.phar /usr/local/bin/composer
```

## Endpoints de l'API

### 1. Enregistrement de l'utilisateur

- **Méthode :** POST
- **URL :** `/user/register/`
- **Format des données :** JSON

#### Données attendues :
- `username` (string) : Obligatoire
- `password` (string) : Obligatoire
- `firstname` (string) : Obligatoire
- `lastname` (string) : Obligatoire
- `email` (string) : Obligatoire
- `numberphone` (string) : Optionnel
- `country` (string) : Optionnel
- `postalcode` (string) : Optionnel
- `city` (string) : Optionnel
- `adress` (text) : Optionnel

#### Réponses possibles :
- **400** : Données obligatoires manquantes
- **111** : Erreur interne
- **201** : Utilisateur créé avec succès

### Exemples de requêtes et réponses

#### Exemple de requête :

```json
POST /user/register/
{
  "username": "johndoe",
  "password": "securepassword",
  "firstname": "John",
  "lastname": "Doe",
  "email": "johndoe@example.com",
  "numberphone": "1234567890",
  "country": "France",
  "postalcode": "75001",
  "city": "Paris",
  "adress": "1 Rue de Rivoli"
}
```

#### Exemple de réponse en cas de succès :

```json
{
  "status": 201,
  "message": "Utilisateur créé avec succès"
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "status": 400,
  "message": "Données obligatoires manquantes"
}
```

## Licence

Ce projet est sous licence [Nom de la licence]. Veuillez consulter le fichier LICENSE pour plus de détails.

## Contribution

Les contributions sont les bienvenues ! Veuillez soumettre une pull request ou ouvrir une issue pour discuter des changements que vous souhaitez apporter.

---

Pour plus d'informations, veuillez consulter la documentation officielle de Symfony et Composer, ou contacter les mainteneurs du projet.