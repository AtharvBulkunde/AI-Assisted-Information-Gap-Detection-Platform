const sources=[];const $=id=>document.getElementById(id);
function renderSources(){const list=$('sourceList');$('sourceCount').textContent=sources.length;if(!sources.length){list.innerHTML='<p class="empty">No sources added yet. Add at least one URL or upload source files.</p>';return;}list.innerHTML='';sources.forEach((url,i)=>{const row=document.createElement('div');row.className='source-item';const text=document.createElement('span');text.textContent=url;const remove=document.createElement('button');remove.type='button';remove.textContent='×';remove.setAttribute('aria-label','Remove source');remove.onclick=()=>{sources.splice(i,1);renderSources()};row.append(text,remove);list.append(row)})}
$('addSource').addEventListener('click',()=>{const input=$('sourceUrl');try{const u=new URL(input.value.trim());if(!['http:','https:'].includes(u.protocol))throw Error();if(!sources.includes(u.href))sources.push(u.href);input.value='';$('message').textContent='Source added.';renderSources()}catch{$('message').textContent='Enter a valid http or https URL.'}});
$('documents').addEventListener('change',()=>{$('fileCount').textContent=$('documents').files.length});
$('researchForm').addEventListener('submit',async e=>{e.preventDefault();$('message').textContent='Submitting research request…';const data=new FormData();data.append('question',$('question').value);data.append('sources',JSON.stringify(sources));for(const f of $('documents').files)data.append('documents[]',f);
try{
 const res=await fetch('api/analyze.php',{method:'POST',body:data,headers:{'Accept':'application/json'}});
 const raw=await res.text();let json;
 try{json=JSON.parse(raw)}catch(parseError){
  const preview=raw.trim().slice(0,180);
  if(!res.ok)throw Error(`PHP server returned HTTP ${res.status}. ${preview||'The response was empty. Check PHP errors and confirm you started the server in the project root.'}`);
  throw Error(`The PHP endpoint did not return JSON. Response: ${preview||'(empty response)'}. Open the site at http://localhost:8000, not through VS Code Live Server.`);
 }
 if(!res.ok)throw Error(json.error||`Request failed (HTTP ${res.status})`);
 $('found').textContent=json.message||'Request received.';$('gaps').textContent='Live gap detection requires source extraction and AI integration.';$('conflicts').textContent='Source comparison will appear when the analysis engine is connected.';$('checklist').innerHTML='<li>Review each original source.</li><li>Confirm current official requirements.</li><li>Resolve missing details and conflicts.</li>';document.getElementById('results').scrollIntoView({behavior:'smooth'});$('message').textContent='Request completed.';
}catch(err){$('message').textContent=err.message+' — Start the PHP server from the project root.'}});
renderSources();
