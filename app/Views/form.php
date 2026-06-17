<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ajouter une note</title>
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
      <div class="topbar-title">Ajouter une note</div>
    </div>
    <div class="content">
      <div class="form-card">
        <form method="get" action="<?= current_url() ?>" style="margin-bottom:16px;">
          <div>
            <label>Etudiant</label>
            <select name="student_id" required onchange="this.form.submit()">
              <?php foreach ($students as $s): ?>
                <option value="<?= esc($s['id']) ?>" <?= ((int) $student_id === (int) $s['id']) ? 'selected' : '' ?>>
                  <?= esc($s['firstname'] . ' ' . $s['lastname']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div style="margin-top:10px;">
            <label>Semestre</label>
            <select name="semester" required onchange="this.form.submit()">
              <?php foreach (($semesters ?? []) as $sem): ?>
                <option value="<?= esc($sem['nomSemestre']) ?>" <?= (($selectedSemester ?? '') === $sem['nomSemestre']) ? 'selected' : '' ?>>
                  <?= esc($sem['nomSemestre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </form>

        <form method="post" action="<?= current_url() ?>">
          <input type="hidden" name="student_id" value="<?= esc($student_id) ?>" />
          <div>
            <label>Matière</label>
            <select name="idmatiere" required>
              <?php foreach (($subjects ?? []) as $sub): ?>
                <option value="<?= esc($sub['idMatiere']) ?>">
                  <?= esc($sub['nomMatiere']) ?> (<?= esc($sub['semester']) ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Note</label>
            <input type="number" name="note" step="0.01" min="0" max="20" required />
          </div>
          <div style="margin-top:12px">
            <button class="btn btn-primary" type="submit">Enregistrer la note</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/script.js') ?>"></script>
</body>
</html>