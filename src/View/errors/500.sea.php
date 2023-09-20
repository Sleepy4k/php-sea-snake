<?= $this->extend('errors.minimal') ?>

<?= $this->set('code', '500') ?>

<?= $this->section('content') ?>
  <h2>500 - Internal Server Error</h2>
  <p><?= $get('message', '') ?></p>
<?= $this->stop() ?>