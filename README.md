
# Task Manager (Tasken Project)

Task Manager est une application web de gestion de tâches collaborative développée avec Laravel, React (Inertia.js), et Tailwind CSS.

## Fonctionnalités principales

- Gestion des tâches, sous-tâches et catégories
- Authentification et rôles utilisateurs (admin, utilisateur)
- Interface moderne et responsive
- Système de progression, priorités, échéances
- Notifications et feedback utilisateur
- Tests automatisés (Pest, Dusk)

## Prérequis

- PHP >= 8.1
- Composer
- Node.js & npm
- SQLite (ou autre SGBD compatible Laravel)
- Google Chrome ou Chromium (pour les tests Dusk)

## Installation

1. **Cloner le dépôt**

	```bash
	git clone <url-du-repo>
	cd tasken-project
	```

2. **Installer les dépendances PHP**

	```bash
	composer install
	```

3. **Installer les dépendances JS**

	```bash
	npm install
	```

4. **Configurer l'environnement**

	- Copier `.env.example` en `.env` et adapter les variables (DB, mail, etc.)
	- Générer la clé d'application :

```bash
php artisan key:generate
```

5. **Lancer les migrations et seeders**

	```bash
	php artisan migrate --seed
	```

6. **Démarrer le serveur de développement**

	```bash
	php artisan serve
	npm run dev
	```

## Tests

- **Unitaires & Feature** :

```bash
./vendor/bin/pest
```

- **End-to-end (Dusk)** :

```bash
php artisan dusk
```

## Structure du projet

- `app/` : Backend Laravel (contrôleurs, modèles, policies...)
- `resources/js/` : Frontend React (pages, composants)
- `database/` : Migrations, seeders, factories
- `tests/` : Tests unitaires, feature et Dusk

## Contribution

Les contributions sont les bienvenues !

- Forkez le projet
- Créez une branche (`feature/ma-fonctionnalite`)
- Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT.

---
