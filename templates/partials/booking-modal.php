<?php /** Step-2 booking details modal (opens after the 3-field hero quote form). */ ?>
<div class="sch-modal" id="bookingModal" aria-hidden="true">
  <div class="sch-modal-card">
    <button class="sch-modal-close" id="bmClose" aria-label="Close">×</button>
    <h3>Just a few more details</h3>
    <p style="color:var(--muted);margin-top:-6px">Tell us about your trip and where to send your quote.</p>
    <form id="bookingForm">
      <div class="bm-summary" id="bmSummary"></div>
      <div class="bm-grid">
        <div class="bm-field"><label>Journey Type</label>
          <select name="journey_type" id="journey_type"><option value="One Way">One Way</option><option value="Return">Return</option></select></div>
        <div class="bm-field"><label>Passengers</label><input type="number" min="1" max="100" name="passengers" required></div>
        <div class="bm-field"><label>Pickup Time</label><input type="time" name="pickup_time"></div>
        <div class="bm-field return-only" style="display:none"><label>Return Date</label><input type="date" name="return_date"></div>
        <div class="bm-field return-only" style="display:none"><label>Return Time</label><input type="time" name="return_time"></div>
        <div class="bm-field bm-full" style="border-top:1px solid var(--line);margin-top:4px;padding-top:14px"><label>Full Name</label><input type="text" name="name" required></div>
        <div class="bm-field"><label>Email</label><input type="email" name="booking_email" required></div>
        <div class="bm-field"><label>Mobile Number</label><input type="tel" name="booking_mobile" required></div>
        <div class="bm-field"><label>Small Luggage</label><input type="number" min="0" name="small_luggage" value="0"></div>
        <div class="bm-field"><label>Medium Luggage</label><input type="number" min="0" name="medium_luggage" value="0"></div>
        <div class="bm-field"><label>Large Luggage</label><input type="number" min="0" name="large_luggage" value="0"></div>
      </div>
      <!-- carried over from the hero quote form (step 1) -->
      <input type="hidden" name="pickup_location"><input type="hidden" name="pickup_latitude"><input type="hidden" name="pickup_longitude">
      <input type="hidden" name="dropoff_location"><input type="hidden" name="dropoff_latitude"><input type="hidden" name="dropoff_longitude">
      <input type="hidden" name="pickup_date"><input type="hidden" name="distance">
      <button class="btn btn-primary btn-lg btn-cta" type="submit" id="bmSubmit" style="width:100%;justify-content:center;margin-top:12px">Get My Quote <span class="btn-arrow">→</span></button>
      <p class="bm-msg" id="bmMsg"></p>
    </form>
  </div>
</div>

<style>
.sch-modal{position:fixed;inset:0;z-index:300;background:rgba(7,21,45,.72);backdrop-filter:blur(6px);
  display:none;align-items:flex-start;justify-content:center;padding:40px 16px;overflow-y:auto}
.sch-modal.open{display:flex}
.sch-modal-card{background:#fff;border-radius:22px;max-width:640px;width:100%;padding:34px;position:relative;box-shadow:var(--shadow)}
.sch-modal-close{position:absolute;top:16px;right:18px;background:none;border:0;font-size:1.9rem;cursor:pointer;color:var(--muted)}
.bm-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin:16px 0}
.bm-full{grid-column:1 / -1}
.bm-field{display:flex;flex-direction:column}
.bm-field label{font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);margin-bottom:6px;font-weight:600}
.bm-field input,.bm-field select{width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:11px;font-family:inherit;font-size:.95rem;background:#fff}
.bm-field input:focus,.bm-field select:focus{outline:none;border-color:var(--cyan)}
.bm-summary{background:var(--bg-soft);border-radius:12px;padding:14px 16px;font-size:.9rem;color:var(--muted);margin-bottom:4px}
.bm-msg{text-align:center;margin-top:12px;font-weight:600}
.bm-msg.ok{color:#128a4a}.bm-msg.err{color:#c62828}
@media(max-width:560px){.bm-grid{grid-template-columns:1fr}}
</style>
