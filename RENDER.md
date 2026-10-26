# Path 2 — Website on Render + CRM on cPanel + one shared cloud MySQL

Three pieces, one database:

```
   ┌─────────────────────┐        ┌─────────────────────┐
   │  Website (Render)   │        │   CRM (cPanel)      │
   │  Docker PHP+Apache  │        │  ihwtest.…co.in     │
   └──────────┬──────────┘        └──────────┬──────────┘
              │         both connect over TLS │
              └───────────────┬───────────────┘
                              ▼
                   ┌─────────────────────┐
                   │  Cloud MySQL (free) │  Aiven / TiDB
                   │  internet-reachable │  holds ALL the data
                   └─────────────────────┘
```

The website and CRM **share this one cloud database**, so content you edit in the CRM
shows on the site, and bookings from the site appear in the CRM.

Everything is already wired for this: the `Dockerfile`, `render.yaml`, env‑driven
`config.php`, and TLS + custom‑port support in `db.php` (cloud MySQL needs both).

---

## Step 1 — Create the free cloud MySQL (Aiven recommended)
1. Sign up at **aiven.io** → **Create service** → **MySQL** → choose the **Free** plan.
2. Wait until it's "Running", then open the service's **Overview** → **Connection information**.
   Note: **Host**, **Port**, **User** (`avnadmin`), **Password**, **Database** (`defaultdb`).
3. Download the **CA certificate** (`ca.pem`) from that same page — you'll need it for TLS.

> TiDB Cloud Serverless works too (MySQL‑compatible, free). Use its host/port (4000)/user/pass.
> Either way the connection is **TLS‑only**, which is why Step 4 ships the CA cert.

## Step 2 — Import your data into the cloud DB
Import `../database/simplecoachhire_new.sql` into the cloud database (it's just tables +
data, no `CREATE DATABASE`, so it drops straight into `defaultdb`). Two easy ways:

- **MySQL Workbench / DBeaver / HeidiSQL:** connect with the Step‑1 details (enable SSL),
  then run the `.sql` file.
- **Command line:**
  ```bash
  mysql --host=HOST --port=PORT --user=avnadmin --password=PASS \
        --ssl-mode=REQUIRED defaultdb < database/simplecoachhire_new.sql
  ```

## Step 3 — Put this `website/` folder on GitHub
Push so that `Dockerfile`, `render.yaml` and `index.php` are at the **repo root**:
```bash
cd website
git init && git add . && git commit -m "Simple Coach Hire website"
git branch -M main
git remote add origin https://github.com/<you>/<repo>.git
git push -u origin main
```
(If you push the whole `deploy/` folder instead, set Render's **Root Directory** to `website`.)

## Step 4 — Ship the CA cert (for TLS)
Put Aiven's `ca.pem` into **`website/app/ca.pem`** before pushing (the Dockerfile copies it
into the image, and `db.php` auto‑detects `app/ca.pem`). It's a public CA cert, safe to commit.

> Prefer not to commit it? Skip this and instead set the env var `DB_SSL=1` in Step 6
> (encrypts the link without a cert file). The `ca.pem` way is a bit more secure.

## Step 5 — Create the Render service
Render → **New +** → **Blueprint** → pick your repo → it reads `render.yaml`.
(Or **New + → Web Service → Docker** and point it at the repo.)

## Step 6 — Set the environment variables (Render → service → Environment)
| Key | Value |
|---|---|
| `DB_HOST` | your Aiven host, e.g. `mysql-xxxx.aivencloud.com` |
| `DB_PORT` | the Aiven port (e.g. `12345`) |
| `DB_NAME` | `defaultdb` |
| `DB_USER` | `avnadmin` |
| `DB_PASS` | the Aiven password |
| `DB_SSL` | `1` (needed; or omit if you shipped `app/ca.pem`) |
| `SITE_URL` | `https://<your-service>.onrender.com` (or your domain) |
| `API_KEY` | your **rotated** transport‑io token |
| `STRIPE_SECRET_KEY` | your **rotated** `sk_live…` key |
| `GOOGLE_MAPS_KEY` | your Google Maps browser key |
| `BOOKING_BASE_URL` | `https://booking.simplecoachhire.com` |

Deploy. Apache listens on Render's `$PORT` automatically. Open the `.onrender.com` URL —
you should see real pages (not a DB error).

## Step 7 — Point the cPanel CRM at the SAME cloud DB
On the cPanel where the CRM lives (`ihwtest.…co.in`):
1. Re‑upload the updated **`crm/app/db.php`** (it now supports custom port + TLS).
2. Upload Aiven's **`ca.pem`** to **`crm/app/ca.pem`**.
3. Edit **`crm/app/config.php`** DB block to the Aiven values:
   ```php
   const DB_HOST = 'mysql-xxxx.aivencloud.com';
   const DB_PORT = '12345';
   const DB_NAME = 'defaultdb';
   const DB_USER = 'avnadmin';
   const DB_PASS = 'your-aiven-password';
   const DB_SSL  = true;   // (ca.pem is auto-detected; this just forces TLS)
   ```
4. Reload the CRM → it's now reading/writing the shared cloud DB. Edits here appear on the
   Render site immediately.

## Step 8 — Go‑live checklist
- [ ] Render site loads real pages (DB reachable over TLS).
- [ ] CRM on cPanel points at the same cloud DB (make a test edit → see it on the site).
- [ ] Rotated Stripe / transport‑io / Google‑Maps keys set as Render env vars (not in code).
- [ ] `robots.txt` → `Sitemap:` line points to your live domain + `/sitemap.xml`.
- [ ] Custom domain + HTTPS added in Render → Settings → Custom Domains; update `SITE_URL`.
- [ ] Add fleet photos (`fleet-70.jpg` etc.) under `assets/image/` if missing.

---

## Heads‑up about Render's free plan
Free web services **sleep after ~15 min idle** and take ~30–60s to wake on the next visit.
That's fine for testing, but for a live business site (and SEO) use a paid Render instance,
or host the site on cPanel alongside the CRM (see `../DEPLOY.md`). The free **Aiven** DB is
fine to keep either way.

## Troubleshooting
- **"Connections using insecure transport are prohibited"** → TLS isn't on. Set `DB_SSL=1`
  (Render) / `const DB_SSL = true;` (CRM), or make sure `app/ca.pem` is present.
- **"SQLSTATE[HY000] [2002]" / timeout** → wrong `DB_HOST`/`DB_PORT`, or the DB service isn't
  running. Re‑check the Aiven connection details.
- **"Access denied for user"** → wrong `DB_USER`/`DB_PASS`, or importing hasn't been done yet.
- **Site loads but content is empty** → the `.sql` wasn't imported into this DB (Step 2).
