# 8-minute demonstration script

**0:00–0:40 — Intro:** Introduce UserHub, technology stack, objective and key features. Show mobile/desktop layout.

**0:40–1:25 — Database:** Open `database/schema.sql` and show `roles` and `users` in phpMyAdmin. Explain foreign key and 1NF/2NF/3NF.

**1:25–2:25 — Registration and login:** Register a new account; demonstrate duplicate-email validation, wrong password, successful login and session-protected dashboard.

**2:25–3:40 — CRUD:** Log in as admin, open Manage Users, create a new user, search, edit name/role, then delete and confirm.

**3:40–4:40 — Roles:** Show the user role cannot visit `users.php` and that only admins see the admin link.

**4:40–5:45 — Profile:** Update name/email, upload a valid photo, demonstrate oversized/unsupported image rejection, and save.

**5:45–6:50 — Security:** Show `password_hash`/`password_verify`, prepared statements, CSRF form fields, server-side validation and escaped HTML output. Note uploads are randomly named.

**6:50–7:30 — Responsive UI:** Resize browser to mobile and demonstrate responsive navigation, cards, and form transitions.

**7:30–8:00 — Conclusion:** Show GitHub repository and README; recap features and future improvements (rate limiting, email verification, password reset).

**Recording tip:** Use OBS Studio or your platform screen recorder. Do not show real passwords, API secrets, or personal data.
