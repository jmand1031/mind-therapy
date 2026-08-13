# Mind Therapy — WordPress build notes

Live build: https://mindtherapyny.wpcomstaging.com (WordPress.com Business plan, Atomic).
Last updated: August 13, 2026.

## What's in this repo

| File | What it is |
|---|---|
| `index.html` | Original single-file prototype (Brightside/Talkspace-inspired, light palette) |
| `warm-redesign.html` | Warm redesign prototype (butter/blush palette, Fredoka type, SVG characters) — approved and applied to the live site |
| `wordpress/additional-css.css` | The full Additional CSS stylesheet from the live site's global styles |
| `wordpress/practitioner-bookings-dashboard.php` | WPCode snippet: staff-only week-grid dashboard (`[mt_practitioner_bookings]`) |
| `wordpress/calendar-chip-labels.php` | WPCode snippet: booking-calendar chips show patient / practitioner / time |
| `wordpress/seo-meta-structured-data.php` | WPCode snippet: meta descriptions + MedicalBusiness / FAQPage / Person JSON-LD |
| `debate-log.md` | The original 3-persona, 5-round design debate record |

## Live site architecture

- **Pages:** Home (6), About (1), Our Services (100), Our Specialties (101), Our Therapists (13) with 13 child pages (one per clinician, e.g. `/our-therapists/ally-nektalov/`), Blog (40), Book (29), FAQ (193), How Online Therapy Works (192), Careers (146), Contact (102), Practitioner Bookings (202, private).
- **Design:** warm palette (butter #FBF1E0, toast #3E3128, spruce #2E5A50, clay #D26E4B, honey #EBAE5F), Fredoka headings, arched photos, hand-drawn SVG character icons (embedded as data URIs in page content).
- **Navigation:** Home · About Us · Our Services · Our Specialties · Therapists (dropdown) · Blog · Careers · Contact, plus a "Book a Consultation" button.
- **Booking:** Booking Calendar (free) — single-day calendar, hourly time slots, practitioner select with stable IDs (P00–P13), required intake fields (name, email, phone, DOB, presenting issue, insurance plan, member ID), "Request to Book" pending-approval flow. Admin notifications to info@mindtherapyny.com.
- **Careers:** Jetpack form with résumé upload, applications emailed to info@mindtherapyny.com.
- **Blog:** stacked full-post feed with comments; weekly draft workflow runs Mondays 9 AM via a local scheduled task (writes a `[AWAITING APPROVAL]` draft + prepares a Gmail approval draft to Ally).

## Known limitations / next steps

- Site is unlaunched; real domain (mindtherapyny.com) not yet connected.
- Per-practitioner availability calendars require Booking Calendar Pro ("Providers" feature).
- Placeholder phone number and insurance list need client confirmation.
- Google Business Profile not yet created (client action).
