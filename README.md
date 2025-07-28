# 🚀 Projet Symfony API

## 📌 Description

Ce projet est une API construite avec le framework **Symfony**. Elle fournit plusieurs endpoints pour la gestion des utilisateurs (enregistrement, authentification, etc.).

## 🛠️ Versions utilisées

* **Symfony CLI** : 5.9.1
  🔗 [Installation Linux](https://symfony.com/download) | [Installation Windows](https://symfony.com/download)
* **Composer** : 2.7.7
  🔗 [Installation Linux](https://getcomposer.org/download/) | [Installation Windows](https://getcomposer.org/download/)
* **PHP** : 8.3.8
  🔗 [Installation Linux](https://www.php.net/manual/fr/install.unix.php) | [Installation Windows](https://windows.php.net/download/)

---

## 💻 Installation

### 🔹 Linux

1️⃣ **Installer PHP 8.3**

```bash
sudo apt install php8.3
sudo update-alternatives --set php /usr/bin/php8.3
```

2️⃣ **Installer Symfony CLI**

```bash
wget https://get.symfony.com/cli/installer -O - | bash
export PATH="$HOME/.symfony5/bin:$PATH"
source ~/.bashrc
```

3️⃣ **Installer Composer**

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
```

---

### 🔹 Windows

1️⃣ **Installer PHP 8.3**

* 🔗 [Télécharger PHP](https://windows.php.net/download/)
* Extraire et ajouter le chemin de PHP dans les variables d'environnement.

2️⃣ **Installer Symfony CLI**

* Avec [Scoop](https://scoop.sh/) :

```powershell
iwr -useb get.scoop.sh | iex
scoop install symfony-cli
```

3️⃣ **Installer Composer**

* 🔗 [Télécharger l'installateur](https://getcomposer.org/download/)

---

## 📚 Endpoints de l'API

👉 Tous les endpoints et leur documentation complète sont disponibles sur Notion :
🔗 [Voir les endpoints](https://www.notion.so/Endpoints-e9e8a120ef0a4352920c79a1f08d9455)

---

## ✅ Lancement du projet

```bash
php bin/console app:db:rebuild
```

```bash
symfony server:start --port=8090
```

---
