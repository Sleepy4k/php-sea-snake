<?= $this->extend('errors.minimal') ?>

<?= $this->set('code', '403') ?>

<?= $this->section('content') ?>
  <h2>403 - Forbidden Error</h2>
  <p><?= $get('message', '') ?></p>
<?= $this->stop() ?>