<?php
/**
 * Booking submission endpoint.
 * 1) Saves the lead into YOUR CRM (bookings table).
 * 2) If FORWARD_TO_TRANSPORT_IO, forwards to connect.transport-io.com/api/create-booking
 *    to obtain a booking_token so the existing quote + Stripe flow keeps working.
 * Returns JSON: { status, booking_token, redirect }.
 */
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/helpers.php';

if (!defined('WEB_BASE')) define('WEB_BASE', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));

header('Content-Type: application/json; charset=utf-8');

function field($k) { return isset($_REQUEST[$k]) ? trim((string)$_REQUEST[$k]) : null; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => false, 'message' => 'POST required']); exit;
}

$data = [
    'name'             => field('name'),
    'email'            => field('booking_email'),
    'mobile'           => field('booking_mobile'),
    'pickup_location'  => field('pickup_location'),
    'pickup_lat'       => field('pickup_latitude'),
    'pickup_lng'       => field('pickup_longitude'),
    'pickup_date'      => field('pickup_date'),
    'pickup_time'      => field('pickup_time'),
    'dropoff_location' => field('dropoff_location'),
    'dropoff_lat'      => field('dropoff_latitude'),
    'dropoff_lng'      => field('dropoff_longitude'),
    'journey_type'     => field('journey_type'),
    'return_date'      => field('return_date'),
    'return_time'      => field('return_time'),
    'passengers'       => field('passengers'),
    'small_luggage'    => field('small_luggage'),
    'medium_luggage'   => field('medium_luggage'),
    'large_luggage'    => field('large_luggage'),
    'distance'         => field('distance'),
];

// basic validation
if (!$data['name'] || !$data['email'] || !$data['pickup_location'] || !$data['dropoff_location']) {
    echo json_encode(['status' => false, 'message' => 'Please complete the required fields.']); exit;
}

$ref = 'SCH' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));

// ---- 1) Save into CRM ----
$booking_token = null;
try {
    db_run('INSERT INTO bookings
      (ref,name,email,mobile,pickup_location,pickup_lat,pickup_lng,pickup_date,pickup_time,
       dropoff_location,dropoff_lat,dropoff_lng,journey_type,return_date,return_time,
       passengers,small_luggage,medium_luggage,large_luggage,distance,source,status,ip)
      VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',
      [$ref,$data['name'],$data['email'],$data['mobile'],$data['pickup_location'],$data['pickup_lat'],
       $data['pickup_lng'],$data['pickup_date'],$data['pickup_time'],$data['dropoff_location'],
       $data['dropoff_lat'],$data['dropoff_lng'],$data['journey_type'],$data['return_date'],
       $data['return_time'],$data['passengers'],$data['small_luggage'],$data['medium_luggage'],
       $data['large_luggage'],$data['distance'],'website','new',$_SERVER['REMOTE_ADDR'] ?? null]);
} catch (Throwable $e) {
    echo json_encode(['status' => false, 'message' => 'Could not save booking. Please try again.']); exit;
}

// ---- 2) Forward to transport-io (keeps live quote + Stripe) ----
if (FORWARD_TO_TRANSPORT_IO && API_KEY && !str_starts_with(API_KEY, 'PASTE_')) {
    $payload = [
        'name' => $data['name'], 'booking_email' => $data['email'], 'booking_mobile' => $data['mobile'],
        'pickup_location' => $data['pickup_location'], 'pickup_latitude' => $data['pickup_lat'], 'pickup_longitude' => $data['pickup_lng'],
        'pickup_date' => $data['pickup_date'], 'pickup_time' => $data['pickup_time'],
        'dropoff_location' => $data['dropoff_location'], 'dropoff_latitude' => $data['dropoff_lat'], 'dropoff_longitude' => $data['dropoff_lng'],
        'passengers' => $data['passengers'], 'small_luggage' => $data['small_luggage'],
        'medium_luggage' => $data['medium_luggage'], 'large_luggage' => $data['large_luggage'],
        'journey_type' => $data['journey_type'], 'distance' => $data['distance'],
        'return_date' => $data['return_date'], 'return_time' => $data['return_time'],
    ];
    $ch = curl_init(API_BASE_URL . '/create-booking');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $payload, CURLOPT_TIMEOUT => 20,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . API_KEY],
    ]);
    $resp = curl_exec($ch);
    curl_close($ch);
    $j = json_decode((string)$resp);
    if ($j && !empty($j->status) && !empty($j->booking_token)) {
        $booking_token = $j->booking_token;
        db_run('UPDATE bookings SET booking_token=?, status="quoted" WHERE ref=?', [$booking_token, $ref]);
    }
}

// ---- Response ----
$redirect = $booking_token
    ? rtrim(BOOKING_BASE_URL, '/') . '/b-status?b=' . urlencode($booking_token)
    : url('contact-us?submitted=1');

echo json_encode([
    'status'        => true,
    'ref'           => $ref,
    'booking_token' => $booking_token,
    'redirect'      => $redirect,
]);
