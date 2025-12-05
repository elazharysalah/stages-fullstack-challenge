# 🎯 Challenge Technique - Description Détaillée

## 📖 Contexte de la Mission

Vous êtes recruté en tant que **développeur full-stack stagiaire** dans une agence web.

### Votre premier jour

L'équipe vous confie une mission : **"Rescue Mission - Blog Platform"**

> *"On a une plateforme de blog qui fonctionne... mais avec des problèmes. Le client se plaint de bugs, de lenteurs et de problèmes de sécurité. Le développeur précédent est parti et on a besoin que tu nettoies tout ça. On a créé un backlog avec les tickets prioritaires. Règle-nous au moins 70% des problèmes et on en discute après !"*

**C'est parti !** Bienvenue dans le monde réel du développement. 🚀

---

## 🎯 Objectif du Challenge

### Mission principale

Résoudre **au moins 7-8 tickets sur 10** (≈ 70% des points) du backlog [TICKETS.md](./TICKETS.md)

Les tickets couvrent :
- 🐛 **Bugs** fonctionnels (4 tickets - 30 pts)
- 🔒 **Failles de sécurité** (3 tickets - 30 pts)
- ⚡ **Problèmes de performance** (3 tickets - 26 pts)

**Total : 10 tickets - 86 points possibles** (dont 4 pts bonus sur PERF-002)

### Ce qui est évalué

1. **Compétences techniques** (60%)
   - Capacité à identifier et corriger les problèmes
   - Qualité des solutions proposées
   - Respect des bonnes pratiques

2. **Méthodologie** (25%)
   - Approche de debugging
   - Documentation du travail
   - Organisation du code

3. **Compréhension** (15%)
   - Explication des causes racines
   - Justification des choix techniques
   - Anticipation des impacts

---

## 🛠️ Technologies & Stack Technique

### Backend
- **PHP 7.4**
- **Laravel 10.x**
- **MySQL 8.0**

### Frontend
- **React 18**
- **Vite** (build tool)
- **Axios** (API calls)

### Infrastructure
- **Docker** & **Docker Compose**
- Serveur web : Apache/Nginx
- Node.js 20

---

## 📋 La Plateforme de Blog

### Fonctionnalités existantes

1. **Gestion des articles**
   - Créer, lire, modifier, supprimer des articles
   - Chaque article : titre, contenu, auteur, date de publication
   - Upload d'image pour l'article

2. **Système de commentaires**
   - Ajouter des commentaires sur les articles
   - Supprimer des commentaires

3. **Recherche**
   - Rechercher des articles par titre ou contenu

4. **Statistiques**
   - Nombre total d'articles
   - Nombre de commentaires
   - Articles les plus commentés

### Architecture

```
/project/
├── backend/              # API Laravel
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/api.php
│   └── .env.example
│
├── frontend/            # Application React
│   ├── src/
│   │   ├── components/
│   │   ├── services/
│   │   └── App.jsx
│   └── package.json
│
└── docker-compose.yml
```

---

## 🚀 Instructions de Travail

### 0. Forker le repository (IMPORTANT)

**Avant tout**, forkez le repository sur votre compte GitHub :

👉 https://github.com/voidagency/stages-fullstack-challenge.git

Cliquez sur le bouton **"Fork"** en haut à droite.

> [!IMPORTANT]
> Votre fork doit être **privé**. Ajoutez **admin[at]void[dot]fr** comme collaborateur (lecture seule).
> Un fork public = candidature annulée.

### 1. Setup de l'environnement

```bash
# Cloner VOTRE fork
git clone https://github.com/VOTRE-USERNAME/stages-fullstack-challenge.git
cd stages-fullstack-challenge/project

# Lancer Docker
docker-compose up -d

# Le backend sera accessible sur http://localhost:8000
# Le frontend sera accessible sur http://localhost:3000
```

Suivez les instructions détaillées dans `project/README.md`

### 2. Exploration du code

- Familiarisez-vous avec l'architecture
- Testez les fonctionnalités manuellement
- Identifiez les problèmes évidents

### 3. Résolution des tickets

Consultez **[TICKETS.md](./TICKETS.md)** pour la liste complète et le workflow Git détaillé.

**Workflow pour chaque ticket** :
- ✅ Créez une branche dédiée (`BUG-001`, `SEC-002`, etc.)
- ✅ Faites vos corrections et committez régulièrement
- ✅ Créez une Pull Request avec une description complète
- ✅ Mergez la PR une fois les tests passés
- ✅ Passez au ticket suivant

**Conseils** :
- 🎯 Commencez par les tickets qui vous semblent accessibles
- 📖 Lisez bien la description de chaque ticket
- 🧪 Testez vos corrections avant de merger
- 💬 Messages de commit clairs : `fix(search): correct collation [BUG-001]`

### 4. Documentation via Pull Requests

Pour chaque ticket résolu, **créez une Pull Request** avec une description complète.

GitHub affichera automatiquement le template `.github/pull_request_template.md` lors de la création.

**Remplissez toutes les sections** :
- 📋 Problème identifié (cause racine)
- 🛠️ Solution implémentée (changements techniques)
- ✅ Tests effectués (comment vérifier)
- 💭 Réponses aux questions à considérer

**Exemple de titre de PR** :
```
[BUG-001] La recherche ne fonctionne pas avec les accents
```

La description de la PR remplace le traditionnel fichier `RAPPORT.md` et offre une meilleure traçabilité

---

## 🤖 Usage de l'IA et Ressources Externes

### ✅ Ce qui est AUTORISÉ

- **ChatGPT, Claude, Copilot** et autres outils IA
- **Google, StackOverflow, documentation officielle**
- **Tutoriels, articles de blog**
- **Forums et communautés**

### ⚠️ Ce qui est ATTENDU

Lors de l'entretien oral qui suivra, vous devrez :
- ✅ **Expliquer** chaque correction effectuée
- ✅ **Justifier** vos choix techniques
- ✅ **Comprendre** le code que vous avez écrit/corrigé

**En 2025, savoir utiliser l'IA efficacement est une compétence.** Ce qui compte : votre capacité à valider, comprendre et adapter le code généré.

---

## 📦 Livrables Attendus

### 1. Fork GitHub avec Pull Requests

**Livrable principal** : Lien vers votre fork GitHub

📌 **Format de soumission** :
```
https://github.com/VOTRE-USERNAME/stages-fullstack-challenge
```

Assurez-vous que :
- ✅ Toutes les PRs sont **mergées** dans votre branche `main`
- ✅ Les PRs sont **visibles** et bien documentées
- ✅ Chaque PR correspond à un ticket résolu
- ✅ L'historique Git est **propre** avec des commits explicites

### 2. Code fonctionnel

Votre branche `main` doit contenir :
- ✅ Le code corrigé et fonctionnel
- ✅ Le projet peut être lancé via Docker
- ✅ Les corrections sont testables

### 3. Pull Requests bien documentées

Chaque PR doit inclure :
- 📋 Description du problème identifié
- 🛠️ Solution technique implémentée
- ✅ Tests effectués et validés
- 💭 Réponses aux questions à considérer (voir TICKETS.md)

---

## 📊 Critères de Réussite

### Pour être invité à l'entretien oral

**Minimum requis** : 
- ✅ Au moins **60 points sur 86** (≈70%) - soit environ **7-8 tickets résolus sur 10**
- ✅ Code fonctionnel et bien structuré
- ✅ Pull Requests bien documentées

**Bonus appréciés** :
- 🌟 Tous les tickets résolus (10/10) avec les bonus frontend
- 🌟 Améliorations supplémentaires non demandées
- 🌟 Tests automatisés ajoutés
- 🌟 Refactoring de code legacy

### Barème détaillé

| Catégorie | Tickets | Points | Difficulté |
|-----------|---------|--------|------------|
| 🐛 Bugs fonctionnels | 4 | 30 pts | ⭐ Facile à ⭐⭐ Moyen |
| 🔒 Sécurité | 3 | 30 pts | ⭐⭐ Moyen à ⭐⭐⭐ Difficile |
| ⚡ Performance | 3 | 26 pts (dont 4 pts bonus) | ⭐⭐ Moyen à ⭐⭐⭐ Difficile |
| **Total** | **10** | **86 pts** | **Minimum 60 pts requis** |

**Note** : PERF-002 offre 8 pts (backend obligatoire) + 4 pts bonus (frontend optionnel)

---

## 🎤 Entretien Oral Technique

### Après l'évaluation de votre code

Si vous atteignez le seuil requis (70%), vous serez invité à un **entretien technique** d'environ **30-45 minutes**.

### Format de l'entretien

1. **Revue de code** (15-20 min)
   - Vous présentez vos corrections
   - Vous expliquez vos choix techniques
   - Discussion sur les difficultés rencontrées

2. **Questions techniques** (10-15 min)
   - Compréhension des concepts (API REST, React hooks, SQL, etc.)
   - Questions sur des scénarios alternatifs
   - "Que se passerait-il si... ?"

3. **Simulation de debugging live** (10 min)
   - On introduit un nouveau bug en direct
   - Vous devez le résoudre ou expliquer votre démarche

### Préparation recommandée

- 📖 Relisez votre code avant l'entretien
- 🧠 Préparez l'explication de chaque correction
- 💬 Soyez prêt à parler de ce que vous avez appris
- 🤔 Identifiez les points faibles / ce que vous auriez pu améliorer

---

## ⏱️ Durée & Organisation

### Temps recommandé

**8 à 10 heures** au total (selon votre niveau)

### Planning suggéré

| Phase | Durée | Activités | Tickets |
|-------|-------|-----------|---------|
| **Setup & exploration** | 1h | Installation, compréhension du code | - |
| **Phase 1 - Quick Wins** | 2h | Bugs simples (BUG-001, 002, 004) | 3 tickets faciles |
| **Phase 2 - Sécurité critique** | 2-3h | SEC-001, SEC-002 | 2 tickets critiques |
| **Phase 3 - Performance** | 2h | PERF-001, PERF-003 | 2 tickets moyens |
| **Phase 4 - Complexe** | 2-3h | BUG-003, SEC-003, PERF-002 | 3 tickets difficiles |
| **Documentation** | 1-2h | Rédaction des Pull Requests (10-15 min/ticket) | - |
| **Tests finaux** | 30min | Vérification globale | - |

**Total réaliste : 8-11h pour 7-8 tickets résolus**

### Format flexible

- ✅ Vous pouvez travailler en **plusieurs sessions**
- ✅ Pas de limite de temps stricte
- ✅ L'important : la qualité, pas la vitesse

---

## 🆘 En Cas de Blocage

### Déblocage autonome (privilégié)

1. **Lisez les logs d'erreur** attentivement
2. **Googlez** le message d'erreur exact
3. **Consultez la documentation** officielle (Laravel, React)
4. **Utilisez l'IA** pour analyser le code

### Besoin d'aide ?

Si vous êtes bloqué plus de **2 heures** sur un ticket :
- ✅ Passez au suivant, revenez-y plus tard
- ✅ Documentez le blocage dans la description de votre PR
- ✅ En cas d'urgence : contactez le recruteur

**Important** : Votre capacité à vous débloquer fait partie de l'évaluation !

---

## ✨ Conseils pour Réussir

### Approche méthodologique

1. **Lisez d'abord tous les tickets** avant de commencer
2. **Priorisez** : commencez par ce qui vous semble faisable
3. **Testez au fur et à mesure**, ne corrigez pas tout d'un coup
4. **Committez régulièrement** avec des messages explicites
5. **Documentez pendant, pas après**

### Mindset

- 🎯 **Focus qualité** > quantité
- 🧠 **Comprenez** avant de copier-coller
- 💬 **Expliquez clairement** vos choix
- 🔍 **Soyez méthodique** dans le debugging
- 🤝 **Demandez de l'aide** si vraiment bloqué

### Erreurs à éviter

- ❌ Corriger sans comprendre
- ❌ Oublier de tester vos modifications
- ❌ Ne pas documenter votre travail
- ❌ Modifier sans committer
- ❌ Abandonner trop vite

---

## 🎓 Ce Que Vous Allez Apprendre

Ce challenge vous expose à des situations réelles :

- 🔍 **Debugging** méthodique et analyse de code
- 🛠️ **Maintenance** d'applications existantes
- 🔒 **Sécurité** web (OWASP, injection SQL, XSS)
- ⚡ **Optimisation** de performances (N+1, cache, indexes)
- 🔧 **Migration** de versions PHP
- 📝 **Documentation** technique
- 🤖 **Usage intelligent de l'IA** comme outil

**Ces compétences sont 10× plus demandées que "créer une TODO app" !**

---

**Prêt à relever le défi ?** 

👉 **Consultez maintenant [TICKETS.md](./TICKETS.md) pour voir les problèmes à résoudre !**

Bonne chance ! 🚀

