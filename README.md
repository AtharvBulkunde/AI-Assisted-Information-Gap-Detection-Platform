# Digital Dark Spot — Gemini AI starter
## Setup
Use PHP 8.1+ with cURL enabled. Revoke any Gemini key previously exposed in a chat or public repository and create a replacement in Google AI Studio.
Windows PowerShell (same terminal as PHP server):
```powershell
$env:GEMINI_API_KEY="YOUR_NEW_GEMINI_API_KEY"
php -S localhost:8000
```
Open http://localhost:8000. Linux/macOS: `export GEMINI_API_KEY="YOUR_NEW_GEMINI_API_KEY"` before starting PHP.
Never put the key in HTML, JavaScript or GitHub. This code reads the environment variable directly.
## Features
Responsive research workspace, PHP API, and Gemini chat at api/chat.php with short conversation history.
## Limitations
Chat answers general questions. Research form does not yet fetch and parse URLs or uploaded files, so it cannot claim to have reviewed source contents. Build a source extraction, PDF parsing, evidence and citation pipeline for full gap detection.
## Troubleshooting
Missing key: set GEMINI_API_KEY before PHP starts. cURL error: enable PHP cURL. 401/403: check key access. 429: quota/rate limit. Use PHP server, not Live Server.
