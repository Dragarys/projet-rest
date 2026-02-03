# Tests et couverture

## Lancer les tests
```
php artisan test
```

## Couverture de tests
Laravel utilise PHPUnit. Pour generer la couverture, installe et active une extension de couverture:
- Xdebug (simple) ou PCOV (plus rapide)

Commandes:
```
php artisan test --coverage
php artisan test --coverage-text
```

## Couverture HTML sans extension (PHPDBG)
Si tu n'as pas Xdebug/PCOV, utilise PHPDBG (fourni avec PHP):
```
C:\php\phpdbg.exe -qrr vendor\bin\phpunit --coverage-html storage\coverage
```

Si la couverture ne fonctionne pas, verifie que l extension est active:
```
php -m | Select-String -Pattern 'xdebug|pcov'
```
