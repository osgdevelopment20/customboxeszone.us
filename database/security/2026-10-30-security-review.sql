-- =====================================================================
-- customboxeszone.com - security clean-up after the 2026-10-12 incident
-- Run in cPanel > phpMyAdmin > (your database) > SQL tab.
--
-- Run ONE step at a time (copy the step, paste, Go). Steps 1 and 4 only
-- read data. Steps 2-3 change data. Step 5 is commented out: edit it
-- after reviewing step 1, then remove the leading "-- " and run it.
-- Take a phpMyAdmin Export of the database before step 2.
-- =====================================================================


-- ---------------------------------------------------------------------
-- STEP 1 (read-only): every account, newest first, with roles and
-- permissions. Accounts the new code will refuse have no roles and no
-- permissions. Look hard at IDs 4-8 (active on 2026-10-12) and anything
-- created on or after 2026-10-12 that you do not recognise.
-- ---------------------------------------------------------------------
SELECT u.id, u.name, u.email, u.created_at,
       GROUP_CONCAT(DISTINCT r.name ORDER BY r.name) AS roles,
       GROUP_CONCAT(DISTINCT p.name ORDER BY p.name) AS direct_permissions
FROM users u
LEFT JOIN model_has_roles mr       ON mr.model_id = u.id AND mr.model_type = 'App\\Models\\User'
LEFT JOIN roles r                  ON r.id = mr.role_id
LEFT JOIN model_has_permissions mp ON mp.model_id = u.id AND mp.model_type = 'App\\Models\\User'
LEFT JOIN permissions p            ON p.id = mp.permission_id
GROUP BY u.id
ORDER BY u.id DESC;

-- Roles and what each one grants (the old sign-up accounts could edit these).
SELECT r.id, r.name, r.created_at, r.updated_at,
       GROUP_CONCAT(p.name ORDER BY p.name) AS permissions
FROM roles r
LEFT JOIN role_has_permissions rp ON rp.role_id = r.id
LEFT JOIN permissions p           ON p.id = rp.permission_id
GROUP BY r.id
ORDER BY r.id;


-- ---------------------------------------------------------------------
-- STEP 2: make sure the permission names the dashboard now enforces exist.
-- Safe to run more than once (existing rows are skipped).
-- ---------------------------------------------------------------------
INSERT IGNORE INTO permissions (name, guard_name, created_at, updated_at) VALUES
('view-dashboard', 'web', NOW(), NOW()),
('manage-users', 'web', NOW(), NOW()),
('manage-roles', 'web', NOW(), NOW()),
('manage-permissions', 'web', NOW(), NOW()),
('manage-marketing', 'web', NOW(), NOW()),
('manage-products', 'web', NOW(), NOW()),
('manage-categories', 'web', NOW(), NOW()),
('manage-banners', 'web', NOW(), NOW()),
('manage-blogs', 'web', NOW(), NOW()),
('manage-queries', 'web', NOW(), NOW()),
('manage-orders', 'web', NOW(), NOW()),
('manage-settings', 'web', NOW(), NOW()),
('manage-reviews', 'web', NOW(), NOW()),
('generate-sitemap', 'web', NOW(), NOW()),
('clear-cache', 'web', NOW(), NOW()),
('pull-code', 'web', NOW(), NOW());


-- ---------------------------------------------------------------------
-- STEP 3 (optional): your Super Admin role already has every permission.
-- Only needed if you ever get "Access denied": gives one account every
-- dashboard permission directly. Put your login email on the first line.
-- Other staff: assign roles/permissions afterwards from Dashboard > Users.
-- ---------------------------------------------------------------------
SET @admin_email = 'PUT-YOUR-ADMIN-LOGIN-EMAIL-HERE';

INSERT IGNORE INTO model_has_permissions (permission_id, model_type, model_id)
SELECT p.id, 'App\\Models\\User', u.id
FROM permissions p
JOIN users u ON u.email = @admin_email
WHERE p.guard_name = 'web'
  AND p.name IN ('view-dashboard', 'manage-users', 'manage-roles', 'manage-permissions', 'manage-marketing', 'manage-products', 'manage-categories', 'manage-banners', 'manage-blogs', 'manage-queries', 'manage-orders', 'manage-settings', 'manage-reviews', 'generate-sitemap', 'clear-cache', 'pull-code');

-- Should list 16 rows. 0 rows = the email above does not match an account.
SELECT p.name FROM model_has_permissions mp
JOIN permissions p ON p.id = mp.permission_id
JOIN users u ON u.id = mp.model_id AND mp.model_type = 'App\\Models\\User'
WHERE u.email = @admin_email;


-- ---------------------------------------------------------------------
-- STEP 4 (read-only): look for injected script/iframes in content the
-- sign-up accounts could edit. Any row returned needs a manual look:
-- legitimate content (e.g. a chat widget or analytics tag you added
-- yourself) is fine; anything you do not recognise should be removed
-- in the dashboard.
-- ---------------------------------------------------------------------
SELECT 'settings' AS source, id, `key` AS item, LEFT(value, 300) AS snippet
FROM settings
WHERE value REGEXP '<script|<iframe|javascript:|onerror=|onload=|eval\\(|atob\\(|document\\.cookie'
UNION ALL
SELECT 'blogs', id, title, LEFT(blog_ldis, 300)
FROM blogs
WHERE blog_ldis REGEXP '<script|<iframe|javascript:|onerror=|onload=|document\\.cookie'
UNION ALL
SELECT 'categories', id, name, LEFT(CONCAT_WS(' ', description, detail), 300)
FROM categories
WHERE CONCAT_WS(' ', description, detail, faqs) REGEXP '<script|<iframe|javascript:|onerror=|onload=|document\\.cookie'
UNION ALL
SELECT 'products', id, product_name, LEFT(CONCAT_WS(' ', product_description, product_detail), 300)
FROM products
WHERE CONCAT_WS(' ', product_description, product_detail, faqs) REGEXP '<script|<iframe|javascript:|onerror=|onload=|document\\.cookie';

-- Settings and blogs changed on or after the incident day.
SELECT 'settings' AS source, id, `key` AS item, updated_at FROM settings WHERE updated_at >= '2026-10-12'
UNION ALL
SELECT 'blogs', id, title, updated_at FROM blogs WHERE updated_at >= '2026-10-12'
ORDER BY updated_at;


-- ---------------------------------------------------------------------
-- STEP 5: the fake accounts (IDs 4-9) and everything they created are
-- removed by the separate script 2026-10-30-remove-attack-data.sql.
-- ---------------------------------------------------------------------


-- ---------------------------------------------------------------------
-- AFTER STEPS 2-5: permissions are cached for 24 hours. In cPanel File
-- Manager, delete everything inside  storage/framework/cache/data/
-- (delete the contents, keep the folder) so the changes apply now.
-- ---------------------------------------------------------------------
