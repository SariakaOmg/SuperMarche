<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Liste des étudiants</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>" />
</head>
<body>

<?php // reuse template shell from design: minimal sidebar + main area ?>
<div class="app">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="logo-icon"></div>
      <div>
        <div class="brand-name">SysInfo</div>
        <div class="brand-sub">v2.4.0</div>
      </div>
    </div>
    <div class="sidebar-section">Navigation</div>
    <a href="<?= site_url('') ?>" class="nav-item">Tableau de bord</a>
    <a href="<?= site_url('notes') ?>" class="nav-item active">Etudiants</a>
  </aside>

  <div class="main">
    <div class="topbar">
      <div class="topbar-title">Liste des étudiants</div>
      <div class="topbar-actions">
        <a href="<?= site_url('notes/add') ?>" class="btn btn-primary">Saisie des notes</a>
      </div>
    </div>
    <div class="content">
      <?php if (!empty($dbError)): ?>
        <div class="alert alert-info" style="margin-bottom:16px;">
          <span><?= esc($dbError) ?></span>
        </div>
      <?php endif; ?>
      <div class="table-card">
        <table>
          <thead>
            <tr><th>Nom</th><th>Matricule</th><th>Programme</th><th>Consultation</th></tr>
          </thead>
          <tbody>
            <?php foreach (($students ?? []) as $s): ?>
            <tr>
              <td><?= esc($s['firstname'].' '.$s['lastname']) ?></td>
              <td><?= esc($s['matricule']) ?></td>
              <td><?= esc($s['program']) ?></td>
              <td>
                <a href="<?= site_url('notes/view/'.$s['id']) ?>">Voir notes</a>
              </td>
            </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>