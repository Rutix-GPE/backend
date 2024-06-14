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

## Installation sur Windows

### 1. Installer PHP 8.3

Pour installer PHP sur Windows, suivez les étapes suivantes :

1. Allez sur ce lien pour télécharger PHP : [Télécharger PHP](https://www.php.net/downloads)
2. Téléchargez la version zippée de PHP 8.3.8.
3. Après avoir extrait le contenu, copiez le dossier dans `C:\Program Files`.
4. Appuyez sur la touche Windows et tapez "Modifier les variables d’environnement système".
5. Cliquez sur le bouton "Variables d'environnement" (normalement en bas de la fenêtre).
6. Dans "Variables système", double-cliquez sur "Path".
7. Cliquez sur "Nouveau" et collez le chemin du répertoire de votre dossier PHP (normalement `C:\Program Files\php-8.3.8-Win32-vs16-x64`).
8. Ouvrez PowerShell et écrivez `php -v`. Si vous n'avez pas d'erreur, l'installation a bien fonctionné.

### 2. Installer Symfony CLI

Pour installer Symfony CLI, nous aurons besoin de Scoop. Suivez les étapes suivantes :

1. Ouvrez un terminal PowerShell.
2. Exécutez la commande suivante pour autoriser l'exécution de scripts :

```sh
Set-ExecutionPolicy RemoteSigned -scope CurrentUser
```

3. Installez Scoop avec cette commande :

```sh
iwr -useb get.scoop.sh | iex
```

4. Une fois Scoop installé, installez Symfony CLI avec la commande suivante :

```sh
scoop install symfony-cli
```

5. Pour vérifier que l'installation s'est bien passée, vous pouvez écrire dans le terminal :

```sh
symfony -V
```

### 3. Installer Composer

Pour installer Composer, suivez les étapes ci-dessous :

1. Téléchargez le script d'installation de Composer :

```sh
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

2. Vérifiez l'intégrité du script téléchargé :

```sh
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
```

3. Installez Composer :

```sh
php composer-setup.php
```

4. Supprimez le script d'installation :

```sh
php -r "unlink('composer-setup.php');"
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