<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tableau de bord</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
</head>
<body>
<div class="app">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="logo-icon"></div>
      <div>
        <div class="brand-name">SysInfo</div>
      </div>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Tableau de bord</div>
    </div>
    <div class="content">
      <div class="page-header">
        <h2>Tableau de bord</h2>
      </div>
      <div class="kpi-grid">
        <div class="kpi-card"><div class="kpi-label">Étudiants</div><div class="kpi-value">—</div></div>
        <div class="kpi-card"><div class="kpi-label">Notes</div><div class="kpi-value">—</div></div>
        <div class="kpi-card"><div class="kpi-label">Crédits</div><div class="kpi-value">—</div></div>
        <div class="kpi-card"><div class="kpi-label">Disponibilité</div><div class="kpi-value">99.8%</div></div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>