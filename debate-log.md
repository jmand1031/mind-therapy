# Mind Therapy NY Prototype — Panel Review & 5-Round Debate Log

**Panel:**
- **Maya Chen** — Senior UX designer, healthcare/therapy specialization
- **Dev Okafor** — WordPress engineer + technical SEO/AEO specialist
- **Patricia Vance** — Practice executive (KPI: booked consultations)

Each persona independently critiqued draft v1, then debated the contested points. Rule: unanimous agreement → change applied; disagreement → continued debate until consensus grounded in logic and what attracts the most clients.

---

## Round 1 — Initial critiques & unanimous items

All three reviewers independently flagged the same critical defects, so these were applied without debate:

1. **The booking funnel was broken.** Every "Book" CTA pointed to `#book`, which was an anchor on *another button* — the funnel dead-ended at a link that scrolled to itself, and the final CTA scrolled back to the top. → **Applied:** a dedicated booking section with a 3-field mini-form (name, contact, insurance plan), a "What you get in your free 15 minutes" value card, and a note marking where the live scheduler embed goes in the WordPress build. Every CTA now lands there.
2. **No phone number anywhere.** Parents booking for teens, older clients, and people in distress convert by phone. → **Applied:** clickable phone in the header, booking section, CTA band, footer, and sticky bar (placeholder number, flagged for replacement with a trackable line).
3. **"Make a change today" CTA copy killed.** Vague and mildly pressuring — wrong tone for anxious visitors. → **Applied:** one canonical label everywhere: "Book a free consultation."
4. **Insurance vagueness** (see Round 2 — position was unanimous, execution debated).

## Round 2 — CTA color: honey vs. spruce

- **Patricia:** Honey everywhere, maximum pop — "serene pages that don't book consults close their doors." Offered an A/B test as her fallback.
- **Maya:** Agreed on honey but for a different reason — the *isolation effect* (the CTA should be the only element in its color; spruce is camouflage on a spruce page). Vetoed the v1 honey (#A96A22) for failing WCAG AA contrast with white text.
- **Dev:** Backed a single reserved conversion color; flagged the same contrast issue.
- **Contested moment:** Patricia wanted the brightest possible amber; Maya refused to ship a sub-AA button on an accessibility-sensitive healthcare site.
- **Consensus:** Honey becomes the *sole* booking-action color sitewide (header, hero, band, form, sticky bar), darkened to #8F5512 in light mode for 6:1+ contrast with white text; spruce demoted to secondary/ghost buttons and non-conversion UI. Patricia's measurement requirement noted for the WP build (event tracking on every booking CTA).

## Round 3 — Hero imagery (the hardest round)

- **Patricia:** Real founder photo in the hero, now — "people buy a person, not a service."
- **Maya:** Real humans only, but flagged an *ethics landmine*: the v1 hero quote from a current client ("Client working with Constance") likely violates professional codes (APA 5.05 prohibits soliciting testimonials from current clients) and named a specific therapist–client relationship. Stock photos vetoed outright — anxious users are hypervigilant to fakeness.
- **Dev:** Keep the testimonial-card-on-gradient until real photos exist; stock "therapy smiles" actively hurt trust and an unmanaged hero image would wreck the page's currently perfect Core Web Vitals.
- **Deadlock:** Patricia wanted a face immediately; no real photo exists yet; Maya/Dev blocked stock.
- **Extended debate → Consensus:** (a) The client testimonial is **removed from the hero** on ethics grounds — no dissent once Maya cited the professional-code issue. (b) The hero card now carries the **founder's story** (the practice's most differentiating asset, previously wasted as a 9-word trust-bar fragment) with a photo slot marked for a real founder photo. (c) A photo shoot is logged as a conversion investment, not a nice-to-have. Patricia accepted the card as a "launch-window placeholder only."

## Round 4 — Page length: bios & pricing

- **Patricia:** Add full therapist bios AND a pricing section — "unanswered objections are the risk, not page length."
- **Maya:** Compact therapist *strip* only (full bios belong on a Team page); one copay-framing line in the FAQ; **against** a full pricing table — out-of-network precision creates more anxiety than it resolves.
- **Dev:** Two additions max — bios (the single largest E-E-A-T hole: "a medical site with zero named, credentialed humans is structurally untrustworthy") and an insurance/pricing transparency block. No blog feed, no carousel.
- **Contested moment:** Full bios vs. strip; pricing table vs. copay framing.
- **Consensus:** A 3-card "Meet your therapists" strip on the homepage (name, credential, specialties, photo slot) linking to a full `/team/` page; cost handled via **copay framing** ("most in-network clients pay $0–$50/session") in the FAQ and the booking card — the strongest cost message without a rate table. FAQ and How-it-works kept at full depth per Patricia. Named-carrier insurance bar added directly under the hero (unanimous from Round 1; built as an editable WP block so the front desk owns the list, with a "list reviewed" date and free-verification caveat as the legal safety valve).

## Round 5 — Sticky mobile bar + SEO/GEO/AEO package + final QA

- **Sticky mobile "Book" bar:** All three yes — but Maya set execution conditions (appears only after the user scrolls past the hero, zero layout shift, no animation, no scarcity countdown, calm copy) and Dev set technical ones (reserved body padding, IntersectionObserver suppression while the booking form is on screen, safe-area insets). Patricia got her tap-to-call button in the bar. **Applied as specified.**
- **SEO/GEO/AEO consensus package (Dev's list, unopposed):**
  - Schema corrected: wrong `medicalSpecialty: "Psychiatric"` removed (implies prescribing); `MedicalBusiness`+`LocalBusiness` with `@id`, `telephone`, `priceRange`, `founder` Person node, `knowsAbout` modalities; `availableService`/`MedicalTherapy` misuse replaced with `hasOfferCatalog`/`Service`; `areaServed` expanded from one State to an array of cities/regions.
  - FAQ JSON-LD now mirrors visible answer text (Google requires marked-up answers to appear on the page); in WP, schema must be generated from the same block content, never hand-maintained.
  - **No `aggregateRating`** without real on-page reviews — manual-action and YMYL legal risk, preempted before anyone asked for SERP stars.
  - AEO: question-format H2s added ("How does online therapy work at Mind Therapy?", "Where is online therapy available in New York?", "Does online therapy actually work?"), plus a quotable cited stat band (2021 *Clinical Psychology Review* meta-analysis, 57 studies) — replacing an uncited efficacy claim buried in a collapsed accordion.
  - GEO: area chips converted from decorative text to links targeting city landing pages (`/online-therapy-brooklyn/` etc.) — flagged by Dev as "the single biggest organic-growth lever on this build." Two new FAQ entries (cost; parents booking for teens) target high-intent long-tail queries.
- **Accessibility/QA sweep (Maya + Dev, unopposed):** `role="img"` removed from the hero card (it was hiding real text from screen readers); footer `<h3>`s replaced with non-heading labels inside `aria-label`ed navs; steps converted to a semantic `<ol>`; skip-to-content link; Safari FAQ marker fix; FAQ kept reachable in mobile nav; dead CSS and inline style overrides cleaned; crisis line (988) promoted from near-invisible footer fineprint to a visible, calm band above the footer.
- **Hero headline (Patricia's Round 1 high-severity item, ratified here):** "Therapy with someone who gets you" was a commodity line every competitor uses; the real differentiator — **in-network insurance** — was demoted to a chip. New H1: *"In-network online therapy, anywhere in New York."* with the empathy line moved to the lede and an "accepting new clients" availability badge (honest urgency, no fake scarcity — Maya's condition).

## Sign-off

- **Maya Chen:** Approves — conversion path is now humane and accessible; wants the founder photo shoot scheduled before launch.
- **Dev Okafor:** Approves — converts cleanly to a block theme; city landing pages are the next build phase; schema is now correct and safe.
- **Patricia Vance:** Approves — with the standing requirement that every booking CTA and the phone line get event tracking, reviewed 30 days post-launch. "Not opinions — click-throughs."

## Placeholders requiring real client data before launch

| Item | Currently | Needs |
|---|---|---|
| Phone number | (212) 555-0134 | Real trackable line |
| Insurance panel | Aetna/Cigna/UHC/Oxford/Empire/Oscar (assumed) | Confirmed carrier list |
| Therapists | 1 partial name + 2 placeholders | Real team, credentials, photos |
| Booking form | Non-functional stub | SimplePractice/Calendly/EHR embed |
| Founder | "Constance —, LCSW" | Full name, bio, photo |
| Schema `sameAs` | Omitted | Psychology Today / Zencare / LinkedIn URLs |
