# 🔐 Symfony License Server for WordPress Plugins

Ce projet est un serveur d’activation de licences développé en **Symfony**.  
Il est conçu spécifiquement pour sécuriser et valider les licences des **plugins WordPress**.

Grâce à ce serveur, les développeurs peuvent gérer et contrôler l'accès à leurs extensions WordPress via une vérification simple et sécurisée grâce à une API REST.

---

## ⚙️ Fonctionnalités principales

- 🎫 Génération et gestion de clés de licence uniques.
- 🌐 Validation de licence par nom de domaine (`site_url`).
- ⏳ Contrôle automatique des dates d'expiration des licences.
- 🔒 Réponses JSON claires et structurées.
- 💡 Architecture évolutive prête pour d'autres systèmes.

---

## 📡 Exemple d'utilisation

**URL :**  
`POST /api/check-license`

**Données envoyées (JSON) :**

```json
{
  "license_key": "YALI-LOCAL-TEST-2025",
  "site_url": "example.com"
}
```

**Réponse si la licence est valide :**

```json
{
  "status": "valid",
  "message": "License valid."
}
```

**Réponse si la licence est invalide ou expirée :**

```json
{
  "status": "invalid",
  "message": "Invalid license key or domain mismatch."
}
```

---

## 💡 Pourquoi utiliser ce serveur ?

- Protégez vos plugins WordPress contre une utilisation non autorisée.
- Gérez des licences limitées dans le temps ou spécifiques à un domaine.
- Facilitez la distribution commerciale de vos plugins.
- Préparez une base robuste pour une évolution vers d’autres produits ou CMS.

---

## 🚀 Installation rapide

1. Clonez le projet :  
`git clone https://github.com/votre-compte/symfony-license-server.git`

2. Installez les dépendances :  
`cd symfony-license-server`  
`composer install`

3. Configurez la base de données dans `.env`.

4. Créez la base et appliquez les migrations :  
`php bin/console doctrine:database:create`  
`php bin/console make:migration`  
`php bin/console doctrine:migrations:migrate`

---

## 📢 Notes

Ce serveur est pensé pour WordPress dans sa version actuelle, mais sa structure permet une adaptation future vers :

- Logiciels desktop,
- Applications SAAS,
- Extensions pour d'autres CMS.

---

## 👨‍💻 Auteur

Développé par **Hocine Dev**  
📧 Contact : hocinedev4@gmail.com

---
