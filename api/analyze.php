<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
function respond(int $status, array $payload): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(405, ['error' => 'Method not allowed. Use POST.']);
$question = trim((string)($_POST['question'] ?? ''));
if (mb_strlen($question) < 8) respond(400, ['error' => 'Please enter a research question with at least 8 characters.']);
$sources = json_decode((string)($_POST['sources'] ?? '[]'), true);
if (!is_array($sources)) respond(400, ['error' => 'The source list is invalid.']);
$validated = [];
foreach ($sources as $source) {
    if (!is_string($source) || !filter_var($source, FILTER_VALIDATE_URL)) continue;
    $scheme = strtolower((string)parse_url($source, PHP_URL_SCHEME));
    if (in_array($scheme, ['http', 'https'], true)) $validated[] = $source;
}
$uploaded = [];
if (isset($_FILES['documents']) && is_array($_FILES['documents']['name'] ?? null)) {
    $allowed = ['pdf', 'txt', 'doc', 'docx'];
    foreach ($_FILES['documents']['name'] as $i => $name) {
        if (($_FILES['documents']['error'][$i] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) continue;
        $ext = strtolower(pathinfo((string)$name, PATHINFO_EXTENSION));
        $size = (int)($_FILES['documents']['size'][$i] ?? 0);
        if (in_array($ext, $allowed, true) && $size <= 10 * 1024 * 1024) {
            $uploaded[] = ['name' => basename((string)$name), 'extension' => $ext, 'size_bytes' => $size];
        }
    }
}
respond(200, ['ok' => true, 'message' => 'Request received by PHP. Live extraction and AI analysis are not connected yet.', 'question' => $question, 'sources_received' => count($validated), 'sources' => $validated, 'documents_received' => $uploaded]);
?>