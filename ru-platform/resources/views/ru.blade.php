<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RU Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Source+Serif+4:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0e1210;
            --panel: #141b17;
            --panel-soft: #1b241e;
            --text: #f7f4ee;
            --muted: #c7c0b2;
            --brand: #f2c94c;
            --accent: #e67e22;
            --line: rgba(247, 244, 238, 0.08);
            --glow: rgba(242, 201, 76, 0.25);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Space Grotesk", system-ui, sans-serif;
            background:
                radial-gradient(1100px 500px at 10% -10%, rgba(242, 201, 76, 0.18), transparent 60%),
                radial-gradient(900px 600px at 90% 0%, rgba(230, 126, 34, 0.18), transparent 60%),
                linear-gradient(180deg, rgba(14, 18, 16, 0.75), rgba(14, 18, 16, 0.85)),
                url('/images/restaurant_bg.jpg'),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            background-size: cover, cover, cover, auto;
            background-attachment: fixed;
        }

        .grid {
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(247,244,238,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(247,244,238,0.03) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: 0.25;
        }

        .wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 48px 20px 64px;
        }

        .hero {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 28px;
            align-items: center;
            margin-bottom: 36px;
        }

        .hero-card {
            background: linear-gradient(145deg, rgba(20, 27, 23, 0.95), rgba(12, 16, 13, 0.95));
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.45);
        }

        .hero h1 {
            font-size: clamp(2rem, 3vw + 1rem, 3.2rem);
            margin: 0 0 12px 0;
        }

        .hero p {
            color: var(--muted);
            line-height: 1.6;
            font-family: "Source Serif 4", serif;
            font-size: 1.05rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            background: rgba(242, 201, 76, 0.12);
            border: 1px solid rgba(242, 201, 76, 0.35);
            color: var(--text);
        }

        .quick {
            display: flex;
            gap: 10px;
            margin-top: 18px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 12px;
            border: 1px solid transparent;
            background: var(--brand);
            color: #1a1405;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 10px 30px var(--glow);
        }

        .btn.secondary {
            background: transparent;
            border-color: var(--line);
            color: var(--text);
            box-shadow: none;
        }

        .stats {
            display: grid;
            gap: 12px;
        }

        .stat {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
        }

        .stat .label { color: var(--muted); font-size: 0.9rem; }
        .stat .value { font-size: 1.4rem; font-weight: 700; }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 28px 0 12px;
        }

        .menus {
            display: grid;
            gap: 14px;
        }

        .menu-card {
            background: linear-gradient(180deg, rgba(27, 36, 30, 0.95), rgba(20, 27, 23, 0.95));
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
        }

        .menu-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }

        .tag {
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 0.75rem;
            background: rgba(230, 126, 34, 0.15);
            border: 1px solid rgba(230, 126, 34, 0.5);
        }

        .menu-items {
            display: grid;
            gap: 10px;
        }

        .dish {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 12px;
            background: rgba(14, 18, 16, 0.6);
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
            gap: 12px;
        }

        .dish-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dish-img {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.06);
            background: rgba(0,0,0,0.2);
        }

        .dish small {
            color: var(--muted);
        }

        .price {
            font-weight: 700;
            color: var(--brand);
        }

        .reserve {
            padding: 6px 10px;
            border-radius: 10px;
            background: rgba(242, 201, 76, 0.15);
            border: 1px solid rgba(242, 201, 76, 0.5);
            color: var(--text);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: rgba(242, 201, 76, 0.12);
            border: 1px solid rgba(242, 201, 76, 0.4);
            color: var(--text);
            padding: 10px 14px;
            border-radius: 10px;
            display: none;
        }

        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            width: min(420px, 100%);
        }

        .modal-card input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid var(--line);
            background: var(--panel-soft);
            color: var(--text);
            margin: 10px 0 14px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .ghost {
            background: transparent;
            border: 1px solid var(--line);
            color: var(--text);
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 16px;
            margin-top: 18px;
        }

        .panel h3 {
            margin-top: 0;
        }

        .orders {
            display: grid;
            gap: 10px;
        }

        .order-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(11, 15, 20, 0.6);
            border-radius: 12px;
            padding: 10px 12px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .rating {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .rating input {
            width: 80px;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1px solid var(--line);
            background: var(--panel-soft);
            color: var(--text);
        }

        .gallery {
            margin-top: 22px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
        }

        .gallery-card {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: rgba(11, 15, 20, 0.6);
        }

        .gallery-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .gallery-card .caption {
            padding: 10px 12px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .footer {
            margin-top: 28px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        @media (max-width: 900px) {
            .hero {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="grid"></div>
    <div class="wrap">
        <section class="hero">
            <div class="hero-card">
                <span class="badge">RU Platform · Menus en direct</span>
                <h1>Votre restaurant universitaire, en une vue claire.</h1>
            </div>
            <div class="stats">
                <div class="stat">
                    <div class="label">Menus chargés</div>
                    <div class="value" id="stat-menus">0</div>
                </div>
                <div class="stat">
                    <div class="label">Plats servis</div>
                    <div class="value" id="stat-dishes">0</div>
                </div>
                <div class="stat">
                    <div class="label">Dernière mise à jour</div>
                    <div class="value" id="stat-updated">—</div>
                </div>
            </div>
        </section>

        <div class="section-title">
            <h2>Menus disponibles</h2>
            <span class="badge" id="status">Chargementâ€¦</span>
        </div>

        <div class="menus" id="menus"></div>

        <div class="panel">
            <h3>Mes commandes</h3>
            <div class="orders" id="orders"></div>
        </div>

        <div class="panel">
            <h3>Laisser un avis</h3>
            <div class="note">Renseigne l’ID du plat et une note.</div>
            <div class="rating">
                <input id="review-dish" placeholder="ID plat">
                <input id="review-rating" type="number" min="1" max="5" value="5">
            </div>
            <input id="review-comment" placeholder="Commentaire (optionnel)" style="margin-top:10px;">
            <button class="btn" onclick="submitReview()">Envoyer</button>
        </div>

    </div>

    <div class="modal" id="modal">
        <div class="modal-card">
            <h3>Réserver un plat</h3>
            <div id="modal-dish" class="note"></div>
            <label>Quantité</label>
            <input id="modal-qty" type="number" min="1" value="1">
            <div class="note">Le token est récupéré depuis le localStorage (utilise l’admin pour le sauvegarder).</div>
            <div class="modal-actions">
                <button class="btn ghost" onclick="closeModal()">Annuler</button>
                <button class="btn" onclick="confirmReservation()">Réserver</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        let selected = null;

        const formatDate = (iso) => {
            try {
                return new Date(iso).toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long' });
            } catch (e) {
                return iso;
            }
        };

        const fetchMenus = async () => {
            const res = await fetch('/api/menus');
            const data = await res.json();
            return Array.isArray(data) ? data : [];
        };

        const fetchOrders = async () => {
            const token = localStorage.getItem('ru_token');
            if (!token) return [];
            const res = await fetch('/api/orders', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (!res.ok) return [];
            return await res.json();
        };

        const renderOrders = (orders) => {
            const root = document.getElementById('orders');
            root.innerHTML = '';
            if (!orders.length) {
                root.innerHTML = '<div class="order-row">Aucune commande.</div>';
                return;
            }
            orders.forEach(o => {
                const items = (o.items || []).map(i => `${i.dish?.name || 'Plat'} x${i.quantity}`).join(', ');
                const btn = o.status !== 'paid'
                    ? `<button class="reserve" onclick="payOrder(${o.id})">Payer</button>`
                    : `<span class="badge">Payé</span>`;
                const row = document.createElement('div');
                row.className = 'order-row';
                row.innerHTML = `
                    <div>
                        <strong>Commande #${o.id}</strong><br>
                        <small>Statut: ${o.status} · Total: ${o.total_amount ?? 0} €</small><br>
                        <small>${items || 'Aucun plat'}</small>
                    </div>
                    ${btn}
                `;
                root.appendChild(row);
            });
        };

        const renderMenus = (menus) => {
            const root = document.getElementById('menus');
            root.innerHTML = '';
            const totalDishes = menus.reduce((acc, m) => acc + (m.items?.length || 0), 0);

            document.getElementById('stat-menus').textContent = menus.length;
            document.getElementById('stat-dishes').textContent = totalDishes;
            document.getElementById('stat-updated').textContent = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

            const status = document.getElementById('status');
            status.textContent = menus.length ? 'Données chargées' : 'Aucun menu';

            if (!menus.length) {
                root.innerHTML = '<div class="menu-card">Aucun menu trouvé. Lance <code>test-api.ps1</code>.</div>';
                return;
            }

            const seenDishes = new Set();
            menus.forEach(menu => {
                const card = document.createElement('div');
                card.className = 'menu-card';

                const items = (menu.items || []).filter(item => {
                    const name = item.dish?.name || `Plat-${item.dish_id}`;
                    if (seenDishes.has(name)) {
                        return false;
                    }
                    seenDishes.add(name);
                    return true;
                }).map(item => {
                    const remaining = item.quantity_limit === null ? 'âˆž' : Math.max(item.quantity_limit - item.sold_count, 0);
                    const dishId = item.dish?.id ?? item.dish_id;
                    const img = item.dish?.image_url || '/images/dish.svg';
                    return `
                        <div class="dish">
                            <div class="dish-left">
                                <img class="dish-img" src="${img}" alt="${item.dish?.name || 'Plat'}">
                                <div>
                                    <strong>${item.dish?.name || 'Plat'}</strong><br>
                                    <small>Restant: ${remaining}</small>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="price">${(item.dish?.price ?? 0).toFixed(2)} €</div>
                                <button class="reserve" onclick="openModal(${menu.id}, ${dishId})">Réserver</button>
                            </div>
                        </div>
                    `;
                }).join('');

                card.innerHTML = `
                    <div class="menu-items">${items || '<div class="dish">Aucun plat</div>'}</div>
                `;

                root.appendChild(card);
            });
        };

        fetchMenus()
            .then(renderMenus)
            .catch(() => {
                document.getElementById('status').textContent = 'Erreur API';
                document.getElementById('menus').innerHTML = '<div class="menu-card">Impossible de charger les menus.</div>';
            });

        fetchOrders()
            .then(renderOrders)
            .catch(() => {});

        const showToast = (msg) => {
            const el = document.getElementById('toast');
            el.textContent = msg;
            el.style.display = 'block';
            setTimeout(() => el.style.display = 'none', 3000);
        };

        const openModal = (menuId, dishId) => {
            selected = { menuId, dishId };
            document.getElementById('modal-dish').textContent = `Menu #${menuId}, Plat #${dishId}`;
            document.getElementById('modal').style.display = 'flex';
        };

        const closeModal = () => {
            document.getElementById('modal').style.display = 'none';
        };

        const confirmReservation = async () => {
            const token = localStorage.getItem('ru_token');
            if (!token) {
                showToast('Token manquant. Va sur /admin et clique Sauver.');
                return;
            }
            const qty = parseInt(document.getElementById('modal-qty').value || '1', 10);
            try {
                const res = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        menu_id: selected.menuId,
                        items: [{ dish_id: selected.dishId, quantity: qty }]
                    })
                });
                if (!res.ok) {
                    const data = await res.json();
                    throw new Error(data.message || 'Erreur réservation');
                }
                closeModal();
                showToast('Réservation enregistrée');
                fetchOrders().then(renderOrders);
            } catch (e) {
                showToast(e.message);
            }
        };

        const payOrder = async (orderId) => {
            const token = localStorage.getItem('ru_token');
            if (!token) {
                showToast('Token manquant. Va sur /admin et clique Sauver.');
                return;
            }
            const res = await fetch(`/api/orders/${orderId}/pay`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}` }
            });
            if (!res.ok) {
                showToast('Paiement échoué');
                return;
            }
            showToast('Paiement effectué');
            fetchOrders().then(renderOrders);
        };

        const submitReview = async () => {
            const token = localStorage.getItem('ru_token');
            if (!token) {
                showToast('Token manquant. Va sur /admin et clique Sauver.');
                return;
            }
            const dishId = parseInt(document.getElementById('review-dish').value, 10);
            const rating = parseInt(document.getElementById('review-rating').value || '5', 10);
            const comment = document.getElementById('review-comment').value;
            const res = await fetch('/api/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ dish_id: dishId, rating, comment })
            });
            if (!res.ok) {
                showToast('Erreur avis');
                return;
            }
            showToast('Avis envoyé');
            document.getElementById('review-comment').value = '';
        };
    </script>
</body>
</html>


