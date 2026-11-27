<?php
require __DIR__ . '/app/bootstrap.php';

// Public landing page. Signed-in users go straight to their dashboard.
if (current_user()) {
    redirect(is_verified() ? home_path_for_current_user() : 'auth/verify-notice.php');
}

$docTypes = db_all('SELECT doc_type_id, name, description, fee, processing_days FROM document_types WHERE is_archived = 0 ORDER BY name');
$categories = db_all('SELECT name FROM concern_categories WHERE is_archived = 0 ORDER BY name');
$pageTitle = 'Home';
$activeNav = 'home';
require APP_ROOT . '/app/views/header.php';
?>
<section class="landing-hero" aria-labelledby="landing-title">
  <div class="landing-hero-copy">
    <div class="landing-id">
      <?= render_seal('landing-seal') ?>
      <div>
        <strong><?= e(barangay_display_name()) ?></strong>
        <?php if (barangay_location() !== ''): ?><span><?= e(barangay_location()) ?></span><?php endif; ?>
      </div>
    </div>
    <p class="landing-eyebrow">Barangay online services</p>
    <h1 id="landing-title">Request barangay documents and report community concerns online</h1>
    <p class="lead">Check requirements, submit your requests ahead, and follow their progress from your phone. Residents can also report local problems and help the barangay respond more efficiently.</p>
    <div class="landing-actions d-flex flex-wrap gap-2">
      <a class="btn btn-primary btn-lg" href="<?= e(url('auth/register.php')) ?>">Get started</a>
      <a class="btn btn-outline-primary btn-lg" href="<?= e(url('auth/login.php')) ?>">Sign in</a>
    </div>
  </div>

  <aside class="hall-card" aria-label="Barangay hall information">
    <div class="hall-card-heading">
      <span class="hall-card-icon" aria-hidden="true"><?= icon('services') ?></span>
      <div><p>Visit the barangay hall</p><h2>Office information</h2></div>
    </div>
    <dl class="hall-details">
      <?php if (barangay_name() !== ''): ?><div><dt>Barangay</dt><dd><?= e(barangay_name()) ?></dd></div><?php endif; ?>
      <?php if (barangay_location() !== ''): ?><div><dt>Location</dt><dd><?= e(barangay_location()) ?></dd></div><?php endif; ?>
      <div><dt>Office hours</dt><dd><?= e(office_hours() ?: 'Please ask at the barangay hall.') ?></dd></div>
    </dl>
    <div class="hall-notice">
      <strong>Before your visit</strong>
      <span>Bring valid ID and the required documents. Releases and payments are made at the barangay hall during office hours.</span>
    </div>
  </aside>
</section>

<section class="landing-section" id="services" aria-labelledby="services-title">
  <div class="landing-section-heading">
    <div>
      <p class="landing-eyebrow">Online services</p>
      <h2 id="services-title">Available document requests</h2>
    </div>
    <a class="text-link" href="<?= e(url('services.php')) ?>">View services and requirements <span aria-hidden="true">→</span></a>
  </div>
  <p class="landing-section-lead">Review fees, processing times, and requirements before submitting a request. A resident account is required to apply.</p>

  <?php if ($docTypes): ?>
    <div class="service-grid">
      <?php foreach ($docTypes as $d): ?>
        <article class="service-card" id="doc-<?= (int) $d['doc_type_id'] ?>">
          <div class="service-card-icon" aria-hidden="true"><?= icon('document') ?></div>
          <div class="service-card-body">
            <h3><?= e($d['name']) ?></h3>
            <?php if (trim((string) $d['description']) !== ''): ?><p><?= e($d['description']) ?></p><?php endif; ?>
            <dl class="service-meta">
              <div><dt>Service fee</dt><dd><?= (float) $d['fee'] > 0 ? e(peso($d['fee'])) : 'Free' ?></dd></div>
              <div><dt>Processing</dt><dd><?= (int) $d['processing_days'] ?> working day<?= (int) $d['processing_days'] === 1 ? '' : 's' ?></dd></div>
            </dl>
            <a class="service-link" href="<?= e(url('services.php#doc-' . (int) $d['doc_type_id'])) ?>">View requirements <span aria-hidden="true">→</span><span class="visually-hidden"> for <?= e($d['name']) ?></span></a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="landing-empty">No documents are available online yet.</div>
  <?php endif; ?>
</section>

<section class="landing-section" id="how-it-works" aria-labelledby="request-title">
  <div class="landing-section-heading">
    <div>
      <p class="landing-eyebrow">Simple process</p>
      <h2 id="request-title">How to request a document</h2>
    </div>
  </div>
  <ol class="landing-steps">
    <li><span class="step-number">1</span><div><h3>Check the requirements</h3><p>Review the fee, processing time, and documents you need to prepare.</p></div></li>
    <li><span class="step-number">2</span><div><h3>Register and verify</h3><p>Create a resident account and verify it through the email link.</p></div></li>
    <li><span class="step-number">3</span><div><h3>Submit your request</h3><p>Choose the document and purpose, then receive a reference number.</p></div></li>
    <li><span class="step-number">4</span><div><h3>Claim at the hall</h3><p>Once marked Ready for Pickup, bring a valid ID and the fee.</p></div></li>
  </ol>
</section>

<section class="concern-section" id="concerns" aria-labelledby="concern-title">
  <div class="concern-copy">
    <p class="landing-eyebrow">Community reporting</p>
    <h2 id="concern-title">See a local problem? Report it.</h2>
    <p>Notify the barangay about issues in your purok and follow the status from submission to resolution.</p>
    <ol class="concern-steps">
      <li><strong>Sign in</strong><span>Use your verified resident account.</span></li>
      <li><strong>Describe the concern</strong><span>Choose a category and provide the exact location or landmark.</span></li>
      <li><strong>Track progress</strong><span>Staff will review, assign, and update the status.</span></li>
    </ol>
  </div>

  <aside class="concern-card">
    <div class="concern-card-icon" aria-hidden="true"><?= icon('concern') ?></div>
    <h3>Reportable concerns</h3>
    <?php if ($categories): ?>
      <ul class="concern-list">
        <?php foreach ($categories as $c): ?><li><?= e($c['name']) ?></li><?php endforeach; ?>
      </ul>
    <?php else: ?>
      <p class="mb-0 text-muted">Concern categories are being updated.</p>
    <?php endif; ?>
    <a class="btn btn-primary w-100" href="<?= e(url(current_user() ? 'resident/concern-form.php' : 'auth/register.php')) ?>">Report a concern</a>
  </aside>
</section>

<section class="landing-cta" aria-label="Create an account">
  <div>
    <p class="landing-eyebrow">Online barangay services</p>
    <h2>Get started with your resident account</h2>
    <p>Register once to request documents and report concerns from any device.</p>
  </div>
  <div class="landing-actions d-flex flex-wrap gap-2">
    <a class="btn btn-primary btn-lg" href="<?= e(url('auth/register.php')) ?>">Register now</a>
    <a class="btn btn-outline-primary btn-lg" href="<?= e(url('auth/login.php')) ?>">Sign in</a>
  </div>
</section>
<?php require APP_ROOT . '/app/views/footer.php'; ?>
