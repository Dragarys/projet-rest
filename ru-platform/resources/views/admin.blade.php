<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RU Admin Console</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Serif+4:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d1117;
            --panel: #121923;
            --panel-soft: #182233;
            --text: #f3f7ff;
            --muted: #a7b2c6;
            --brand: #00d1b2;
            --accent: #ff7a59;
            --line: rgba(255, 255, 255, 0.08);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Space Grotesk", system-ui, sans-serif;
            background:
                linear-gradient(180deg, rgba(14, 18, 16, 0.75), rgba(14, 18, 16, 0.85)),
                url('/images/restaurant_bg.jpg'),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            background-size: cover, cover, auto;
            background-attachment: fixed;
        }
        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 20px 64px;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
        }
        h1 { margin: 0; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }
        .card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
        }
        label {
            display: block;
            font-size: 0.85rem;
            color: var(--muted);
            margin-bottom: 6px;
        }
        input, textarea, select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: var(--panel-soft);
            color: var(--text);
            margin-bottom: 12px;
        }
        button {
            padding: 10px 14px;
            border: none;
            border-radius: 10px;
            background: var(--brand);
            color: #031413;
            font-weight: 600;
            cursor: pointer;
        }
        .ghost {
            background: transparent;
            border: 1px solid var(--line);
            color: var(--text);
        }
        .note { color: var(--muted); font-size: 0.85rem; }
        .row { display: flex; gap: 10px; }
        .row > * { flex: 1; }
        .toast {
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(0, 209, 178, 0.12);
            border: 1px solid rgba(0, 209, 178, 0.4);
            font-size: 0.9rem;
        }
        a { color: var(--accent); }
        .chart {
            display: grid;
            gap: 8px;
        }
        .bar {
            height: 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }
        .bar > span {
            display: block;
            height: 100%;
            background: var(--brand);
        }
        .list {
            display: grid;
            gap: 6px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <div>
                <h1>Admin Console</h1>
                <div class="note">Gère menus, stock, plats et stats.</div>
            </div>
            <a href="/">Retour public</a>
        </header>

        <div class="card" style="margin-bottom:16px;">
            <h3>Connexion</h3>
            <div class="row">
                <input id="login-email" placeholder="admin@ru.local">
                <input id="login-pass" type="password" placeholder="password">
                <button onclick="login()">Se connecter</button>
            </div>
            <div class="note">Se connecte et sauvegarde automatiquement le token.</div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <label>Token API</label>
            <div class="row">
                <input id="token" placeholder="Colle le token Bearer ici">
                <button class="ghost" onclick="saveToken()">Sauver</button>
            </div>
            <div class="note">Récupère le token via <code>/api/auth/login</code>.</div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <h3>Création rapide</h3>
            <div class="note">Un clic pour générer ingrédient, plat, stock et menu.</div>
            <div class="row">
                <button onclick="createTestMenu()">Créer un menu test</button>
                <button class="ghost" onclick="deleteLastMenu()">Supprimer dernier menu</button>
            </div>
            <div class="list" id="menu-list" style="margin-top:10px;"></div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <h3>Statistiques rapides</h3>
            <div class="note">Derniers 30 jours, top plats, alertes stock.</div>
            <div class="grid">
                <div class="card">
                    <h4>Fréquentation</h4>
                    <div class="chart" id="stats-attendance"></div>
                </div>
                <div class="card">
                    <h4>Top plats</h4>
                    <div class="list" id="stats-top"></div>
                </div>
            </div>
            <div style="margin-top:10px;">
                <button class="ghost" onclick="loadStats()">Rafraîchir</button>
            </div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <h3>Réservations & Ventes</h3>
            <div class="note">Liste des commandes avec statut (reserved/paid).</div>
            <div class="row">
                <button class="ghost" onclick="loadOrders()">Rafraîchir</button>
            </div>
            <div class="list" id="orders-list" style="margin-top:10px;"></div>
        </div>

        <div class="card" style="margin-bottom:16px;">
            <h3>Avis utilisateurs</h3>
            <div class="note">Derniers avis et notes.</div>
            <div class="row">
                <button class="ghost" onclick="loadReviews()">Rafraîchir</button>
            </div>
            <div class="list" id="reviews-list" style="margin-top:10px;"></div>
        </div>

        <div id="toast" class="toast" style="display:none;"></div>
    </div>

    <script>
        const tokenInput = document.getElementById('token');
        tokenInput.value = localStorage.getItem('ru_token') || '';

        const api = async (path, body) => {
            const token = localStorage.getItem('ru_token') || '';
            const res = await fetch(path, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(body)
            });
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Erreur API');
            }
            return data;
        };

        const apiGet = async (path) => {
            const token = localStorage.getItem('ru_token') || '';
            const res = await fetch(path, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            const data = await res.json();
            if (!res.ok) {
                throw new Error(data.message || 'Erreur API');
            }
            return data;
        };

        const toast = (msg) => {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        };

        function saveToken() {
            localStorage.setItem('ru_token', tokenInput.value.trim());
            toast('Token sauvegardé');
        }

        async function login() {
            try {
                const res = await fetch('/api/auth/login', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        email: document.getElementById('login-email').value,
                        password: document.getElementById('login-pass').value
                    })
                });
                const data = await res.json();
                if (!res.ok) {
                    throw new Error(data.message || 'Login échoué');
                }
                tokenInput.value = data.token;
                saveToken();
                toast('Connecté');
                loadStats();
            } catch (e) {
                toast(e.message);
            }
        }

        async function createIngredient() {
            const data = await api('/api/ingredients', {
                name: document.getElementById('ing-name').value,
                unit: document.getElementById('ing-unit').value
            });
            toast('Ingrédient créé: ID ' + data.id);
        }

        async function createDish() {
            const data = await api('/api/dishes', {
                name: document.getElementById('dish-name').value,
                description: document.getElementById('dish-desc').value,
                price: parseFloat(document.getElementById('dish-price').value || '0'),
                ingredients: [{
                    id: parseInt(document.getElementById('dish-ing-id').value, 10),
                    qty: parseFloat(document.getElementById('dish-ing-qty').value || '0.1')
                }]
            });
            toast('Plat créé: ID ' + data.id);
        }

        async function createMenu() {
            const data = await api('/api/menus', {
                menu_date: document.getElementById('menu-date').value,
                service: document.getElementById('menu-service').value,
                items: [{
                    dish_id: parseInt(document.getElementById('menu-dish-id').value, 10),
                    quantity_limit: parseInt(document.getElementById('menu-limit').value || '50', 10)
                }]
            });
            toast('Menu créé: ID ' + data.id);
        }

        async function createStock() {
            await api('/api/stock/movements', {
                ingredient_id: parseInt(document.getElementById('stock-ing-id').value, 10),
                delta_qty: parseFloat(document.getElementById('stock-qty').value || '1'),
                reason: document.getElementById('stock-reason').value || 'Stock update'
            });
            toast('Stock mis à jour');
        }

        async function loadStats() {
            try {
                const [attendance, top, dishes] = await Promise.all([
                    apiGet('/api/stats/attendance'),
                    apiGet('/api/stats/top-dishes'),
                    fetch('/api/dishes').then(r => r.json())
                ]);

                const dishMap = new Map();
                dishes.forEach(d => dishMap.set(d.id, d.name));

                const attRoot = document.getElementById('stats-attendance');
                attRoot.innerHTML = '';
                const max = Math.max(...attendance.map(a => a.reservations || 0), 1);
                attendance.slice(0, 7).forEach(a => {
                    const row = document.createElement('div');
                    row.innerHTML = `
                        <div class="note">${a.day}: ${a.reservations}</div>
                        <div class="bar"><span style="width:${Math.round((a.reservations / max) * 100)}%"></span></div>
                    `;
                    attRoot.appendChild(row);
                });

                const topRoot = document.getElementById('stats-top');
                topRoot.innerHTML = '';
                top.slice(0, 5).forEach(t => {
                    const name = dishMap.get(t.dish_id) || `Plat #${t.dish_id}`;
                    const row = document.createElement('div');
                    row.textContent = `${name} · ${t.total}`;
                    topRoot.appendChild(row);
                });

            } catch (e) {
                toast('Stats indisponibles (token requis)');
            }
        }

        async function loadOrders() {
            try {
                const orders = await apiGet('/api/orders');
                const root = document.getElementById('orders-list');
                root.innerHTML = '';
                if (!orders.length) {
                    root.textContent = 'Aucune commande.';
                    return;
                }
                orders.slice(0, 20).forEach(o => {
                    const row = document.createElement('div');
                    const status = o.status || 'unknown';
                    const items = (o.items || []).map(i => `${i.dish?.name || 'Plat'} x${i.quantity}`).join(', ');
                    row.textContent = `#${o.id} · ${status} · ${o.total_amount ?? 0} € · ${items || 'Sans items'}`;
                    root.appendChild(row);
                });
            } catch (e) {
                toast('Impossible de charger les commandes');
            }
        }

        async function loadReviews() {
            try {
                const reviews = await apiGet('/api/reviews');
                const root = document.getElementById('reviews-list');
                root.innerHTML = '';
                if (!reviews.length) {
                    root.textContent = 'Aucun avis.';
                    return;
                }
                reviews.slice(0, 20).forEach(r => {
                    const row = document.createElement('div');
                    const name = r.dish?.name ? `Plat: ${r.dish.name}` : `Menu: ${r.menu_id ?? '-'}`;
                    row.textContent = `#${r.id} · ${name} · Note: ${r.rating} · ${r.comment ?? ''}`;
                    root.appendChild(row);
                });
            } catch (e) {
                toast('Impossible de charger les avis');
            }
        }

        async function createTestMenu() {
            const suffix = new Date().toISOString().replace(/[-:.TZ]/g, '');
            const ingredient = await api('/api/ingredients', { name: `Test Ingredient ${suffix}`, unit: 'kg' });
            await api('/api/stock/movements', { ingredient_id: ingredient.id, delta_qty: 50, reason: 'Initial stock' });
            const dishes = [
                { name: `Pesto Pasta ${suffix}`, price: 7.5, image_url: '/images/dishes/pesto_pasta.jpg' },
                { name: `Salade Fraiche ${suffix}`, price: 5.2, image_url: '/images/dishes/salad_bowl.jpg' },
                { name: `Riz Saute ${suffix}`, price: 6.0, image_url: '/images/dishes/fried_rice.jpg' }
            ];
            const created = [];
            for (const d of dishes) {
                const dish = await api('/api/dishes', {
                    name: d.name,
                    description: 'Plat test',
                    price: d.price,
                    image_url: d.image_url,
                    ingredients: [{ id: ingredient.id, qty: 0.5 }]
                });
                created.push(dish);
            }
            const menuDate = new Date();
            const day = String(menuDate.getDate()).padStart(2, '0');
            const month = String(menuDate.getMonth() + 1).padStart(2, '0');
            const year = menuDate.getFullYear();
            const service = Math.random() > 0.5 ? 'lunch' : 'dinner';
            const items = created.map(d => ({ dish_id: d.id, quantity_limit: 50 }));
            const menu = await api('/api/menus', {
                menu_date: `${year}-${month}-${day}`,
                service,
                items
            });
            toast(`Menu test créé: ID ${menu.id}`);
            loadMenus();
        }

        async function loadMenus() {
            try {
                const menus = await apiGet('/api/menus');
                const root = document.getElementById('menu-list');
                root.innerHTML = '';
                if (!menus.length) {
                    root.textContent = 'Aucun menu.';
                    return;
                }
                menus.slice(0, 10).forEach(m => {
                    const row = document.createElement('div');
                    row.textContent = `Menu #${m.id} · ${m.menu_date} · ${m.service}`;
                    row.style.display = 'flex';
                    row.style.justifyContent = 'space-between';
                    row.style.alignItems = 'center';
                    const btn = document.createElement('button');
                    btn.textContent = 'Supprimer';
                    btn.className = 'ghost';
                    btn.onclick = () => deleteMenu(m.id);
                    row.appendChild(btn);
                    root.appendChild(row);
                });
            } catch (e) {
                toast('Impossible de lister les menus');
            }
        }

        async function deleteMenu(id) {
            try {
                const token = localStorage.getItem('ru_token') || '';
                const res = await fetch(`/api/menus/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                if (!res.ok) {
                    throw new Error('Suppression échouée');
                }
                toast(`Menu #${id} supprimé`);
                loadMenus();
            } catch (e) {
                toast(e.message);
            }
        }

        async function deleteLastMenu() {
            try {
                const menus = await apiGet('/api/menus');
                if (!menus.length) {
                    toast('Aucun menu à supprimer');
                    return;
                }
                const last = menus[0];
                await deleteMenu(last.id);
            } catch (e) {
                toast('Suppression échouée');
            }
        }

        loadStats();
        loadOrders();
        loadReviews();
    </script>
</body>
</html>
