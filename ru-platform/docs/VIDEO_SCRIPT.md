# Script vidéo (guide simple)

## Scène 1 — Démarrer le serveur
**Narration:** “Je lance le serveur de l’application.”
```
cd C:\Users\tshopira\OneDrive\Desktop\PROJET\ru-platform
powershell -ExecutionPolicy Bypass -File .\run-all.ps1 -OnlyServe
```

## Scène 2 — Page publique
**Narration:** “Voici la page publique.”
Ouvre:
```
http://127.0.0.1:8000/
```

## Scène 3 — Admin
**Narration:** “J’ouvre l’espace admin.”
Ouvre:
```
http://127.0.0.1:8000/admin
```

## Scène 4 — Connexion
**Narration:** “Je me connecte.”
- Email: `admin@ru.local`
- Mot de passe: `password`
- Clique **Se connecter**

## Scène 5 — Créer un menu test
**Narration:** “Je génère un menu test.”
- Clique **Créer un menu test**

## Scène 6 — Vérifier la page publique
**Narration:** “Les plats apparaissent.”
Retourne sur `http://127.0.0.1:8000/`.

## Scène 7 — Réserver un plat
**Narration:** “Je réserve un plat.”
- Clique **Réserver**
- Choisis une quantité

## Scène 8 — Vérifier les commandes
**Narration:** “La commande est visible.”
Regarde **Mes commandes** sur la page publique.

## Scène 9 — Vérifier dans l’admin
**Narration:** “L’admin voit les réservations et ventes.”
Section **Réservations & Ventes** dans `/admin`.
