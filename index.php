<?php
$pageTitle = "SorgumHub - Sorgum Pengganti Nasi";
require __DIR__ . "/includes/db.php";

$defaultHero = [
  'name' => 'Sorgum Instant Pack',
  'description' => 'Paket siap masak, tinggal seduh, cocok untuk meal prep mingguan.',
  'price' => 49000,
  'image_path' => 'https://selayar.quarta.id/wp-content/uploads/2025/02/sorgum.jpg',
];

$defaultHighlights = [
  ['title' => '250 gram', 'subtitle' => 'Paket mencoba'],
  ['title' => '1 kilogram', 'subtitle' => 'Paket keluarga'],
  ['title' => '3 kilogram', 'subtitle' => 'Paket hemat'],
  ['title' => 'Bumbu rempah', 'subtitle' => 'Aroma spesial'],
];

$defaultPackages = [
  ['name' => 'Starter Pack', 'description' => 'Untuk kamu yang baru coba sorgum.', 'price' => 49000],
  ['name' => 'Family Pack', 'description' => 'Cocok untuk kebutuhan satu minggu.', 'price' => 159000],
  ['name' => 'Healthy Bulk', 'description' => 'Stok lebih lama, harga lebih hemat.', 'price' => 399000],
];

$defaultFaqs = [
  [
    'question' => 'Apakah sorgum cocok untuk diet rendah gula?',
    'answer' => 'Ya, sorgum memiliki indeks glikemik rendah dan kaya serat sehingga aman untuk diet seimbang.',
  ],
  [
    'question' => 'Berapa lama proses pengiriman?',
    'answer' => 'Pengiriman diproses maksimal 1x24 jam setelah pembayaran terkonfirmasi.',
  ],
  [
    'question' => 'Apakah ada panduan memasak?',
    'answer' => 'Setiap pembelian mendapatkan kartu resep dan akses ke tips meal prep mingguan.',
  ],
];

$packageFeatures = [
  ['250 gram sorgum', 'Resep sederhana', 'Konsultasi admin'],
  ['1 kg sorgum premium', 'Bonus bumbu rempah', 'Resep diet mingguan'],
  ['3 kg sorgum premium', 'Konsultasi nutrisi', 'Gratis ongkir Jawa'],
];

$products = [];
$heroProduct = $defaultHero;
$highlightCards = $defaultHighlights;
$packageData = [];
$faqs = $defaultFaqs;
$statsOrder = '1.200+';

try {
  $products = db_fetch_all(
    "SELECT id, name, price, description, image_path, status
         FROM products
         WHERE status != 'Nonaktif'
         ORDER BY id ASC"
  );
  $orderCount = (int) db_fetch_value("SELECT COUNT(*) FROM orders");
  if ($orderCount > 0) {
    $statsOrder = number_format($orderCount);
  }

  $faqRows = db_fetch_all(
    "SELECT question, answer
         FROM faqs
         WHERE status = 'Aktif'
         ORDER BY sort_order ASC, id ASC"
  );
  if ($faqRows) {
    $faqs = $faqRows;
  }
} catch (Throwable $e) {
  $products = [];
}

if ($products) {
  $heroProduct = $products[0];
  $highlightCards = [];
  foreach (array_slice($products, 0, 4) as $product) {
    $highlightCards[] = [
      'title' => $product['name'],
      'subtitle' => $product['description'] ?: rupiah((float) $product['price']),
    ];
  }
}

$startingPrice = $defaultHero['price'];
if ($products) {
  $prices = array_map('floatval', array_column($products, 'price'));
  if ($prices) {
    $startingPrice = min($prices);
  }
}

for ($i = 0; $i < 3; $i++) {
  $fallback = $defaultPackages[$i];
  $features = $packageFeatures[$i] ?? [];
  if (isset($products[$i])) {
    $product = $products[$i];
    $packageData[] = [
      'name' => $product['name'],
      'description' => $product['description'] ?: $fallback['description'],
      'price' => (float) $product['price'],
      'features' => $features,
    ];
  } else {
    $packageData[] = [
      'name' => $fallback['name'],
      'description' => $fallback['description'],
      'price' => (float) $fallback['price'],
      'features' => $features,
    ];
  }
}

$heroImage = !empty($heroProduct['image_path']) ? $heroProduct['image_path'] : $defaultHero['image_path'];
$heroName = $heroProduct['name'] ?? $defaultHero['name'];
$heroDescription = $heroProduct['description'] ?: $defaultHero['description'];

include __DIR__ . "/includes/header.php";
?>
<main>
  <section class="py-5 section-tint section-sunrise">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-6">
          <span class="brand-badge">Sorgum premium lokal</span>
          <h1 class="display-5 fw-bold mt-3">Sorgum sebagai pengganti nasi yang lebih ringan dan tetap mengenyangkan.</h1>
          <p class="section-subtitle mt-3">Dikemas praktis, kaya serat, dan cocok untuk gaya hidup sehat. Nikmati tekstur lembut dan rasa gurih alami yang pas untuk menu harian.</p>
          <div class="d-flex flex-wrap gap-3 mt-4">
            <a class="btn btn-brand" href="/checkout.php">Pesan Sekarang</a>
            <a class="btn btn-outline-brand" href="#produk">Lihat Detail</a>
          </div>
          <div class="d-flex gap-4 mt-4 text-muted">
            <div>
              <div class="fw-semibold text-dark"><?php echo htmlspecialchars($statsOrder); ?></div>
              <small>Pesanan terkirim</small>
            </div>
            <div>
              <div class="fw-semibold text-dark">4.9/5</div>
              <small>Rating pelanggan</small>
            </div>
            <div>
              <div class="fw-semibold text-dark">24 jam</div>
              <small>Dukungan admin</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-card" data-reveal>
            <div class="row g-3 align-items-center">
              <div class="col-md-6">
                <div class="product-shot hero-illust">
                  <img src="<?php echo htmlspecialchars($heroImage); ?>" alt="Produk sorgum" class="rounded-4">
                </div>
              </div>
              <div class="col-md-6">
                <h4 class="fw-bold"><?php echo htmlspecialchars($heroName); ?></h4>
                <p class="text-muted"><?php echo htmlspecialchars($heroDescription); ?></p>
                <ul class="list-unstyled text-muted">
                  <li class="mb-2"><i class="bi bi-check-circle text-warning me-2"></i>Tekstur pulen, tidak keras</li>
                  <li class="mb-2"><i class="bi bi-check-circle text-warning me-2"></i>Rendah gula, tinggi serat</li>
                  <li class="mb-2"><i class="bi bi-check-circle text-warning me-2"></i>100% sorgum lokal</li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge-orange">Diskon 15%</span>
                  <span class="text-muted">Mulai <?php echo rupiah((float) $startingPrice); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 section-tint section-mint">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4" data-reveal>
          <div class="info-card h-100">
            <h5>Lebih sehat</h5>
            <p class="text-muted">Sorgum memiliki indeks glikemik rendah yang cocok untuk pola makan seimbang.</p>
          </div>
        </div>
        <div class="col-lg-4" data-reveal>
          <div class="info-card h-100">
            <h5>Praktis untuk keluarga</h5>
            <p class="text-muted">Kemasan siap masak dan resep pilihan untuk sarapan hingga menu diet.</p>
          </div>
        </div>
        <div class="col-lg-4" data-reveal>
          <div class="info-card h-100">
            <h5>Pasokan terjaga</h5>
            <p class="text-muted">Dukungan petani lokal dan pengolahan higienis standar pangan modern.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="produk" class="py-5 section-tint section-cream">
    <div class="container">
      <div class="row align-items-center g-4">
        <div class="col-lg-6" data-reveal>
          <h2 class="section-title">Kenali produk sorgum kami</h2>
          <p class="section-subtitle">Diformulasikan khusus agar mudah dimasak, cocok untuk diet rendah gula, dan tetap mengenyangkan.</p>
          <div class="row g-3 mt-4">
            <?php foreach ($highlightCards as $card): ?>
              <div class="col-6">
                <div class="info-card">
                  <h6 class="fw-semibold"><?php echo htmlspecialchars($card['title']); ?></h6>
                  <p class="text-muted mb-0"><?php echo htmlspecialchars($card['subtitle']); ?></p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="col-lg-6" data-reveal>
          <div class="product-shot">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhIvdv7jI2dj-YVWpyqsIpLnG8kwzN2MzYLQ&s" alt="Butiran sorgum" class="rounded-4">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="paket" class="py-5 section-tint section-sky">
    <div class="container">
      <div class="text-center mb-5" data-reveal>
        <h2 class="section-title">Pilih paket yang sesuai kebutuhan</h2>
        <p class="section-subtitle">Semua paket sudah termasuk panduan masak, tips meal prep, dan bonus resep mingguan.</p>
      </div>
      <div class="row g-4">
        <?php foreach ($packageData as $index => $package): ?>
          <div class="col-lg-4" data-reveal>
            <div class="package-card <?php echo $index === 1 ? 'popular' : ''; ?> h-100">
              <?php if ($index === 1): ?>
                <span class="badge-orange">Favorit</span>
              <?php endif; ?>
              <h5 class="fw-bold <?php echo $index === 1 ? 'mt-3' : ''; ?>"><?php echo htmlspecialchars($package['name']); ?></h5>
              <p class="text-muted"><?php echo htmlspecialchars($package['description']); ?></p>
              <div class="price"><?php echo rupiah((float) $package['price']); ?></div>
              <ul class="list-unstyled text-muted mt-3">
                <?php foreach ($package['features'] as $feature): ?>
                  <li class="mb-2"><i class="bi bi-check text-warning me-2"></i><?php echo htmlspecialchars($feature); ?></li>
                <?php endforeach; ?>
              </ul>
              <a class="btn <?php echo $index === 1 ? 'btn-brand' : 'btn-outline-brand'; ?> w-100" href="/checkout.php">Pilih Paket</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section id="testimoni" class="py-5 section-tint section-mint">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4" data-reveal>
          <div class="testimonial h-100">
            <p class="mb-3">"Rasanya enak, teksturnya mirip nasi. Cocok buat diet keluarga di rumah."</p>
            <div class="fw-semibold">Rani, Bandung</div>
          </div>
        </div>
        <div class="col-lg-4" data-reveal>
          <div class="testimonial h-100">
            <p class="mb-3">"Pengiriman cepat dan adminnya responsif. Saya jadi langganan."</p>
            <div class="fw-semibold">Iqbal, Jakarta</div>
          </div>
        </div>
        <div class="col-lg-4" data-reveal>
          <div class="testimonial h-100">
            <p class="mb-3">"Sorgumnya praktis, tinggal masak. Bumbu rempahnya favorit."</p>
            <div class="fw-semibold">Maya, Surabaya</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-5 section-tint section-sunrise">
    <div class="container" data-reveal>
      <div class="cta-banner">
        <div class="row align-items-center g-3">
          <div class="col-lg-8">
            <h3 class="fw-bold">Siap mulai hidup lebih sehat bersama sorgum?</h3>
            <p class="text-muted">Pesan sekarang dan dapatkan bonus resep harian langsung dari tim nutrisi kami.</p>
          </div>
          <div class="col-lg-4 text-lg-end">
            <a class="btn btn-brand" href="/checkout.php">Checkout Sekarang</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="faq" class="py-5 section-tint section-cream">
    <div class="container">
      <div class="text-center mb-4" data-reveal>
        <h2 class="section-title">Pertanyaan umum</h2>
      </div>
      <div class="accordion" id="faqAccordion" data-reveal>
        <?php foreach ($faqs as $index => $faq): ?>
          <?php
          $itemId = 'faqItem' . $index;
          $collapseId = 'faqCollapse' . $index;
          $isOpen = $index === 0;
          ?>
          <div class="accordion-item">
            <h2 class="accordion-header" id="<?php echo $itemId; ?>">
              <button class="accordion-button <?php echo $isOpen ? '' : 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $collapseId; ?>" aria-expanded="<?php echo $isOpen ? 'true' : 'false'; ?>" aria-controls="<?php echo $collapseId; ?>">
                <?php echo htmlspecialchars($faq['question']); ?>
              </button>
            </h2>
            <div id="<?php echo $collapseId; ?>" class="accordion-collapse collapse <?php echo $isOpen ? 'show' : ''; ?>" aria-labelledby="<?php echo $itemId; ?>" data-bs-parent="#faqAccordion">
              <div class="accordion-body"><?php echo htmlspecialchars($faq['answer']); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php include __DIR__ . "/includes/footer.php"; ?>