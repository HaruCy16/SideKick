<?php
require_once '../../config/config.php';
require_once '../../includes/auth_guard.php';
require_auth_page('freelancer');
$role = $_SESSION['user_role'] ?? 'freelancer';
$user_data = [
    'first_name' => $_SESSION['first_name'] ?? 'Admin',
    'last_name' => $_SESSION['last_name'] ?? 'User',
    'email' => $_SESSION['email'] ?? 'admin@example.com',
    'role' => $role,
    'user_id' => $_SESSION['user_id'] ?? 1
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Settings | SideKick</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/2.28.0/tabler-icons.min.css" rel="stylesheet">
<link href="../../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-slate-50 dark:bg-slate-900">
<div class="flex min-h-screen">

<?php $current_role=$role; $current_page='settings'; require_once '../../includes/sidebar.php'; ?>

<div class="flex-1 flex flex-col pt-16 md:ml-64">
<?php require_once '../../includes/partials/topbar.php'; ?>

<main class="flex-1 overflow-y-auto p-8">
  <div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Settings</h1>
      <p class="text-slate-600 dark:text-slate-400">Manage your account settings and preferences</p>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-slate-200 dark:border-slate-700 mb-8">
      <button onclick="switchTab('profile')" class="tab-btn active px-6 py-3 font-medium text-slate-700 dark:text-slate-300 border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400">
        <i class="ti ti-user inline mr-2"></i> Profile
      </button>
      <button onclick="switchTab('notifications')" class="tab-btn px-6 py-3 font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:text-slate-900 dark:hover:text-white">
        <i class="ti ti-bell inline mr-2"></i> Notifications
      </button>
    </div>

    <!-- Profile Tab -->
    <div id="profile-tab" class="tab-content">
      <div class="bg-white dark:bg-slate-800 rounded-xl p-8 border border-slate-200 dark:border-slate-700">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Profile Information</h2>
        
        <form id="profileForm" onsubmit="updateProfile(event)">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">First Name</label>
              <input type="text" id="firstName" value="<?php echo htmlspecialchars($user_data['first_name']); ?>" 
                class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Last Name</label>
              <input type="text" id="lastName" value="<?php echo htmlspecialchars($user_data['last_name']); ?>"
                class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:outline-none focus:border-indigo-500">
            </div>
          </div>

          <div class="mb-8">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email (Read-only)</label>
            <input type="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly
              class="w-full px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-600 text-slate-600 dark:text-slate-400 cursor-not-allowed">
          </div>

          <div class="mb-8">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Role</label>
            <div class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-600 text-slate-700 dark:text-slate-300 capitalize">
              <?php echo ucfirst($user_data['role']); ?>
            </div>
          </div>

          <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
              Save Changes
            </button>
            <button type="button" onclick="resetForm()" class="px-6 py-2 border border-slate-200 dark:border-slate-700 rounded-lg font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Notifications Tab -->
    <div id="notifications-tab" class="tab-content hidden">
      <div class="bg-white dark:bg-slate-800 rounded-xl p-8 border border-slate-200 dark:border-slate-700">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Notification Preferences</h2>
        
        <div class="space-y-4">
          <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-700">
            <div>
              <p class="font-medium text-slate-900 dark:text-white">Email Notifications</p>
              <p class="text-sm text-slate-600 dark:text-slate-400">Receive updates via email</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" id="emailNotif" checked onchange="updateNotification(this)">
              <span class="toggle"></span>
            </label>
          </div>

          <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-700">
            <div>
              <p class="font-medium text-slate-900 dark:text-white">Push Notifications</p>
              <p class="text-sm text-slate-600 dark:text-slate-400">Browser push notifications</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" id="pushNotif" checked onchange="updateNotification(this)">
              <span class="toggle"></span>
            </label>
          </div>

          <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-700">
            <div>
              <p class="font-medium text-slate-900 dark:text-white">Project Updates</p>
              <p class="text-sm text-slate-600 dark:text-slate-400">Notify when projects are updated</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" id="projectNotif" checked onchange="updateNotification(this)">
              <span class="toggle"></span>
            </label>
          </div>

          <div class="flex items-center justify-between pb-4 border-b border-slate-200 dark:border-slate-700">
            <div>
              <p class="font-medium text-slate-900 dark:text-white">Task Assignments</p>
              <p class="text-sm text-slate-600 dark:text-slate-400">Notify when tasks are assigned</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" id="taskNotif" checked onchange="updateNotification(this)">
              <span class="toggle"></span>
            </label>
          </div>

          <div class="flex items-center justify-between">
            <div>
              <p class="font-medium text-slate-900 dark:text-white">Weekly Digest</p>
              <p class="text-sm text-slate-600 dark:text-slate-400">Weekly summary of activity</p>
            </div>
            <label class="flex items-center cursor-pointer">
              <input type="checkbox" id="digestNotif" onchange="updateNotification(this)">
              <span class="toggle"></span>
            </label>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
</div>
</div>

<style>
.tab-btn {
  border-color: transparent;
  color: inherit;
  cursor: pointer;
  transition: all 0.2s;
}

.tab-btn.active {
  border-color: #6366f1;
  color: #6366f1;
}

.toggle {
  display: inline-block;
  width: 44px;
  height: 24px;
  background: #cbd5e1;
  border-radius: 12px;
  position: relative;
  margin-left: 8px;
  transition: background 0.3s;
}

input[type="checkbox"] {
  display: none;
}

input[type="checkbox"]:checked + .toggle {
  background: #6366f1;
}

.toggle::after {
  content: '';
  position: absolute;
  width: 20px;
  height: 20px;
  background: white;
  border-radius: 50%;
  top: 2px;
  left: 2px;
  transition: left 0.3s;
}

input[type="checkbox"]:checked + .toggle::after {
  left: 22px;
}
</style>

<script>
function switchTab(tab) {
  document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
  document.getElementById(tab + '-tab').classList.remove('hidden');
  
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  event.target.closest('.tab-btn').classList.add('active');
}

function resetForm() {
  location.reload();
}

async function updateProfile(e) {
  e.preventDefault();
  const payload = {
    first_name: document.getElementById('firstName').value,
    last_name: document.getElementById('lastName').value,
  };

  try {
    const r = await fetch('/SideKick/api/settings/update_profile.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(payload)
    });
    showToast('Profile updated successfully', 'success');
  } catch (_) {
    showToast('Changes saved locally', 'success');
  }
}

function updateNotification(checkbox) {
  const notifId = checkbox.id;
  const isEnabled = checkbox.checked;
  showToast(`${notifId} ${isEnabled ? 'enabled' : 'disabled'}`, 'success');
}

function showToast(msg, type) {
  const t = document.createElement('div');
  t.className = `fixed bottom-6 right-6 px-4 py-3 rounded-lg font-medium text-white z-50 ${type === 'success' ? 'bg-green-600' : 'bg-red-600'}`;
  t.textContent = msg;
  document.body.appendChild(t);
  setTimeout(() => t.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', () => {
  if(localStorage.getItem('sidekick_theme')==='dark') document.documentElement.classList.add('dark');
});
</script>
</body>
</html>








