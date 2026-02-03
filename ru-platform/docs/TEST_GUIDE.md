# Guide de test (pas à pas)

## 1) Démarrer le serveur
```powershell
cd C:\Users\tshopira\OneDrive\Desktop\PROJET\ru-platform
powershell -ExecutionPolicy Bypass -File .\run-all.ps1 -OnlyServe
```

## 2) Ouvrir la page publique
```
http://127.0.0.1:8000/
```

## 3) Ouvrir l’admin
```
http://127.0.0.1:8000/admin
```

## 4) Se connecter dans l’admin
- Email: `admin@ru.local`
- Mot de passe: `password`
- Clique **Se connecter**

## 5) Créer un menu test
Dans **Création rapide**, clique **Créer un menu test**.

## 6) Vérifier les menus côté public
Retourne sur `http://127.0.0.1:8000/` et vérifie que les plats s’affichent.

## 7) Réserver un plat
Sur la page publique:
- Clique **Réserver**
- Choisis la quantité

## 8) Vérifier la commande
La commande apparaît dans **Mes commandes**.

## 9) Vérifier côté admin
Dans `/admin`, regarde **Réservations & Ventes**.

---

## Exporter en PDF
Ouvre ce fichier et imprime en PDF:
- Windows: ouvrir `docs/TEST_GUIDE.md`, puis **Imprimer** → **Microsoft Print to PDF**.
