# Digital Dark Spot
AI-Assisted Information Gap Detection Platform

## Structure
- index.html: frontend page
- assets/css/style.css: responsive UI styles
- assets/js/app.js: form and API interaction
- api/analyze.php: PHP request validation endpoint
- java/src/main/java/com/digitaldarkspot: Java domain model and analysis service scaffold

## Run
Requires PHP 8.1+:
```bash
php -S localhost:8000
```
Open http://localhost:8000.

Java scaffold requires Java 17+:
```bash
mkdir -p build
javac -d build java/src/main/java/com/digitaldarkspot/*.java
```

## Important status
This is a frontend + PHP request-validation starter. The Java service is not yet connected to PHP. It does not yet fetch live web pages, parse PDFs, or call an AI model. Implement a permitted source extractor, claim extraction with precise references, question coverage analysis, contradiction checks, date/cycle checks and evidence-backed checklist. Keep API keys on the server and label AI findings as potential signals.
