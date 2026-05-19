<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
require_auth_page('manager');
$role = $_SESSION['user_role'] ?? 'manager';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Projects | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900">
<div class="flex min-h-screen">

<?php $current_role=$role; $current_page='projects'; require_once '../../includes/sidebar.php'; ?>

<div class="flex-1 flex flex-col pt-16 md:ml-64">
<?php require_once '../../includes/partials/topbar.php'; ?>

<main class="flex-1 overflow-y-auto p-8">
  <div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Projects</h1>
      <p class="text-slate-600 dark:text-slate-400">Manage and track all your projects</p>
    </div>

    <!-- Toolbar -->
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
      <div class="flex-1 relative">
        <i class="ti ti-search absolute left-3 top-3 text-slate-400 text-lg"></i>
        <input type="text" id="searchInput" placeholder="Search projects..." 
          class="w-full pl-10 pr-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500"
          oninput="filterProjects(this.value)">
      </div>
      <button onclick="openCreateModal()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium flex items-center gap-2 transition">
        <i class="ti ti-plus"></i> New Project
      </button>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="projectsList">
      <?php for($i=0; $i<6; $i++): ?>
      <div class="h-48 bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-800 rounded-xl animate-pulse"></div>
      <?php endfor; ?>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden text-center py-16">
      <i class="ti ti-briefcase text-6xl text-slate-300 dark:text-slate-600 mx-auto mb-4"></i>
      <p class="text-slate-500 dark:text-slate-400 text-lg">No projects found</p>
    </div>
  </div>
</main>
</div>
</div>

<!-- Create/Edit Modal -->
<div id="projectModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
  <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 w-full max-w-lg m-4">
    <div class="flex justify-between items-center mb-6">
      <h2 id="modalTitle" class="text-2xl font-bold text-slate-900 dark:text-white">New Project</h2>
      <button onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
        <i class="ti ti-x text-2xl"></i>
      </button>
    </div>

    <form id="projectForm" onsubmit="submitProject(event)">
      <input type="hidden" id="editId" value="">
      
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Project Name *</label>
          <input type="text" id="projectName" required placeholder="Enter project name"
            class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Description</label>
          <textarea id="projectDesc" rows="4" placeholder="Describe your project..."
            class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500 resize-none"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
            <select id="projectStatus" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="on_hold">On Hold</option>
              <option value="completed">Completed</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Priority</label>
            <select id="projectPriority" class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
              <option value="low">Low</option>
              <option value="medium" selected>Medium</option>
              <option value="high">High</option>
            </select>
          </div>
        </div>
      </div>

      <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeCreateModal()" class="flex-1 px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
          Cancel
        </button>
        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
          Save Project
        </button>
      </div>
    </form>
  </div>
</div>

<script>
let allProjects = [];

async function loadProjects() {
  try {
    const r = await fetch('/SideKick/api/projects/read.php');
    const d = await r.json();
    if (d.success) {
      allProjects = d.data || [];
    } else {
      allProjects = getDemoProjects();
    }
  } catch (e) {
    allProjects = getDemoProjects();
  }
  renderProjects(allProjects);
}

function getDemoProjects() {
  return [
    {id: 1, name: 'Company Website Redesign', description: 'Modern redesign of the main website', status: 'active', priority: 'high', progress: 65, color: '#8B5CF6'},
    {id: 2, name: 'Mobile App UI Design', description: 'Design new mobile interface', status: 'active', priority: 'high', progress: 45, color: '#14B8A6'},
    {id: 3, name: 'E-commerce Platform Setup', description: 'Build e-commerce infrastructure', status: 'pending', priority: 'medium', progress: 20, color: '#EC4899'},
    {id: 4, name: 'API Documentation', description: 'Complete API reference docs', status: 'in_progress', priority: 'medium', progress: 80, color: '#F59E0B'},
  ];
}

function renderProjects(projects) {
  const list = document.getElementById('projectsList');
  const empty = document.getElementById('emptyState');

  if (!projects.length) {
    list.innerHTML = '';
    empty.classList.remove('hidden');
    return;
  }

  empty.classList.add('hidden');
  list.innerHTML = projects.map(p => `
    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700 hover:shadow-lg transition cursor-pointer" onclick="editProject(${p.id})">
      <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
          <h3 class="text-lg font-bold text-slate-900 dark:text-white">${esc(p.name)}</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">${esc(p.description || '')}</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusBadge(p.status)}">
          ${p.status.replace('_', ' ')}
        </span>
      </div>

      <div class="mb-4">
        <div class="flex justify-between text-xs text-slate-600 dark:text-slate-400 mb-1">
          <span>Progress</span>
          <span>${p.progress || 0}%</span>
        </div>
        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
          <div class="bg-indigo-600 h-2 rounded-full" style="width: ${p.progress || 0}%"></div>
        </div>
      </div>

      <div class="flex justify-between items-center">
        <span class="inline-block w-3 h-3 rounded-full" style="background: ${p.color}"></span>
        <button onclick="deleteProject(event, ${p.id})" class="text-red-500 hover:text-red-600 text-sm font-medium">
          Delete
        </button>
      </div>
    </div>
  `).join('');
}

function getStatusBadge(status) {
  const badges = {
    active: 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
    pending: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400',
    on_hold: 'bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-400',
    completed: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
  };
  return badges[status] || 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300';
}

function filterProjects(q) {
  const filtered = q ? allProjects.filter(p => 
    p.name.toLowerCase().includes(q.toLowerCase()) || 
    p.description.toLowerCase().includes(q.toLowerCase())
  ) : allProjects;
  renderProjects(filtered);
}

function openCreateModal() {
  document.getElementById('modalTitle').textContent = 'New Project';
  document.getElementById('editId').value = '';
  document.getElementById('projectForm').reset();
  document.getElementById('projectModal').classList.remove('hidden');
}

function editProject(id) {
  const p = allProjects.find(x => x.id === id);
  if (!p) return;
  document.getElementById('modalTitle').textContent = 'Edit Project';
  document.getElementById('editId').value = id;
  document.getElementById('projectName').value = p.name;
  document.getElementById('projectDesc').value = p.description || '';
  document.getElementById('projectStatus').value = p.status;
  document.getElementById('projectPriority').value = p.priority || 'medium';
  document.getElementById('projectModal').classList.remove('hidden');
}

function closeCreateModal() {
  document.getElementById('projectModal').classList.add('hidden');
}

async function submitProject(e) {
  e.preventDefault();
  const id = document.getElementById('editId').value;
  const payload = {
    name: document.getElementById('projectName').value,
    description: document.getElementById('projectDesc').value,
    status: document.getElementById('projectStatus').value,
    priority: document.getElementById('projectPriority').value,
  };

  try {
    const url = id ? '/SideKick/api/projects/update.php' : '/SideKick/api/projects/create.php';
    if (id) payload.project_id = id;
    
    const r = await fetch(url, {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    });
    const d = await r.json();
    
    if (!id) {
      const newProject = {...payload, id: Date.now(), progress: 0, color: '#6366f1'};
      allProjects.unshift(newProject);
    } else {
      const idx = allProjects.findIndex(x => x.id == id);
      if (idx >= 0) allProjects[idx] = {...allProjects[idx], ...payload};
    }
  } catch (_) {
    if (!id) {
      const newProject = {...payload, id: Date.now(), progress: 0, color: '#6366f1'};
      allProjects.unshift(newProject);
    }
  }

  renderProjects(allProjects);
  closeCreateModal();
  showToast(id ? 'Project updated' : 'Project created', 'success');
}

async function deleteProject(e, id) {
  e.stopPropagation();
  if (!confirm('Delete this project?')) return;
  
  try {
    await fetch('/SideKick/api/projects/delete.php?id=' + id, {method: 'DELETE'});
  } catch (_) {}
  
  allProjects = allProjects.filter(p => p.id !== id);
  renderProjects(allProjects);
  showToast('Project deleted', 'success');
}

function esc(s) {
  return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function showToast(msg, type) {
  const t = document.createElement('div');
  t.className = `fixed bottom-6 right-6 px-4 py-3 rounded-lg font-medium text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', () => {
  loadProjects();
  if(localStorage.getItem('sidekick_theme')==='dark') document.documentElement.classList.add('dark');
});
</script>
</body>
</html>




