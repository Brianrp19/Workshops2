<?php
declare(strict_types=1);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'php_web2';

function getConnection(): mysqli {
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  $conn->set_charset('utf8mb4');
  return $conn;
}

/**
 * Retorna array de usuario si login ok, o null si falla.
 * Requisitos:
 *  - Debe existir el username
 *  - Debe estar en status 'active'
 *  - MD5($password) debe coincidir
 *  - Si todo ok: actualiza last_login_datetime = NOW()
 */
function authenticate(string $username, string $password): ?array {
  $conn = getConnection();

  $sql = "SELECT id, username, password, name, lastname, role, status, last_login_datetime
            FROM users
           WHERE username = ?
           LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$user) { $conn->close(); return null; }

  if ($user['status'] !== 'active') { $conn->close(); return null; }

  if (hash('md5', $password) !== $user['password']) {
    $conn->close();
    return null;
  }

  // Actualizar último login
  $upd = $conn->prepare("UPDATE users SET last_login_datetime = NOW() WHERE id = ?");
  $upd->bind_param('i', $user['id']);
  $upd->execute();
  $upd->close();

  $conn->close();
  return $user;
}

function createUser(array $data): int {
  $conn = getConnection();
  $sql = "INSERT INTO users (username, password, name, lastname, role, status)
          VALUES (?, MD5(?), ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  $username = $data['username'] ?? '';
  $password = $data['password'] ?? '';
  $name     = $data['name']     ?? '';
  $lastname = $data['lastname'] ?? '';
  $role     = $data['role']     ?? 'Usuario';
  $status   = $data['status']   ?? 'active';

  $stmt->bind_param('ssssss', $username, $password, $name, $lastname, $role, $status);
  $stmt->execute();
  $newId = $stmt->insert_id;

  $stmt->close();
  $conn->close();
  return $newId;
}

function saveStudent(array $student): bool {
  $conn = getConnection();
  $sql = "INSERT INTO students (full_name, email, document) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);

  $fullName = $student['full_name'] ?? '';
  $email    = $student['email']     ?? '';
  $doc      = $student['document']  ?? '';

  $stmt->bind_param('sss', $fullName, $email, $doc);
  $ok = $stmt->execute();

  $stmt->close();
  $conn->close();
  return $ok;
}

function getStudents(): array {
  $conn = getConnection();
  $res  = $conn->query("SELECT id, full_name, email, document, created_at FROM students ORDER BY id DESC");
  $rows = $res->fetch_all(MYSQLI_ASSOC);
  $conn->close();
  return $rows;
}

function deleteStudent(int $id): bool {
  $conn = getConnection();
  $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
  $stmt->bind_param('i', $id);
  $ok = $stmt->execute();
  $stmt->close();
  $conn->close();
  return $ok;
}

// Stub, por si luego lo usas:
function sendScheduleEmail(string $recipient, string $subject): void {}
