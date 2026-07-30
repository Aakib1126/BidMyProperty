<<<<<<< HEAD
# BidMyProperty — Decoupled Architecture

This is a full rewrite of the original monolithic PHP app into three
independent layers:

```
BidMyProperty/
├── frontend/            Pure HTML + CSS + JavaScript (no PHP at all)
│   ├── pages/              24 .html pages (one per feature)
│   ├── js/                  Matching JavaScript per page, + shared api.js / nav.js
│   ├── css/                  Stylesheets (unchanged from the original project)
│   ├── assets/                Static images / sample video
│   └── legacy/                3 old unused prototype files, kept for reference
├── backend/
│   ├── api/                 27 PHP files — pure JSON APIs, zero HTML output
│   └── uploads/               Runtime folder for user-uploaded images/videos/documents
└── database/
    ├── db.sql                 Schema — import this first
    └── db_connect.php         DB connection settings
```

The frontend talks to the backend **only** via `fetch()` calls to
`backend/api/*.php`, which all return JSON. No PHP file outputs HTML anymore.

## How it works

- **`frontend/js/api.js`** — shared helpers (`apiGet`, `apiPost`, `apiPostForm`)
  that every page uses to call the backend.
- **`frontend/js/nav.js`** — calls `backend/api/session_status.php` on page load
  to decide whether to show Login/Register or Dashboard/Logout links.
- **Sessions** — still handled by PHP's normal `session_start()` + cookies.
  Since frontend and backend are served from the same site (just different
  folders), this works with plain same-origin `fetch()` — no CORS setup needed.
- **File uploads** (`upload_property.html`, `property-update.html`) use
  `FormData` + `apiPostForm()` so multipart uploads still work.

## How to run it

1. Install **XAMPP** (or MAMP/WAMP) — https://www.apachefriends.org
2. Copy the whole `BidMyProperty` folder into `htdocs` (XAMPP) or `www` (WAMP/MAMP).
3. Start Apache and MySQL from the control panel.
4. In phpMyAdmin (http://localhost/phpmyadmin), create a database named
   `bidmyproperty` and import `database/db.sql` into it.
5. Open `database/db_connect.php` and check the username/password match your
   MySQL setup (a fresh XAMPP install is usually `root` with an empty password).
6. Visit: **http://localhost/BidMyProperty/frontend/pages/index.html**

## Honest notes on scope and limitations

- **This was a substantial rewrite**, not a simple reorganization — every one
  of the 27 original pages had its HTML-rendering logic separated from its
  PHP/database logic. I traced each page's original behavior carefully, but
  I don't have PHP/MySQL available in my own environment to actually execute
  and click through the app before handing it to you. Test it and tell me
  the exact error if something doesn't work — I can fix it fast once I know
  what broke.
- **`manage.css` and `upload_style.css`** were referenced by two original
  pages but never existed in your original upload — not something this
  rewrite broke. Those two pages will load unstyled until those files exist.
- **`payment.php`** was already a non-functional demo in the original project
  (it references a Stripe/PayPal SDK via Composer that was never installed,
  with placeholder API keys). The new `backend/api/payment.php` preserves
  that structure but now fails with a clear JSON error instead of a blank
  white-screen crash, and explains what's needed to make it real.
- **`backend/api/place_bid.php`** exists (converted from the original) but,
  like in the original project, nothing actually links to it — `userbid.php`,
  `real-timebidding.php`, and `property_details.php` each handle their own
  bid submissions directly. Kept for completeness/reference.
- I fixed one clear bug while converting `my_uploaded_properties.php`: the
  original query compared a property's `id` to the logged-in user's `id`
  (`WHERE id = '$user_id'`), which would almost never return the user's own
  properties. It now correctly filters `WHERE user_id = '$user_id'`. Flagging
  this explicitly rather than silently changing behavior.
- The nav bar is now consistent across all pages (rendered by `nav.js`)
  rather than each page hand-rolling slightly different nav HTML like the
  original did — a small deliberate simplification for consistency.
=======
# BidMyProperty
BidMyProperty — Bid. Buy. Own. Easily.

• Engineered a full-stack property listing and bidding platform supporting buyer-seller interactions from search to bid 
submission.

• Designed responsive, mobile-first UI using HTML, CSS, and vanilla JavaScript for cross-device usability.

• Implemented PHP-MySQL backend for property records, bid history, and secure data transactions.

• Prioritised input validation and structured data handling to prevent common injection vulnerabilities.

Tech Stack: HTML, CSS, JavaScript, PHP, MySQL
>>>>>>> 034dc77822ac0f6d1cd46992806fa28bf9f8ce27
