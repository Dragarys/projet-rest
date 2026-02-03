# Modele de donnees (ERD - texte)

## Entites
- users (id, role, name, email, password_hash, student_id, staff_id)
- categories (id, name)
- dishes (id, name, description, price)
- dish_categories (dish_id, category_id)
- ingredients (id, name, unit)
- dish_ingredients (dish_id, ingredient_id, qty)
- menus (id, menu_date, service)
- menu_items (menu_id, dish_id, quantity_limit, sold_count)
- orders (id, user_id, menu_id, status, total_amount, reserved_for)
- order_items (order_id, dish_id, quantity, unit_price)
- payments (id, order_id, status, method, amount)
- stock_movements (id, ingredient_id, delta_qty, reason)
- reviews (id, user_id, dish_id, menu_id, rating, comment)

## Relations
- users 1-n orders
- menus 1-n menu_items
- dishes n-n categories via dish_categories
- dishes n-n ingredients via dish_ingredients
- orders 1-n order_items
- orders 1-1 payments
- users 1-n reviews
- dishes 1-n reviews
- menus 1-n reviews
