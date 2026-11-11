-- =====================================================================
-- customboxeszone.com - remove the records created during the attack
-- (2026-10-13 03:00-04:30 server time, by sign-up accounts 4-9).
--
-- Run in phpMyAdmin > SQL tab as ONE block (it is a single transaction).
-- Export the database first (phpMyAdmin > Export > Quick > Go).
--
-- Every DELETE names the exact IDs AND re-checks the fake name / email /
-- time, so a real record can never match even if an ID were different.
-- Running it twice is harmless (second run deletes nothing).
-- =====================================================================

START TRANSACTION;

-- Uploaded files (19 media rows; several are .php files) on the fake records.
DELETE FROM media
WHERE id IN (21101, 21102, 21103, 21104, 21107, 21108, 21109, 21110, 21111, 21112,
             21113, 21114, 21115, 21116, 21117, 21118, 21119, 21120, 21121)
  AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00';

-- Fake products "probe-php-8891", "probe-base-8891", "probe-a1-8891", "probe-a2-8891".
DELETE FROM category_product
WHERE product_id IN (2577, 2578, 2579, 2580)
  AND product_id IN (SELECT id FROM (SELECT id FROM products WHERE slug LIKE 'probe-%-8891') AS fake);
DELETE FROM products
WHERE id IN (2577, 2578, 2579, 2580) AND slug LIKE 'probe-%-8891';

-- Fake blogs "QA Blog 7e95e", "QAPNG 64551", "QAJPG ef83b".
DELETE FROM blogs
WHERE id IN (230, 231, 232) AND slug IN ('qa-blog-7e95e', 'qapng-64551', 'qajpg-ef83b');

-- Fake category "QACAT dc789".
DELETE FROM categories
WHERE id = 180 AND slug = 'qacat-dc789';

-- Test quote submissions ("QA Probe", "Dave Parker / Test Product", "zzqa-probe").
DELETE FROM quotes
WHERE id BETWEEN 945 AND 950
  AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00'
  AND (email LIKE '%@mailinator.com' OR email LIKE '%@example.com');

-- The sign-up accounts themselves (they never had roles or permissions).
DELETE FROM password_resets
WHERE email IN (SELECT email FROM (
    SELECT email FROM users
    WHERE id BETWEEN 4 AND 9
      AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00'
      AND (email LIKE '%@mailinator.com' OR email LIKE '%@example.com')) AS fake);
DELETE FROM model_has_roles
WHERE model_type = 'App\\Models\\User' AND model_id IN (SELECT id FROM (
    SELECT id FROM users WHERE id BETWEEN 4 AND 9
      AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00'
      AND (email LIKE '%@mailinator.com' OR email LIKE '%@example.com')) AS fake);
DELETE FROM model_has_permissions
WHERE model_type = 'App\\Models\\User' AND model_id IN (SELECT id FROM (
    SELECT id FROM users WHERE id BETWEEN 4 AND 9
      AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00'
      AND (email LIKE '%@mailinator.com' OR email LIKE '%@example.com')) AS fake);
DELETE FROM users
WHERE id BETWEEN 4 AND 9
  AND created_at BETWEEN '2026-10-13 03:00:00' AND '2026-10-13 04:30:00'
  AND (email LIKE '%@mailinator.com' OR email LIKE '%@example.com');

COMMIT;

-- Check: every count below should be 0.
SELECT
  (SELECT COUNT(*) FROM users      WHERE id BETWEEN 4 AND 9)               AS fake_users,
  (SELECT COUNT(*) FROM products   WHERE slug LIKE 'probe-%-8891')         AS fake_products,
  (SELECT COUNT(*) FROM blogs      WHERE id IN (230, 231, 232))            AS fake_blogs,
  (SELECT COUNT(*) FROM categories WHERE slug = 'qacat-dc789')             AS fake_category,
  (SELECT COUNT(*) FROM quotes     WHERE id BETWEEN 945 AND 950)           AS fake_quotes,
  (SELECT COUNT(*) FROM media      WHERE id BETWEEN 21101 AND 21121)       AS fake_media;
