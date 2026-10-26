<?php
/** PDO database connection (new site DB). */

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $port = getenv('DB_PORT') ?: (defined('DB_PORT') ? DB_PORT : '3306');
        $dsn  = 'mysql:host=' . DB_HOST . ';port=' . $port . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        // --- TLS for cloud MySQL (Aiven / TiDB / etc. require an encrypted link) ---
        // Enabled automatically if a CA file (app/ca.pem) is present or DB_SSL=1 is set.
        $ca = getenv('DB_SSL_CA') ?: (defined('DB_SSL_CA') && DB_SSL_CA ? DB_SSL_CA : '');
        if ($ca === '' && is_file(__DIR__ . '/ca.pem')) $ca = __DIR__ . '/ca.pem';
        $sslOn = $ca !== '' || getenv('DB_SSL') === '1' || (defined('DB_SSL') && DB_SSL);
        if ($ca !== '')  $opts[PDO::MYSQL_ATTR_SSL_CA] = $ca;
        if ($sslOn)      $opts[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    }
    return $pdo;
}

/** Fetch one row. */
function db_one(string $sql, array $args = []): ?array
{
    $st = db()->prepare($sql);
    $st->execute($args);
    $row = $st->fetch();
    return $row === false ? null : $row;
}

/** Fetch all rows. */
function db_all(string $sql, array $args = []): array
{
    $st = db()->prepare($sql);
    $st->execute($args);
    return $st->fetchAll();
}

/** Run a statement, return last insert id. */
function db_run(string $sql, array $args = []): string
{
    $st = db()->prepare($sql);
    $st->execute($args);
    return db()->lastInsertId();
}
