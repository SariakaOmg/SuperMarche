<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Notes de l'étudiant</title>
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
      <div class="topbar-title">Notes de l'étudiant</div>
    </div>
    <div class="content">
      <div class="page-header">
        <h2>Notes — <?= esc($student['firstname'].' '.$student['lastname']) ?></h2>
      </div>

      <?php foreach (($notesBySemester ?? []) as $sem => $semNotes): ?>
      <h3 style="margin:18px 0 8px;">Semestre <?= esc($sem) ?></h3>
      <div class="alert alert-info">
        <span>
          <strong>Moyenne:</strong> <?= esc($semesterSummary[$sem]['avg'] ?? 0) ?>
          — <strong>Mention:</strong> <?= esc($semesterSummary[$sem]['mention'] ?? 'N/A') ?>
          — <strong>Crédits:</strong> <?= esc($semesterSummary[$sem]['credits'] ?? 0) ?>/<?= esc($semesterSummary[$sem]['required'] ?? 0) ?>
          — <strong>Passé:</strong> <?= !empty($semesterSummary[$sem]['pass']) ? 'Oui' : 'Non' ?>
        </span>
      </div>

      <div class="table-card" style="margin-bottom:12px;">
        <table>
          <thead><tr><th>Matière</th><th>Note</th><th>Coef</th><th>Crédit</th><th>Optionnel</th><th>Actions</th></tr></thead>
          <tbody>
          <?php foreach ($semNotes as $n): ?>
            <tr>
              <td><?= esc($n['subject']) ?></td>
              <td><?= esc($n['note']) ?></td>
              <td><?= esc($n['coeff']) ?></td>
              <td><?= esc($n['credit']) ?></td>
              <td><?= !empty($n['optional']) ? 'Oui' : 'Non' ?></td>
              <td>
                <?php if (!empty($n['note_id'])): ?>
                  <form method="post" action="<?= site_url('notes/update/' . $n['note_id']) ?>" style="display:inline-flex;gap:6px;align-items:center;">
                    <input type="hidden" name="student_id" value="<?= esc($student['id']) ?>" />
                    <input type="number" name="note" step="0.01" min="0" max="20" value="<?= esc($n['note']) ?>" style="width:80px;" />
                    <button type="submit" class="btn btn-secondary btn-sm">Modifier</button>
                  </form>
                  <form method="post" action="<?= site_url('notes/delete/' . $n['note_id']) ?>" style="display:inline-block;margin-left:6px;" onsubmit="return confirm('Supprimer cette note ?');">
                    <input type="hidden" name="student_id" value="<?= esc($student['id']) ?>" />
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                  </form>
                <?php else: ?>
                  <span style="color:#64748b;">Aucune note</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach ?>
          </tbody>
        </table>
      </div>
      <?php endforeach; ?>

      <h3 style="margin:20px 0 8px;">Bilan L2 (S3 + S4)</h3>
      <div class="alert alert-info">
        <span>
          <strong>Moyenne L2:</strong> <?= esc($l2Avg ?? 'N/A') ?>
          — <strong>Mention:</strong> <?= esc($l2Mention ?? 'N/A') ?>
          — <strong>Crédits:</strong> <?= esc($l2Credits ?? 0) ?>/<?= esc($l2Required ?? 0) ?>
          — <strong>Passage L2:</strong> <?= !empty($l2Pass) ? 'Oui' : 'Non' ?>
        </span>
      </div>

      <div style="margin-top:12px">
        <a href="<?= site_url('notes/add/' . $student['id']) ?>" class="btn btn-primary">Ajouter note</a>
        <a href="<?= site_url('notes') ?>" class="btn btn-secondary">Retour</a>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>