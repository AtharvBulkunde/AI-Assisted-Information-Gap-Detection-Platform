<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
function jsonResponse(int $status,array $data): void { http_response_code($status); $out=json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); echo $out!==false?$out:'{"error":"Could not encode JSON response."}'; exit; }
if($_SERVER['REQUEST_METHOD']!=='POST') jsonResponse(405,['error'=>'Use POST.']);
$key=getenv('GEMINI_API_KEY'); if(!$key) jsonResponse(503,['error'=>'Gemini API key is not configured. Set GEMINI_API_KEY before starting PHP.']);
$input=json_decode(file_get_contents('php://input'),true); if(!is_array($input)) jsonResponse(400,['error'=>'Invalid JSON request.']);
$message=trim((string)($input['message']??'')); if($message===''||strlen($message)>12000) jsonResponse(400,['error'=>'Enter a message (maximum 12,000 characters).']);
$history=$input['history']??[]; if(!is_array($history))$history=[]; $contents=[];
foreach(array_slice($history,-12) as $turn){if(!is_array($turn))continue;$role=($turn['role']??'')==='model'?'model':'user';$t=trim((string)($turn['text']??''));if($t!==''&&strlen($t)<=12000)$contents[]=['role'=>$role,'parts'=>[['text'=>$t]]];}
$contents[]=['role'=>'user','parts'=>[['text'=>$message]]];
$payload=['systemInstruction'=>['parts'=>[['text'=>'You are Digital Dark Spot, a helpful research and learning assistant. Answer in a structured way with headings, short paragraphs, numbered steps and examples. Be honest about uncertainty. Never invent citations, URLs or claim to have inspected sources unless their content was provided. A URL alone does not mean you read the page. Distinguish facts from suggestions. For time-sensitive or high-stakes topics, advise checking current authoritative sources.']]],'contents'=>$contents,'generationConfig'=>['temperature'=>0.4,'maxOutputTokens'=>1800]];
$ch=curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent');curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_TIMEOUT=>45,CURLOPT_HTTPHEADER=>['Content-Type: application/json','x-goog-api-key: '.$key],CURLOPT_POSTFIELDS=>json_encode($payload)]);
$response=curl_exec($ch);$err=curl_error($ch);$status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);if($response===false)jsonResponse(502,['error'=>'Gemini connection failed: '.$err]);$data=json_decode($response,true);
if($status<200||$status>=300)jsonResponse($status===429?429:502,['error'=>$data['error']['message']??'Gemini request failed.']);
$answer='';foreach(($data['candidates'][0]['content']['parts']??[]) as $part)if(isset($part['text']))$answer.=$part['text'];if($answer==='')jsonResponse(502,['error'=>'Gemini returned no text.']);
jsonResponse(200,['ok'=>true,'answer'=>$answer,'model'=>'gemini-2.5-flash']);
