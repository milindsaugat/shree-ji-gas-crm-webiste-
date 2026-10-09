---
name: database-design-pdo
description: >-
  Use this skill when designing database schemas, writing PDO prepared statements, managing transactions,
  optimizing SQL queries, adding indexes, and creating migration scripts for SQLite or MySQL.
---

# Database Design & PDO Skill

This skill governs data schema design, secure query execution, and high-performance data access.

## Database Standards

1. **Relational Schema Hygiene**:
   - Primary keys: Integer `AUTO_INCREMENT` / `AUTOINCREMENT` or consistent UUIDs.
   - Foreign keys: Explicit constraints with cascading rules where appropriate (`ON DELETE CASCADE` or `RESTRICT`).
   - Appropriate column types: `VARCHAR(255)` for names/slugs, `TEXT` for rich content, `DATETIME`/`TIMESTAMP` for tracking, `TINYINT(1)` for booleans.
   - Unique constraints on slugs, emails, and identifiers to prevent duplicates at the database level.

2. **PDO Prepared Statements (100% Parameterized)**:
   - NEVER interpolate variables directly into SQL queries (`SELECT ... WHERE id = $id` is strictly forbidden).
   - Always use prepared statements with named or positional parameters:
     ```php
     $stmt = $pdo->prepare('SELECT * FROM products WHERE slug = ? AND is_active = 1 LIMIT 1');
     $stmt->execute([$slug]);
     $product = $stmt->fetch();
     ```
   - Always configure PDO error mode to exceptions: `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.

3. **Performance & Indexing**:
   - Create single or compound indexes on all columns used in `WHERE`, `ORDER BY`, and `JOIN` clauses (e.g. `is_active`, `category_id`, `sort_order`, `slug`).
   - Avoid `SELECT *` in high-throughput listing queries; select only required columns.
   - Use transactions (`$pdo->beginTransaction()`, `$pdo->commit()`, `$pdo->rollBack()`) for multi-step updates to prevent partial state corruption.

4. **Migrations & Seeders**:
   - Maintain reproducible SQL schema files (`schema.sql`) and idempotent seed scripts (`database/seed.php`).
