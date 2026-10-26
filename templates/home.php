<?php
$seo_obj = $page ?: ['title' => setting('site_name', SITE_NAME), 'slug' => 'home'];
require __DIR__ . '/partials/header.php';
$dest = menu_tree('destinations');
$fleet = [
  ['n' => '70 Seater Coach', 'img' => 'fleet-70.jpg', 'r' => '4.9', 'rev' => '120+', 'pax' => '70 Passengers', 'desc' => 'Spacious luxury for large groups and long journeys.'],
  ['n' => '54 Seater Coach', 'img' => 'fleet-54.jpg', 'r' => '4.8', 'rev' => '98+',  'pax' => '54 Passengers', 'desc' => 'Comfortable executive travel with ample luggage space.'],
  ['n' => '49 Seater Coach', 'img' => 'fleet-49.jpg', 'r' => '4.7', 'rev' => '85+',  'pax' => '49 Passengers', 'desc' => 'Reliable group transport with air conditioning.'],
  ['n' => '16 Seater Minibus','img' => 'fleet-16.jpg', 'r' => '4.9', 'rev' => '140+', 'pax' => '16 Passengers', 'desc' => 'Perfect for smaller groups and airport transfers.'],
];
?>

<!-- ===== HERO (cinematic banner: horizontal quote bar + parked bus) ===== -->
<?php $useVideo = (setting('hero_bg', 'video') === 'video') && setting('hero_video'); ?>
<section class="hero hero-banner" id="hero" style="--hero-img:url('<?= asset('image/hero-bg.jpg') ?>')">
  <?php if ($useVideo): ?>
    <video class="hero-video" autoplay muted loop playsinline preload="auto" poster="<?= asset('image/hero-bg.jpg') ?>">
      <source src="<?= asset('video/' . setting('hero_video')) ?>" type="video/mp4">
    </video>
  <?php else: ?>
    <div class="road-lights" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
    <div class="parked-bus" aria-hidden="true">
      <img class="pb-img" src="<?= asset('image/hero-coach.png') ?>" alt="" onerror="this.closest('.parked-bus').remove()">
      <span class="pb-glow"></span>
      <span class="pb-lamp pb-lamp-l"></span><span class="pb-lamp pb-lamp-r"></span>
    </div>
  <?php endif; ?>
  <div class="hero-overlay"></div>
  <div class="hero-streaks" aria-hidden="true"><span></span><span></span><span></span><span></span><span></span><span></span></div>
  <div class="lightsweep"></div>

  <div class="container hero-stack">
    <!-- horizontal quote bar, directly below the menu -->
    <div class="quote-bar hero-anim" id="book">
      <div class="qb-title">Get An Instant <span class="accent">Quote</span> For Free</div>
      <form id="quoteForm" autocomplete="off">
        <div class="qb-row">
          <div class="qb-field">
            <label>Pickup Address</label>
            <div class="qb-in"><span class="i">📍</span><input type="text" id="pickup_location" name="pickup_location" placeholder="Enter a location" required>
              <input type="hidden" id="pickup_latitude" name="pickup_latitude"><input type="hidden" id="pickup_longitude" name="pickup_longitude"></div>
          </div>
          <div class="qb-field">
            <label>Drop-off Address</label>
            <div class="qb-in"><span class="i">📍</span><input type="text" id="drop_location" name="dropoff_location" placeholder="Enter destination" required>
              <input type="hidden" id="dropoff_latitude" name="dropoff_latitude"><input type="hidden" id="dropoff_longitude" name="dropoff_longitude"></div>
          </div>
          <div class="qb-field">
            <label>Pickup Date</label>
            <div class="qb-in"><span class="i">📅</span><input type="date" id="pickup_date" name="pickup_date" required></div>
          </div>
          <input type="hidden" name="distance" id="distance">
          <button class="btn btn-primary btn-lg qb-go btn-cta" type="submit">Get Instant Quote <span class="btn-arrow">→</span></button>
        </div>
      </form>
    </div>

    <div class="hero-content">
      <div class="hero-badges hero-anim">
        <span class="badge-item"><span class="badge-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/><path d="M4 11a2 2 0 0 1 2 2v3h12v-3a2 2 0 0 1 4 0v5a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-5a2 2 0 0 1 2-2z"/><path d="M6 18v2M18 18v2"/></svg></span>Comfortable Travel</span>
        <span class="badge-item"><span class="badge-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9.5"/><circle cx="12" cy="12" r="2.6"/><path d="M12 14.6V21M9.9 10.4 4.3 7.2M14.1 10.4 19.7 7.2"/></svg></span>Professional Drivers</span>
        <span class="badge-item"><span class="badge-ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3 3 5.5v15L9 18l6 3 6-2.5v-15L15 6 9 3z"/><path d="M9 3v15M15 6v15"/></svg></span>Nationwide Coverage</span>
      </div>
      <div class="hero-slider" id="heroSlider">
        <div class="hero-slide active">
          <h1 class="hero-title">Efficient and <span class="accent">Affordable</span></h1>
          <p class="sub">Experience affordable and efficient group transportation with Simple Coach Hire.</p>
        </div>
        <div class="hero-slide">
          <h1 class="hero-title"><span class="accent">Hassle-free</span> Travel</h1>
          <p class="sub">Simplify your group transportation with Simple Coach Hire.</p>
        </div>
        <div class="hero-slide">
          <h1 class="hero-title">Relax and <span class="accent">Enjoy the Ride</span></h1>
          <p class="sub">Sit back and relax with Simple Coach Hire — your ultimate group travel solution.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FLEET ===== -->
<section class="section" id="fleet">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Our Fleet</span>
      <h2>Find the Perfect Ride</h2>
      <p class="lead">Modern, comfortable and reliable vehicles for every occasion.</p>
    </div>
    <div class="swiper fleetSwiper">
      <div class="swiper-wrapper">
        <?php foreach ($fleet as $f): ?>
        <div class="swiper-slide">
          <div class="fleet-card reveal">
            <div class="fleet-img"><span class="tag"><?= e($f['pax']) ?></span>
              <img src="<?= asset('image/' . $f['img']) ?>" alt="<?= e($f['n']) ?>" loading="lazy" onerror="this.remove()"></div>
            <div class="fleet-body">
              <h3><?= e($f['n']) ?></h3>
              <div class="rating-row"><span class="stars">★★★★★</span><span><?= e($f['r']) ?> (<?= e($f['rev']) ?> reviews)</span></div>
              <div class="fleet-specs"><span>👥 <?= e($f['pax']) ?></span><span>🧳 Luggage Space</span><span>❄ Air Conditioning</span></div>
              <a class="btn btn-outline" href="<?= url('our-fleets') ?>">View Vehicle →</a>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-pagination" style="position:relative;margin-top:26px"></div>
    </div>
  </div>
</section>

<!-- ===== WELCOME / COMMITMENT ===== -->
<?php
$bsvg = [
 'pin'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
 'clock'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
 'wheel'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2.5"/><path d="M12 14.5V21M9.8 10.3 4.4 7.2M14.2 10.3 19.6 7.2"/></svg>',
 'bus'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="11" rx="2"/><path d="M3 11h18M7 16v2M17 16v2"/></svg>',
 'seat'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 9V6a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3"/><path d="M4 11a2 2 0 0 1 2 2v3h12v-3a2 2 0 0 1 4 0v5a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-5a2 2 0 0 1 2-2Z"/><path d="M6 18v2M18 18v2"/></svg>',
 'shield' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 4 5v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V5l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>',
];
?>
<section class="section dark welcome-sec">
  <div class="sec-waves" aria-hidden="true">
    <svg viewBox="0 0 1440 560" preserveAspectRatio="xMidYMid slice">
      <defs>
        <linearGradient id="wGrad" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0" stop-color="#21B9F3" stop-opacity="0"/>
          <stop offset=".45" stop-color="#21B9F3" stop-opacity="1"/>
          <stop offset="1" stop-color="#8fe3ff" stop-opacity="0"/>
        </linearGradient>
        <filter id="wGlow" x="-20%" y="-20%" width="140%" height="140%"><feGaussianBlur stdDeviation="4"/></filter>
      </defs>
      <g fill="none" stroke="rgba(255,255,255,.05)" stroke-width="1.5">
        <path d="M-50,430 C350,330 760,520 1500,330"/>
        <path d="M-50,470 C400,380 820,560 1500,390"/>
        <path d="M-50,510 C450,430 880,590 1500,450"/>
      </g>
      <g class="streak" fill="none" stroke="url(#wGrad)">
        <path d="M-80,90 C380,10 760,250 1520,60" stroke-width="2.5" opacity=".75" filter="url(#wGlow)"/>
        <path d="M-80,140 C320,70 720,300 1520,120" stroke-width="1.6" opacity=".5" filter="url(#wGlow)"/>
        <path d="M-80,60 C420,-10 820,210 1520,30" stroke-width="1.2" opacity=".4" filter="url(#wGlow)"/>
      </g>
    </svg>
  </div>
  <div class="container split">
    <div class="welcome-media reveal" style="background-image:url('<?= asset('image/welcome.jpg') ?>')"></div>
    <div>
      <span class="eyebrow">Welcome to Simple Coach Hire</span>
      <div class="welcome-head"><span class="vbar"></span><h2>Your Journey.<br>Our Commitment.</h2></div>
      <p class="welcome-lead">Reliable and professional coach and minibus hire across the UK. Whether you're planning a corporate event, school trip, airport transfer or a special occasion, we're here to get you there safely, comfortably and on time.</p>
      <div class="benefits">
        <?php foreach ([
          ['pin','Nationwide UK coverage'],['clock','Flexible travel arrangements'],
          ['wheel','Professional, experienced drivers'],['bus','Reliable group transportation'],
          ['seat','Comfortable and spacious vehicles'],['shield','Options for different occasions'],
        ] as $b): ?>
        <div class="benefit"><span class="b-ico"><?= $bsvg[$b[0]] ?></span><span class="b-label"><?= e($b[1]) ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ===== COVERAGE ===== -->
<section class="section soft">
  <div class="container coverage">
    <div>
      <span class="eyebrow">Our Coverage</span>
      <h2>Wherever You Need to Go, We'll Get You There.</h2>
      <p class="lead">From major cities to local destinations, we provide reliable coach and minibus hire across the UK.</p>
      <div class="city-chips">
        <?php foreach ($dest as $d): ?><a class="chip" href="<?= e(nav_url($d['url'])) ?>"><span class="i"><svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg></span><?= e($d['label']) ?></a><?php endforeach; ?>
      </div>
    </div>
    <div class="cov-map reveal" id="covMap">
      <span class="cov-script">Nationwide Coverage</span>
      <img class="cov-img" src="<?= asset('image/coverage-uk.png') ?>" alt="Map of the UK showing our nationwide coach and minibus coverage" loading="lazy">
      <svg class="cov-fx" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
        <?php
        $fx = [
          'M36,34 Q40,31.5 44,34',   // Glasgow – Edinburgh
          'M44,34 Q48,41 50,49',     // Edinburgh – Leeds
          'M36,34 Q42,41 50,49',     // Glasgow – Leeds
          'M50,49 Q48,52 47,55',     // Leeds – Manchester
          'M47,55 Q43.5,55.5 40,57', // Manchester – Liverpool
          'M47,55 Q46,59.5 46,64',   // Manchester – Birmingham
          'M46,64 Q51,68 55,73',     // Birmingham – London
          'M50,49 Q54,60 55,73',     // Leeds – London (long sweep)
        ];
        foreach ($fx as $d) echo '<path class="cov-route" d="'.$d.'"/>';
        foreach ($fx as $i => $d) echo '<path class="cov-flow" pathLength="100" d="'.$d.'" style="animation-delay:'.($i*0.22).'s"/>';
        ?>
      </svg>
      <?php foreach ([
        ['Glasgow','36%','34%','l'],['Edinburgh','44%','34%','r'],['Leeds','50%','49%','r'],
        ['Liverpool','40%','57%','l'],['Manchester','47%','55%','r'],['Birmingham','46%','64%','r'],['London','55%','73%','r'],
      ] as $i => $l): ?>
        <span class="cov-lab<?= $l[3]==='l' ? ' l' : '' ?>" style="left:<?= $l[1] ?>;top:<?= $l[2] ?>;--d:<?= $i*0.35 ?>s"><?= e($l[0]) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== CTA BAND ===== -->
<section class="section cta-section">
  <div class="container">
    <div class="cta-band reveal">
      <div class="cta-bg" aria-hidden="true"><span class="cta-orb o1"></span><span class="cta-orb o2"></span><span class="cta-orb o3"></span></div>
      <div class="cta-inner">
        <div class="cta-copy">
          <span class="eyebrow">Get in Touch</span>
          <h2>Your Next Journey Starts Here.</h2>
          <div class="contact-row">
            <a href="tel:<?= e(preg_replace('/\s+/','',setting('phone',SITE_PHONE))) ?>"><span class="i"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></span><?= e(setting('phone',SITE_PHONE)) ?></a>
            <a href="mailto:<?= e(setting('email',SITE_EMAIL)) ?>"><span class="i"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2.5"/><path d="m22 7-9.1 5.5a2 2 0 0 1-2.06 0L2 7"/></svg></span><?= e(setting('email',SITE_EMAIL)) ?></a>
          </div>
        </div>
        <div class="cta-actions">
          <a class="btn btn-primary btn-lg" href="#book">Get Your Quote →</a>
          <a class="btn btn-ghost btn-lg" href="<?= url('contact-us') ?>">Contact Our Team</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== WHY CHOOSE US ===== -->
<section class="section soft">
  <div class="container">
    <div class="section-head center"><span class="eyebrow">Why Choose Us</span><h2>A Service You Can Rely On</h2></div>
    <div class="grid-4">
      <?php foreach ([
        ['📅','Flexible Hire Periods','From a few hours to several days, we offer flexible hire options to suit your needs.',false],
        ['🚌','Premium Vehicles','Modern, well-maintained coaches and minibuses with top-class comfort and safety.',true],
        ['🧳','Plenty of Space','Travel in comfort with spacious seating, luggage space and plenty of room to relax.',false],
        ['⭐','Optional Extras','Add extras like meet & greet, child seats, WiFi and more to make your journey perfect.',true],
      ] as $c): ?>
      <div class="feature reveal <?= $c[3] ? 'navy' : '' ?>"><div class="ico"><?= $c[0] ?></div><h3><?= e($c[1]) ?></h3><p><?= e($c[2]) ?></p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section soft testi-section">
  <div class="container testi-grid">
    <div class="testi-head">
      <span class="eyebrow">Testimonials</span>
      <h2>What Our Customers Say</h2>
      <p class="lead">Trusted by groups, businesses and travellers across the UK.</p>
      <div class="testi-nav">
        <button class="testi-arrow" id="testiPrev" type="button" aria-label="Previous review"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg></button>
        <button class="testi-arrow" id="testiNext" type="button" aria-label="Next review"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></button>
      </div>
    </div>
    <div class="swiper testiSwiper">
      <div class="swiper-wrapper">
        <?php foreach ([
          'Thanks. Lovely drivers. I defo be rebooking use in future and will shar simple coach hire.',
          'Lovely clean vehicle and funny driver. Was a real pleasure to travel the journey with your company. All the best for the future.',
          'Driver was lovely and on time. No complaints but praise. Thanks a Mil.',
          'Booked a coach last minute to travel from Nottingham to London. The service was very professional and would use again. Thanks Guys.',
          'Thank you for helping me with an urgent coach request. Was let down last minute by another provider but Simple assured me and provided a lovely vehicle. Thanks for all your help.',
          'I would highly recommend this minibus company. First class service from start to finish. Minibus was on time and the driver was very friendly and professional.',
        ] as $t): ?>
        <div class="swiper-slide">
          <div class="testi">
            <div class="stars">★★★★★</div>
            <blockquote>“<?= e($t) ?>”</blockquote>
            <div class="who"><div class="avatar">★</div><div><b>Verified customer</b><small>Simple Coach Hire</small></div></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div>
</section>

<!-- ===== READY TO EXPLORE (CTA) ===== -->
<?php
$phoneTel = preg_replace('/\s+/', '', setting('phone', SITE_PHONE));
$svgStar  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.6 5.6 6.1.6-4.6 4.1 1.3 6L12 16.9 6.6 19.3l1.3-6L3.3 9.2l6.1-.6L12 3Z"/></svg>';
$svgPhone = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>';
?>
<section class="section explore-sec">
  <div class="sec-waves" aria-hidden="true">
    <svg viewBox="0 0 1440 560" preserveAspectRatio="xMidYMid slice">
      <g fill="none" stroke="rgba(255,255,255,.05)" stroke-width="1.5">
        <path d="M-50,120 C350,30 760,230 1500,60"/><path d="M-50,170 C400,90 820,290 1500,130"/>
      </g>
    </svg>
  </div>
  <div class="container explore-grid">
    <div class="explore-content">
      <span class="explore-badge"><?= $bsvg['shield'] ?>Start Your Journey</span>
      <h2>Ready to Explore the <span class="accent">UK</span>?</h2>
      <p class="explore-lead">Get a personalised quote for <span class="accent">UK coach hire</span> from us and travel across the country with comfort, safety and style.</p>
      <div class="feature-cards">
        <div class="fcard"><span class="fc-ico ico-cyan"><?= $bsvg['pin'] ?></span><div><b>50+ Cities Covered</b><small>Nationwide UK coach hire from London</small></div></div>
        <div class="fcard"><span class="fc-ico ico-green"><?= $bsvg['clock'] ?></span><div><b>24/7 Service</b><small>Always available for your coach hire needs</small></div></div>
        <div class="fcard"><span class="fc-ico ico-gold"><?= $svgStar ?></span><div><b>4.9/5 Rating</b><small>Trusted by thousands across the UK</small></div></div>
      </div>
      <div class="explore-actions">
        <a class="btn btn-lg btn-cta" href="#book">Get Free Quote <span class="btn-arrow">→</span></a>
        <a class="btn btn-lg btn-call" href="tel:<?= e($phoneTel) ?>"><?= $svgPhone ?> Call <?= e(setting('phone', SITE_PHONE)) ?></a>
      </div>
    </div>
    <div class="explore-visual reveal">
      <div class="ev-card" style="background-image:url('<?= asset('image/welcome.jpg') ?>')"></div>
      <div class="float-stat fs-top"><span class="fs-ico"><?= $bsvg['bus'] ?></span><div><b>50+</b><small>Vehicles</small></div></div>
      <div class="float-stat fs-bot"><span class="fs-ico green"><?= $bsvg['shield'] ?></span><div><b>100%</b><small>Insured</small></div></div>
    </div>
  </div>
</section>

<!-- ===== CITY LIST ===== -->
<section class="section soft citylist-section">
  <div class="container">
    <div class="citylist-head">
      <span class="eyebrow">Explore Our Destinations</span>
      <h2>UK Cities We Cover</h2>
    </div>
    <div class="citylist-row">
      <?php foreach ($dest as $d): ?><a class="city-link" href="<?= e(nav_url($d['url'])) ?>"><span class="i"><svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.9-3.1-7-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg></span><?= e($d['label']) ?></a><?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/booking-modal.php'; ?>
<?php require __DIR__ . '/partials/footer.php'; ?>
