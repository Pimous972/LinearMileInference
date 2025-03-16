# 📌 Suivi Kilométrage des Véhicules

## 🚀 Présentation
Ce projet PHP permet de **suivre et estimer le kilométrage** des véhicules en se basant sur les relevés effectués lors des contrôles techniques.

### 🎯 Fonctionnalités
✅ **Enregistrement des relevés kilométriques**
✅ **Estimation du kilométrage à une date donnée**
✅ **Affichage des relevés sous forme de tableau**
✅ **Visualisation graphique de l'évolution du kilométrage**

## 📞 Notre Échange
L'idée est née d'une discussion où l'objectif était d'obtenir **une estimation fiable du kilométrage d'un véhicule** en fonction des relevés existants.

L'évolution du projet :
1. **Calcul du kilométrage estimé** en fonction des relevés (interpolation linéaire)
2. **Création d'une interface web** avec formulaire, tableau et graphique
3. **Intégration d'un graphique interactif** avec Chart.js

## 🏗 Structure du Projet
```
📂 projet-kilometrage
│── 📜 index.php          # Interface principale
│── 📜 db.sql             # Script de création de la base de données
│── 📜 README.md          # Documentation
```

## 🛠 Installation
### 1️⃣ Base de données
Créer une base de données `gestion_kilometrage` et exécuter le script `db.sql`.

### 2️⃣ Configuration PHP
Modifier les paramètres de connexion dans `index.php` :
```php
$host = 'localhost';
$dbname = 'gestion_kilometrage';
$username = 'root';
$password = '';
```

### 3️⃣ Lancer le projet
- Placer les fichiers dans un serveur local (XAMPP, WAMP, LAMP...)
- Accéder à `http://localhost/projet-kilometrage/index.php`

## 🖥 Technologies Utilisées
- **PHP** (Backend)
- **MySQL** (Base de données)
- **Bootstrap** (Interface responsive)
- **Chart.js** (Graphique interactif)

## 🔥 Fonctionnalités Futures
🔹 Ajout d'un filtre par période
🔹 Exportation des relevés en CSV
🔹 Interface d'ajout des relevés directement via l'interface web

Merci d'avoir suivi ce projet ! 🚗💨

