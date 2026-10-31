<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function respond(int $status, array $payload): never {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, ['error' => 'Method not allowed. Use POST.']);
}

$question = trim((string)($_POST['question'] ?? ''));
$questionLength = preg_match_all('/./us', $question, $unused);
if ($questionLength === false) {
    $questionLength = strlen($question);
}
if ($questionLength < 8) {
    respond(400, ['error' => 'Please enter a research question with at least 8 characters.']);
}

$sourceRaw = (string)($_POST['sources'] ?? '[]');
$sources = json_decode($sourceRaw, true);
if (!is_array($sources)) {
    respond(400, ['error' => 'The source list is not valid JSON.']);
}

$validatedSources = [];
foreach ($sources as $source) {
    if (!is_string($source) || !filter_var($source, FILTER_VALIDATE_URL)) {
        continue;
    }
    $scheme = strtolower((string)parse_url($source, PHP_URL_SCHEME));
    if (in_array($scheme, ['http', 'https'], true)) {
        $validatedSources[] = $source;
    }
}

$uploaded = [];
if (isset($_FILES['documents']) && is_array($_FILES['documents']['name'] ?? null)) {
    $allowed = ['pdf', 'txt', 'doc', 'docx'];
    foreach ($_FILES['documents']['name'] as $i => $originalName) {
        $error = $_FILES['documents']['error'][$i] ?? UPLOAD_ERR_NO_FILE;
        if ($error !== UPLOAD_ERR_OK) continue;
        $extension = strtolower(pathinfo((string)$originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed, true)) continue;
        $size = (int)($_FILES['documents']['size'][$i] ?? 0);
        if ($size > 10 * 1024 * 1024) continue;
        // This starter reports metadata only; it does not store or execute uploads.
        $uploaded[] = [
            'name' => basename((string)$originalName),
            'extension' => $extension,
            'size_bytes' => $size
        ];
    }
}

respond(200, [
    'ok' => true,
    'message' => 'Research request received by the PHP backend. Live extraction and AI gap analysis are not connected yet.',
    'question' => $question,
    'sources_received' => count($validatedSources),
    'sources' => $validatedSources,
    'documents_received' => $uploaded
]);
