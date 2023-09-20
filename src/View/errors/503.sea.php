<?= $this->extend('errors.minimal') ?>

<?= $this->set('code', '503') ?>

<?= $this->section('content') ?>
  <h2>503 - Service Unavailable</h2>
  <p><?= $get('message', '') ?></p>
<?= $this->stop() ?>