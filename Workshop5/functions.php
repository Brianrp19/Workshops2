<?php
/**
 * functions.php
 * Taller Semana 5 - Aventones / Workshop5
 *
 * Ajusta las credenciales según tu entorno.
 * En XAMPP por defecto: root + contraseña vacía.
 */

declare(strict_types=1);

/* =========================
 *  CONFIGURACIÓN DB
 * ========================= */
const DB_HOST = 'localhost';
const DB_USER = 'root';     // o 'app_user' si creaste uno
const DB_PASS = '';         // en XAMPP por defecto: '' (vacío). Si le pusiste: 'root1234'
const DB_NAME = 'php_web2'; // cambia si usas otra BD

/* =========================
 *  CONEXIÓN MYSQL
 * ========================= */
function getConnection(): mysqli {
  // Lanza excepciones ante errores de MySQLi
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

  $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  // Si tu MySQL corre en otro puerto, usa: new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, 3306);
  $conn->set_charset('utf8mb4');
  return $conn;
}

/**
 * Autenticación de usuario
 * - Solo permite usuarios con status = 'active'
 * - Compara contra MD5 (igual que tu base actual). Idealmente migra a password_hash() en el futuro.
 * - Actualiza last_login_datetime = NOW() en login exitoso
 *
 * @return array|null  Devuelve arreglo con datos del usuario o null si falla
 */
function authenticate(string $username, string $password): ?array {
  $conn = getConnection();

  // Busca usuario por username
  $sql = "SELECT id, username, password, name, lastname, role, status, last_login_datetime
            FROM users
           WHERE username = ?
           LIMIT 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $user = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  if (!$user) {
    $conn->close();
    return null; // usuario no existe
  }

  // Debe estar activo
  if ($user['status'] !== 'active') {
    $conn->close();
    return null; // inactivo
  }

  // Comparar contraseña (MD5 según tu base actual)
  if (hash('md5', $password) !== $user['password']) {
    $conn->close();
    return null; // clave incorrecta
  }

  // Login OK: actualiza last_login_datetime
  $upd = $conn->prepare("UPDATE users SET last_login_datetime = NOW() WHERE id = ?");
  $upd->bind_param('i', $user['id']);
  $upd->execute();
  $upd->close();

  $conn->close();
  return $user;
}

/**
 * Crea un usuario (opcional, útil para pruebas)
 * Guarda contraseña con MD5 (para ser compatible con authenticate actual).
 * Cambia a password_hash() cuando migres.
 */
function createUser(array $data): int {
  $conn = getConnection();
  $sql = "INSERT INTO users (username, password, name, lastname, role, status)
          VALUES (?, MD5(?), ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);

  // Valores por defecto
  $username = $data['username'] ?? '';
  $password = $data['password'] ?? '';
  $name     = $data['name']     ?? '';
  $lastname = $data['lastname'] ?? '';
  $role     = $data['role']     ?? 'Usuario';   // 'Administrador' | 'Usuario'
  $status   = $data['status']   ?? 'active';    // 'active' | 'inactive'

  $stmt->bind_param('ssssss', $username, $password, $name, $lastname, $role, $status);
  $stmt->execute();
  $newId = $stmt->insert_id;
  $stmt->close();
  $conn->close();
  return $newId; // id del nuevo usuario
}

/* =========================
 *  MÓDULO STUDENTS (demo)
 * ========================= */

/**
 * Inserta un estudiante (usa prepared statements)
 */
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

/**
 * Lista todos los estudiantes
 * @return array lista de filas asociativas
 */
function getStudents(): array {
  $conn = getConnection();
  $sql = "SELECT id, full_name, email, document, created_at FROM students ORDER BY id DESC";
  $res = $conn->query($sql);
  $rows = $res->fetch_all(MYSQLI_ASSOC);
  $conn->close();
  return $rows;
}

/**
 * Elimina estudiante por id
 */
function deleteStudent(int $id): bool {
  $conn = getConnection();
  $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
  $stmt->bind_param('i', $id);
  $ok = $stmt->execute();
  $stmt->close();
  $conn->close();
  return $ok;
}

/* =========================
 *  EMAIL (placeholder)
 * ========================= */
/**
 * Ejemplo de wrapper para algún envío por CLI (personalízalo).
 * Por ahora solo deja un “hook” si te sirve más adelante.
 */
function sendScheduleEmail(string $recipient, string $subject): void {
  // Ejemplo (deshabilitado):
  // $output = $retval = null;
  // exec("C:\\ruta\\a\\php.exe C:\\ruta\\a\\tu_script_envio.php " . escapeshellarg($recipient) . " " . escapeshellarg($subject), $output, $retval);
}

