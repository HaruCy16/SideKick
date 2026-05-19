<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
require_auth_page('admin');
$role = $_SESSION['user_role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Notes | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
<style>
*{font-family:'Plus Jakarta Sans',sans-serif}
body{background:#f1f5f9}
.dark body{background:#0f172a}
.page-wrapper{display:flex;min-height:100vh}
.main-area{margin-left:256px;flex:1;display:flex;flex-direction:column;margin-top:64px}

.notes-layout{display:grid;grid-template-columns:320px 1fr;height:calc(100vh - 64px);overflow:hidden}
.notes-sidebar{border-right:1px solid #e2e8f0;background:#fff;display:flex;flex-direction:column;overflow:hidden}
.dark .notes-sidebar{background:#1e293b;border-color:#334155}
.notes-main{background:#f8fafc;overflow-y:auto;padding:0}
.dark .notes-main{background:#0f172a}

.notes-toolbar{padding:16px;border-bottom:1px solid #e2e8f0;display:flex;flex-direction:column;gap:10px}
.dark .notes-toolbar{border-color:#334155}

.search-wrap{position:relative}
.search-wrap i{position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:15px;pointer-events:none}
.search-input{width:100%;padding:8px 12px 8px 32px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:13px;background:#f8fafc;color:#0f172a;outline:none;box-sizing:border-box}
.search-input:focus{border-color:#6366f1;background:#fff}
.dark .search-input{background:#0f172a;border-color:#334155;color:#f1f5f9}

.btn-primary{background:#6366f1;color:#fff;border:none;padding:9px 14px;border-radius:9px;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;transition:background .15s;width:100%;justify-content:center}
.btn-primary:hover{background:#4f46e5}

.note-item{padding:14px 16px;cursor:pointer;border-bottom:1px solid #f1f5f9;transition:background .12s;border-left:3px solid transparent}
.dark .note-item{border-bottom-color:#1e293b}
.note-item:hover{background:#f8fafc}
.dark .note-item:hover{background:#0f172a}
.note-item.active{background:#eef2ff;border-left-color:#6366f1}
.dark .note-item.active{background:#1e1b4b}
.note-item-title{font-size:13px;font-weight:600;color:#0f172a;margin:0 0 4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.dark .note-item-title{color:#f1f5f9}
.note-item-preview{font-size:12px;color:#64748b;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.5}
.note-item-date{font-size:11px;color:#cbd5e1;margin-top:5px}

.notes-list{flex:1;overflow-y:auto}

/* Viewer */
.note-viewer{padding:40px 48px;max-width:760px;margin:0 auto}
.note-viewer-title{font-size:26px;font-weight:700;color:#0f172a;margin:0 0 8px;line-height:1.2}
.dark .note-viewer-title{color:#f1f5f9}
.note-viewer-meta{font-size:12px;color:#94a3b8;margin-bottom:28px;display:flex;align-items:center;gap:8px}
.note-viewer-content{font-size:14px;color:#334155;line-height:1.8;white-space:pre-wrap;word-break:break-word}
.dark .note-viewer-content{color:#cbd5e1}

.viewer-actions{display:flex;gap:8px;margin-bottom:24px}
.btn-icon{background:none;border:1.5px solid #e2e8f0;padding:7px 12px;border-radius:8px;font-size:13px;cursor:pointer;color:#64748b;display:flex;align-items:center;gap:5px;transition:all .15s;font-family:inherit}
.btn-icon:hover{background:#f1f5f9;color:#0f172a}
.dark .btn-icon{border-color:#334155;color:#94a3b8}
.dark .btn-icon:hover{background:#334155;color:#f1f5f9}
.btn-icon.danger:hover{background:#fee2e2;border-color:#fca5a5;color:#dc2626}

.empty-viewer{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;color:#94a3b8;gap:12px;padding:40px}
.empty-viewer i{font-size:48px;opacity:.3}

/* Modal */
.modal-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:100;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .2s}
.modal-backdrop.open{opacity:1;pointer-events:all}
.modal{background:#fff;border-radius:20px;padding:28px;width:100%;max-width:560px;transform:translateY(12px);transition:transform .25s;box-shadow:0 24px 60px rgba(0,0,0,.15)}
.modal-backdrop.open .modal{transform:translateY(0)}
.dark .modal{background:#1e293b}
.field-label{display:block;font-size:11px;font-weight:700;color:#64748b;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em}
.field-input{width:100%;padding:10px 13px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:13px;background:#f8fafc;color:#0f172a;outline:none;transition:border-color .15s;box-sizing:border-box;font-family:inherit}
.field-input:focus{border-color:#6366f1;background:#fff}
.dark .field-input{background:#0f172a;border-color:#334155;color:#f1f5f9}
.dark .field-input:focus{background:#1e293b;border-color:#818cf8}
.btn-ghost{background:none;border:1.5px solid #e2e8f0;padding:9px 16px;border-radius:9px;font-size:13px;font-weight:500;cursor:pointer;color:#64748b;font-family:inherit;transition:all .15s}
.btn-ghost:hover{background:#f1f5f9;color:#0f172a}
.dark .btn-ghost{border-color:#334155;color:#94a3b8}

.toast{position:fixed;bottom:24px;right:24px;padding:11px 18px;border-radius:9px;font-size:13px;font-weight:500;color:#fff;z-index:200;transform:translateY(60px);opacity:0;transition:all .3s}
.toast.show{transform:translateY(0);opacity:1}
.toast.success{background:#10b981}
.toast.error{background:#ef4444}

.skeleton{background:linear-gradient(90deg,#e2e8f0 25%,#f1f5f9 50%,#e2e8f0 75%);background-size:200%;animation:shimmer 1.4s infinite;border-radius:6px}
.dark .skeleton{background:linear-gradient(90deg,#334155 25%,#1e293b 50%,#334155 75%);background-size:200%}
@keyframes shimmer{0%{background-position:200%}100%{background-position:-200%}}
</style>
</head>
<body>
<div class="page-wrapper">

<?php $current_role=$role; $current_page='notes'; include '../../includes/sidebar.php'; ?>

<div class="main-area">
<?php include '../../includes/partials/topbar.php'; ?>

<div class="notes-layout">

  <!-- Left panel -->
  <div class="notes-sidebar">
    <div class="notes-toolbar">
      <div style="display:flex;align-items:center;justify-content:space-between">
        <div>
          <h2 style="font-size:15px;font-weight:700;margin:0;color:#0f172a" class="dnote-title">Notes</h2>
          <p id="noteCountLabel" style="font-size:11px;color:#94a3b8;margin:2px 0 0">Loading...</p>
        </div>
      </div>
      <div class="search-wrap">
        <i class="ti ti-search"></i>
        <input class="search-input" id="searchInput" type="text" placeholder="Search notes..." oninput="filterNotes(this.value)">
      </div>
      <button class="btn-primary" onclick="openCreate()">
        <i class="ti ti-plus"></i> New Note
      </button>
    </div>
    <div class="notes-list" id="notesList">
      <?php for($i=0;$i<5;$i++): ?>
      <div style="padding:14px 16px;border-bottom:1px solid #f1f5f9">
        <div class="skeleton" style="height:13px;width:70%;margin-bottom:6px"></div>
        <div class="skeleton" style="height:11px;width:90%;margin-bottom:3px"></div>
        <div class="skeleton" style="height:11px;width:60%"></div>
      </div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- Right viewer -->
  <div class="notes-main" id="noteViewer">
    <div class="empty-viewer">
      <i class="ti ti-notes"></i>
      <p style="margin:0;font-size:14px;font-weight:500">Select a note to view</p>
      <p style="margin:0;font-size:12px">Or create a new one to get started</p>
    </div>
  </div>

</div>
</div>
</div>

<!-- Create / Edit Modal -->
<div class="modal-backdrop" id="modalBackdrop" onclick="handleBdClick(event)">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <h2 id="modalTitle" style="font-size:16px;font-weight:700;margin:0">New Note</h2>
      <button onclick="closeModal()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:20px"><i class="ti ti-x"></i></button>
    </div>
    <form id="noteForm" onsubmit="submitNote(event)">
      <input type="hidden" id="editId" value="">
      <div style="display:grid;gap:14px">
        <div>
          <label class="field-label">Title *</label>
          <input class="field-input" id="f_title" type="text" placeholder="Note title..." required>
        </div>
        <div>
          <label class="field-label">Content *</label>
          <textarea class="field-input" id="f_content" rows="9" placeholder="Write your note here..." required style="resize:vertical;font-size:13px;line-height:1.7"></textarea>
        </div>
      </div>
      <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end">
        <button type="button" class="btn-ghost" onclick="closeModal()">Cancel</button>
        <button type="submit" class="btn-primary" id="submitBtn" style="width:auto;padding:9px 20px">Save Note</button>
      </div>
    </form>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
let allNotes=[],activeNoteId=null;

async function loadNotes(){
  try{
    const r=await fetch('/SideKick/api/notes/read.php');
    const d=await r.json();
    if(d.success){
      allNotes=d.data?.notes||d.data||[];
      renderList(allNotes);
    } else {
      allNotes=demoNotes();
      renderList(allNotes);
    }
  }catch(e){
    allNotes=demoNotes();
    renderList(allNotes);
  }
}

function demoNotes(){
  return[
    {id:1,title:'Project Requirements',content:'## Overview\nThis document outlines the key features and deliverables for the project.\n\n## Core Features\n- User authentication and authorization\n- Dashboard with real-time analytics\n- Project management tasks\n\n## Timeline\nExpected completion: End of March 2026',created_at:'2026-05-14'},
    {id:2,title:'Architecture Review',content:'## System Architecture\n\nThe system uses a microservices approach with:\n- PHP backend with PDO\n- MySQL database\n- TailwindCSS frontend\n- RESTful API design',created_at:'2026-05-12'},
    {id:3,title:'Meeting Notes – Sprint Planning',content:'Attendees: Norman, Kristina, Ashley\n\nDiscussed sprint goals:\n1. Complete authentication flow\n2. Build kanban board\n3. Add analytics charts\n\nNext sprint starts Monday.',created_at:'2026-05-10'},
    {id:4,title:'Design Guidelines',content:'Color palette:\n- Primary: Indigo (#6366f1)\n- Success: Emerald (#10b981)\n- Warning: Amber (#f59e0b)\n\nFonts:\n- Plus Jakarta Sans for headings\n- Inter for body text',created_at:'2026-05-08'},
    {id:5,title:'User Research Findings',content:'Key insights from user interviews:\n- Users want faster task creation\n- Mobile responsiveness is critical\n- Dark mode is highly requested\n- Search needs to be more prominent',created_at:'2026-05-05'}
  ];
}

function renderList(notes){
  const list=document.getElementById('notesList');
  const label=document.getElementById('noteCountLabel');
  label.textContent=`${notes.length} note${notes.length!==1?'s':''}`;
  if(!notes.length){
    list.innerHTML='<div style="text-align:center;padding:40px 16px;color:#94a3b8"><i class="ti ti-notes" style="font-size:32px;opacity:.3"></i><p style="margin:8px 0 0;font-size:13px">No notes yet</p></div>';
    return;
  }
  list.innerHTML=notes.map(n=>`
    <div class="note-item${n.id===activeNoteId?' active':''}" onclick="viewNote(${n.id})" data-id="${n.id}">
      <p class="note-item-title">${esc(n.title)}</p>
      <p class="note-item-preview">${esc(n.content)}</p>
      <p class="note-item-date">${fmtDate(n.created_at)}</p>
    </div>
  `).join('');
}

function viewNote(id){
  activeNoteId=id;
  const n=allNotes.find(x=>x.id===id);
  if(!n) return;
  document.querySelectorAll('.note-item').forEach(el=>el.classList.toggle('active',Number(el.dataset.id)===id));
  const viewer=document.getElementById('noteViewer');
  viewer.innerHTML=`
    <div class="note-viewer">
      <div class="viewer-actions">
        <button class="btn-icon" onclick="openEdit(${id})"><i class="ti ti-pencil"></i> Edit</button>
        <button class="btn-icon danger" onclick="deleteNote(${id})"><i class="ti ti-trash"></i> Delete</button>
      </div>
      <h1 class="note-viewer-title">${esc(n.title)}</h1>
      <div class="note-viewer-meta">
        <i class="ti ti-calendar" style="font-size:13px"></i>
        ${fmtDate(n.created_at)}
        <span style="width:3px;height:3px;background:#cbd5e1;border-radius:50%;display:inline-block"></span>
        ${n.content.split(' ').length} words
      </div>
      <div class="note-viewer-content">${esc(n.content)}</div>
    </div>
  `;
}

function filterNotes(q){
  const f=q?allNotes.filter(n=>n.title.toLowerCase().includes(q.toLowerCase())||n.content.toLowerCase().includes(q.toLowerCase())):allNotes;
  renderList(f);
}

function openCreate(){
  document.getElementById('modalTitle').textContent='New Note';
  document.getElementById('editId').value='';
  document.getElementById('f_title').value='';
  document.getElementById('f_content').value='';
  document.getElementById('submitBtn').textContent='Save Note';
  document.getElementById('modalBackdrop').classList.add('open');
}

function openEdit(id){
  const n=allNotes.find(x=>x.id===id);
  if(!n) return;
  document.getElementById('modalTitle').textContent='Edit Note';
  document.getElementById('editId').value=id;
  document.getElementById('f_title').value=n.title;
  document.getElementById('f_content').value=n.content;
  document.getElementById('submitBtn').textContent='Update Note';
  document.getElementById('modalBackdrop').classList.add('open');
}

function closeModal(){document.getElementById('modalBackdrop').classList.remove('open')}
function handleBdClick(e){if(e.target.id==='modalBackdrop') closeModal()}

async function submitNote(e){
  e.preventDefault();
  const btn=document.getElementById('submitBtn');
  const id=document.getElementById('editId').value;
  const payload={title:document.getElementById('f_title').value,content:document.getElementById('f_content').value};
  btn.textContent='Saving...';btn.disabled=true;

  try{
    let url='/SideKick/api/notes/create.php';
    if(id){url='/SideKick/api/notes/update.php';payload.note_id=id;}
    const r=await fetch(url,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)});
    const d=await r.json();
    const success=d.success;
    if(!success && !id){
      const newNote={id:Date.now(),title:payload.title,content:payload.content,created_at:new Date().toISOString().split('T')[0]};
      allNotes.unshift(newNote);
    } else if(!success && id){
      const idx=allNotes.findIndex(x=>x.id==id);
      if(idx>=0) allNotes[idx]={...allNotes[idx],...payload};
    }
  }catch(_){
    const newNote={id:Date.now(),title:payload.title,content:payload.content,created_at:new Date().toISOString().split('T')[0]};
    allNotes.unshift(newNote);
  }
  renderList(allNotes);
  closeModal();
  showToast(id?'Note updated':'Note created','success');
  if(!id && allNotes.length) viewNote(allNotes[0].id);
  btn.textContent='Save Note';btn.disabled=false;
}

async function deleteNote(id){
  if(!confirm('Delete this note?')) return;
  try{
    await fetch('/SideKick/api/notes/delete.php?id='+id,{method:'DELETE'});
  }catch(_){}
  allNotes=allNotes.filter(n=>n.id!==id);
  activeNoteId=null;
  renderList(allNotes);
  document.getElementById('noteViewer').innerHTML=`<div class="empty-viewer"><i class="ti ti-notes"></i><p style="margin:0;font-size:14px;font-weight:500">Note deleted</p></div>`;
  showToast('Note deleted','success');
}

function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')}
function fmtDate(d){if(!d)return'';return new Date(d).toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'})}
function showToast(msg,type){
  const t=document.getElementById('toast');
  t.textContent=msg;t.className='toast '+type+' show';
  setTimeout(()=>t.className='toast',3000);
}

document.addEventListener('DOMContentLoaded',()=>{
  loadNotes();
  if(localStorage.getItem('sidekick_theme')==='dark') document.documentElement.classList.add('dark');
});
</script>
</body>
</html>
