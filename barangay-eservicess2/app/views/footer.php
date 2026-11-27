<?php $appLayout = $appLayout ?? false; ?>
</main>
<footer class="<?= $appLayout ? 'app-footer' : 'site-footer' ?>">
  <?php if ($appLayout): ?>
    <div class="app-footer-inner">
      <span><strong><?= e(app_name()) ?></strong></span>
      <span>Document requests and community concern reporting</span>
    </div>
  <?php else: ?>
    <div class="container-xl public-footer-grid">
      <div class="footer-brand">
        <strong><?= e(barangay_display_name()) ?></strong>
        <?php if (barangay_location() !== ''): ?><span><?= e(barangay_location()) ?></span><?php endif; ?>
        <span>Online document requests and community concern reporting</span>
      </div>
      <nav class="footer-links" aria-label="Footer">
        <strong>Quick links</strong>
        <a href="<?= e(url('index.php')) ?>">Home</a>
        <a href="<?= e(url('services.php')) ?>">Services and requirements</a>
        <a href="<?= e(url('auth/login.php')) ?>">Sign in</a>
        <a href="<?= e(url('auth/register.php')) ?>">Register</a>
      </nav>
      <div class="footer-hours">
        <strong>Office hours</strong>
        <span><?= e(office_hours() ?: 'Please ask at the barangay hall.') ?></span>
      </div>
    </div>
    <div class="container-xl footer-bottom">
      <span>&copy; <?= e(date('Y')) ?> <?= e(app_name()) ?></span>
      <span>For barangay services and community assistance</span>
    </div>
  <?php endif; ?>
</footer>
<?php if ($appLayout): ?>
  </div><!-- /.app-main -->
</div><!-- /.app-shell -->
<?php endif; ?>
<script src="<?= e(asset('vendor/bootstrap/bootstrap.bundle.min.js')) ?>"></script>
<script src="<?= e(asset('js/app.js')) ?>"></script>
</body>
</html>
