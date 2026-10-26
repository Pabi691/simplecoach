/* Simple Coach Hire — front-end interactions */
(function () {
  'use strict';
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  /* ---- Header solid on scroll ---- */
  const header = $('#siteHeader');
  const onScroll = () => header && header.classList.toggle('solid', window.scrollY > 40);
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

  /* ---- Mobile nav ---- */
  const mob = $('#mobileNav');
  $('#navToggle')?.addEventListener('click', () => mob.classList.add('open'));
  $('#navClose')?.addEventListener('click', () => mob.classList.remove('open'));
  $$('#mobileNav a').forEach(a => a.addEventListener('click', () => mob.classList.remove('open')));

  /* ---- Lenis smooth scroll ---- */
  let lenis = null;
  if (window.Lenis) {
    lenis = new Lenis({ duration: 1.05, smoothWheel: true });
    const raf = t => { lenis.raf(t); requestAnimationFrame(raf); };
    requestAnimationFrame(raf);
  }
  /* Smooth anchor to #book */
  $$('a[href*="#book"]').forEach(a => a.addEventListener('click', e => {
    const t = $('#book'); if (!t) return;
    e.preventDefault();
    lenis ? lenis.scrollTo(t, { offset: -90 }) : t.scrollIntoView({ behavior: 'smooth' });
  }));

  /* ---- GSAP reveals ---- */
  if (window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);
    if (lenis) lenis.on('scroll', ScrollTrigger.update);

    // Scroll-triggered reveals (staggered)
    ScrollTrigger.batch('.reveal', {
      start: 'top 88%',
      onEnter: els => gsap.to(els, { opacity: 1, y: 0, duration: .9, stagger: .09, ease: 'power3.out', overwrite: true }),
    });
    gsap.set('.reveal', { opacity: 0, y: 28 });

    // Cinematic hero load-in (the title/subtitle are handled by the slider below)
    const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
    tl.from('.hero-badges', { y: 18, opacity: 0, duration: .7 }, .15)
      .from('.quote-bar', { y: 28, opacity: 0, duration: .85 }, '-=.4')
      .from('.hero-contact', { y: 18, opacity: 0, duration: .7 }, '-=.5')
      .from('.scroll-cue', { opacity: 0, duration: .8 }, '-=.3');

    // Parallax: hero foreground drifts up as you scroll
    gsap.to('.hero .container', {
      yPercent: 24, opacity: .55, ease: 'none',
      scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true },
    });
    // Subtle parallax on dark-section media images
    $$('.split .media, .cta-band').forEach(el => {
      gsap.fromTo(el, { backgroundPositionY: '42%' }, {
        backgroundPositionY: '58%', ease: 'none',
        scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true },
      });
    });

    // Animated UK map routes + pulsing dots
    const routes = $$('.route');
    if (routes.length) {
      ScrollTrigger.create({
        trigger: '.map-wrap', start: 'top 80%', once: true,
        onEnter: () => {
          gsap.to('.route', { strokeDashoffset: 0, duration: 1.6, stagger: .3, ease: 'power2.inOut' });
          gsap.fromTo('.map-dot',
            { scale: 0, opacity: 0, transformOrigin: 'center' },
            { scale: 1, opacity: 1, duration: .5, stagger: .12, ease: 'back.out(2)', delay: .3 });
          gsap.to('.map-dot', { scale: 1.35, repeat: -1, yoyo: true, duration: 1.1, stagger: .2, delay: 1.2, ease: 'sine.inOut' });
        },
      });
    }
  } else {
    $$('.reveal').forEach(el => { el.style.opacity = 1; el.style.transform = 'none'; });
  }

  /* ---- Swiper carousels ---- */
  if (window.Swiper) {
    if ($('.fleetSwiper')) new Swiper('.fleetSwiper', {
      slidesPerView: 1.1, spaceBetween: 24, grabCursor: true,
      pagination: { el: '.fleetSwiper .swiper-pagination', clickable: true },
      breakpoints: { 640: { slidesPerView: 2 }, 992: { slidesPerView: 3 } },
    });
    if ($('.testiSwiper')) new Swiper('.testiSwiper', {
      slidesPerView: 1.08, spaceBetween: 20, loop: true, grabCursor: true,
      autoplay: { delay: 6000, disableOnInteraction: false },
      pagination: { el: '.testiSwiper .swiper-pagination', clickable: true },
      navigation: { prevEl: '#testiPrev', nextEl: '#testiNext' },
      breakpoints: { 680: { slidesPerView: 1.15 }, 1100: { slidesPerView: 1.25 } },
    });
  }

  /* ---- Auto-rotating hero text slider ---- */
  const heroSlides = $$('#heroSlider .hero-slide');
  if (heroSlides.length > 1) {
    let hi = 0;
    setInterval(() => {
      heroSlides[hi].classList.remove('active');
      hi = (hi + 1) % heroSlides.length;
      heroSlides[hi].classList.add('active');
    }, 5500);
  }

  /* ---- Google Maps autocomplete + distance (optional) ---- */
  function initMaps() {
    if (!window.google || !google.maps || !google.maps.places) return;
    const bias = { componentRestrictions: { country: 'gb' } };
    const bind = (inputId, latId, lngId) => {
      const el = document.getElementById(inputId); if (!el) return null;
      const ac = new google.maps.places.Autocomplete(el, bias);
      ac.addListener('place_changed', () => {
        const p = ac.getPlace();
        if (p.geometry) {
          document.getElementById(latId).value = p.geometry.location.lat();
          document.getElementById(lngId).value = p.geometry.location.lng();
          calcDistance();
        }
      });
      return ac;
    };
    bind('pickup_location', 'pickup_latitude', 'pickup_longitude');
    bind('drop_location', 'dropoff_latitude', 'dropoff_longitude');
    bind('s_pickup_location', 's_pickup_latitude', 's_pickup_longitude');
    bind('s_drop_location', 's_dropoff_latitude', 's_dropoff_longitude');
  }
  function calcDistance() {
    const pl = $('#pickup_latitude')?.value, pn = $('#pickup_longitude')?.value;
    const dl = $('#dropoff_latitude')?.value, dn = $('#dropoff_longitude')?.value;
    if (!window.google || !pl || !dl) return;
    new google.maps.DistanceMatrixService().getDistanceMatrix({
      origins: [new google.maps.LatLng(+pl, +pn)], destinations: [new google.maps.LatLng(+dl, +dn)],
      travelMode: 'DRIVING', unitSystem: google.maps.UnitSystem.IMPERIAL,
    }, (res, status) => {
      if (status === 'OK') {
        const d = res.rows[0].elements[0];
        if (d.status === 'OK') $('#distance').value = d.distance.text;
      }
    });
  }
  if (window.SCH_MAPS_KEY) {
    const s = document.createElement('script');
    s.src = 'https://maps.googleapis.com/maps/api/js?key=' + window.SCH_MAPS_KEY + '&libraries=places&callback=__schMaps';
    s.async = true; window.__schMaps = initMaps; document.head.appendChild(s);
  }

  /* ---- Booking flow: quick form (hero OR sticky bar) -> modal -> submit ---- */
  const modal = $('#bookingModal');
  const bForm = $('#bookingForm');
  const STEP1 = ['pickup_location','pickup_latitude','pickup_longitude',
                 'dropoff_location','dropoff_latitude','dropoff_longitude','pickup_date','distance'];

  function openBookingFrom(form) {
    const get = n => form.querySelector(`[name="${n}"]`)?.value || '';
    STEP1.forEach(n => { const dst = bForm.querySelector(`[name="${n}"]`); if (dst) dst.value = get(n); });
    $('#bmSummary').innerHTML =
      `<b>${get('pickup_location') || '—'}</b> → <b>${get('dropoff_location') || '—'}</b>` +
      (get('pickup_date') ? `<br>Pickup: ${get('pickup_date')}` : '');
    modal.classList.add('open'); modal.setAttribute('aria-hidden', 'false');
  }

  // both the hero quote form and the sticky bar open the same details step
  ['#quoteForm', '#quoteFormSticky'].forEach(sel => {
    const f = $(sel);
    f?.addEventListener('submit', e => { e.preventDefault(); if (!f.reportValidity()) return; openBookingFrom(f); });
  });

  // show the sticky quote bar once the hero form scrolls out of view
  const sticky = $('#stickyQuote'), heroEl = $('#hero');
  if (sticky && heroEl) {
    const onScrollSticky = () => sticky.classList.toggle('show', window.scrollY > heroEl.offsetHeight - 130);
    onScrollSticky(); window.addEventListener('scroll', onScrollSticky, { passive: true });
  }
  $('#bmClose')?.addEventListener('click', () => modal.classList.remove('open'));
  modal?.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });

  $('#journey_type')?.addEventListener('change', e => {
    const show = e.target.value === 'Return';
    $$('.return-only').forEach(el => el.style.display = show ? '' : 'none');
  });

  /* ---- Parked-bus headlights blink when a quote field is clicked/focused ---- */
  (function () {
    const hero = $('#hero'), qf = $('#quoteForm');
    if (!hero || !qf) return;
    const flash = () => {
      hero.classList.add('lights-on');
      hero.classList.remove('blink'); void hero.offsetWidth; hero.classList.add('blink'); // restart blink
    };
    qf.querySelectorAll('input, select').forEach(el => {
      el.addEventListener('focus', flash);
      el.addEventListener('mousedown', flash);   // re-blink even if already focused
      el.addEventListener('blur', () => {
        setTimeout(() => { if (!qf.contains(document.activeElement)) hero.classList.remove('lights-on', 'blink'); }, 60);
      });
    });
  })();

  bForm?.addEventListener('submit', async e => {
    e.preventDefault();
    if (!bForm.reportValidity()) return;
    const btn = $('#bmSubmit'), msg = $('#bmMsg');
    btn.disabled = true; btn.textContent = 'Sending…'; msg.className = 'bm-msg'; msg.textContent = '';
    try {
      const res = await fetch(window.SCH_BASE + '/booking.php', { method: 'POST', body: new FormData(bForm) });
      const data = await res.json();
      if (data.status) {
        msg.className = 'bm-msg ok'; msg.textContent = 'Thank you! Redirecting to your quote…';
        // Google Ads conversion (kept from the original site)
        if (window.gtag) gtag('event', 'conversion', { send_to: 'AW-11230624893/pOYUCLTy_7AYEP34lusp' });
        setTimeout(() => { window.location.href = data.redirect; }, 900);
      } else {
        msg.className = 'bm-msg err'; msg.textContent = data.message || 'Something went wrong. Please try again.';
        btn.disabled = false; btn.textContent = 'Get My Quote →';
      }
    } catch (err) {
      msg.className = 'bm-msg err'; msg.textContent = 'Network error. Please try again.';
      btn.disabled = false; btn.textContent = 'Get My Quote →';
    }
  });
})();
