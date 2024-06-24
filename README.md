Voici le README mis à jour avec les nouvelles routes ajoutées :

# Projet Symfony API

## Description
Ce projet est une API construite avec le framework Symfony. Il fournit plusieurs endpoints pour la gestion des utilisateurs, incluant l'enregistrement des nouveaux utilisateurs et l'authentification pour obtenir un token.

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

### 2. Authentification de l'utilisateur

- **Méthode :** POST
- **URL :** `/user/authenticate/`
- **Format des données :** JSON

#### Données attendues :
- `username` (string) ou `email` (string) : Obligatoire (un seul des deux)
- `password` (string) : Obligatoire

#### Réponses possibles :
- **401** : Données obligatoires manquantes ou mot de passe incorrect ou présence à la fois de `username` et `email`
- **200** : Authentification réussie, token renvoyé

#### Exemple de requête :

```json
POST /user/authenticate/
{
  "username": "ArtR",
  "password": "azerty"
}
```

#### Exemple de réponse en cas de succès :

```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MTkyMzMwMTUsImV4cCI6MTcxOTIzNjMyMSwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiQXJ0UiJ9.QPoTXJNzgMkbAsgHKdyDnb1ooF7q6k6m1MdlCFrjvgvFgXKw6zkiSKbEoTsj7Ns3oMrP13gkoUS2ssxkw_FrIOH3VOXOwgNie445ohijCYExJiFuz2KFcE6e_z17EN8_X5Kqxt_vbzov8vNVJ1IShT5XZxgd7zC4EkQ49-4YuO5fJjKvhX3ihUklDKTBrndsE07iSBuITDpjOY5xdaycSIdPqnV1nuLd29XVBt-49vJuhcSKus1b2-xhL8FebQdeXOBQ-4RonHo20ZiVq2aKWUimbFQoXkxFE_402PVghfOMo37-Z4NctBSVc9UB_XV1Xkea67Xh8HvVScoOx-cnUA"
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "status": 401,
  "message": "Données obligatoires manquantes"
}
```

### 3. Affichage d'un utilisateur

-

 **Méthode :** GET
- **URL :** `/user/show/{id}`
- **Format des données :** JSON

#### Réponses possibles :
- **200** : Utilisateur trouvé, données de l'utilisateur renvoyées
- **404** : Utilisateur non trouvé

#### Exemple de requête :

```sh
GET /user/show/9
```

#### Exemple de réponse en cas de succès :

```json
{
  "id": 9,
  "username": "ArtR",
  "firstname": "Arthur",
  "lastname": "Rubiralta",
  "email": "arthur@example.com",
  "phonenumber": "07237845245",
  "country": "France",
  "postalcode": "75001",
  "city": "Paris",
  "adress": "1 Rue de Rivoli"
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "msg": "Not found"
}
```

### 4. Liste des utilisateurs

- **Méthode :** GET
- **URL :** `/user/list`
- **Format des données :** JSON

#### Réponses possibles :
- **200** : Utilisateurs trouvés, liste des utilisateurs renvoyée
- **404** : Aucun utilisateur trouvé

#### Exemple de requête :

```sh
GET /user/list
```

#### Exemple de réponse en cas de succès :

```json
[
  {
    "id": 1,
    "username": "johndoe",
    "firstname": "John",
    "lastname": "Doe",
    "email": "johndoe@example.com",
    "phonenumber": "1234567890",
    "country": "France",
    "postalcode": "75001",
    "city": "Paris",
    "adress": "1 Rue de Rivoli"
  },
  {
    "id": 2,
    "username": "janedoe",
    "firstname": "Jane",
    "lastname": "Doe",
    "email": "janedoe@example.com",
    "phonenumber": "0987654321",
    "country": "France",
    "postalcode": "75002",
    "city": "Paris",
    "adress": "2 Rue de Rivoli"
  }
]
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "msg": "Zero users"
}
```

### 5. Mise à jour d'un utilisateur

- **Méthode :** PUT
- **URL :** `/user/update/{id}`
- **Format des données :** JSON

#### Données attendues (optionnelles) :
- `username` (string)
- `firstname` (string)
- `lastname` (string)
- `email` (string)
- `password` (string)
- `phonenumber` (string)
- `country` (string)
- `postalcode` (string)
- `city` (string)
- `adress` (text)

#### Réponses possibles :
- **200** : Utilisateur mis à jour avec succès
- **404** : Utilisateur non trouvé
- **400** : Erreur lors de la mise à jour

#### Exemple de requête :

```json
PUT /user/update/9
{
  "username": "ArtR",
  "firstname": "ArthurTest",
  "lastname": "RubiraltaTest",
  "email": "testUpdate@gmail.com",
  "password": "azerty",
  "phonenumber": "07237845245",
  "country": "EN",
  "postalcode": "94130",
  "city": "Nogent",
  "adress": "54 rue du marché de nogent"
}
```

#### Exemple de réponse en cas de succès :

```json
{
  "id": 9,
  "username": "ArtR",
  "firstname": "ArthurTest",
  "lastname": "RubiraltaTest",
  "email": "testUpdate@gmail.com",
  "phonenumber": "07237845245",
  "country": "EN",
  "postalcode": "94130",
  "city": "Nogent",
  "adress": "54 rue du marché de nogent"
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "msg": "Not found"
}
```

### 6. Mise à jour du rôle d'un utilisateur

- **Méthode :** PUT
- **URL :** `/user/update-role/{id}`
- **Format des données :** JSON

#### Données attendues :
- `role` (string) : Obligatoire (valeurs possibles : `user`, `admin`)

#### Réponses possibles :
- **200** : Rôle mis à jour avec succès
- **404** : Utilisateur non trouvé ou rôle incorrect

#### Exemple de requête :

```json
PUT /user/update-role/6
{
  "role": "admin"
}
```

#### Exemple de réponse en cas de succès :

```json
{
  "id": 6,
  "username": "janedoe",
  "role": "admin"
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "msg": "Not found"
}
```

ou

```json
{
  "msg": "Choose between role user or admin"
}
```

### 7. Suppression d'un utilisateur

- **Méthode :** DELETE
- **URL :** `/user/delete/{id}`
- **Format des données :** JSON

#### Réponses possibles :
- **200** : Utilisateur supprimé avec succès
- **404** : Utilisateur non trouvé
- **500** : Erreur interne lors de la suppression

#### Exemple de requête :

```sh
DELETE /user/delete/6
```

#### Exemple de réponse en cas de succès :

```json
{
  "success": true
}
```

#### Exemple de réponse en cas d'erreur :

```json
{
  "msg": "Not found"
}
```

ou

```json
{
  "success": false
}
```
