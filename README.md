# Mind Therapy NY — Website Prototype

A modernized, WordPress-ready homepage prototype for [mindtherapyny.com](https://www.mindtherapyny.com), designed as a Brightside × Talkspace hybrid in a research-backed spruce/honey palette. The production build now lives on WordPress.com (Business plan) at mindtherapyny.wpcomstaging.com.

## Files

- **index.html** — the complete single-file prototype. Open it in any browser; all photos, styles, and scripts are embedded. Includes hash-routed views (home, full therapists page, 13 individual profile pages), an interactive booking calendar, an FAQ chatbot, and SEO/GEO/AEO structured data (MedicalBusiness + FAQPage JSON-LD).
- **debate-log.md** — the 5-round design-review debate (UX designer / WordPress engineer / practice executive personas) that shaped every major decision.
- **photos.zip** — source photos: the 13 practitioner headshots (from the practice's existing site) and the lifestyle card images.

## Placeholders to replace before launch

| Item | Currently |
|---|---|
| Phone number | (212) 555-0134 (placeholder) |
| Insurance panel | Aetna/Cigna/UHC/Oxford/Empire/Oscar (confirm actual panel) |
| Booking calendar | Simulated availability — wire to a real scheduler |
| Testimonials | Placeholders pending consented client quotes |

## WordPress build

Every section of the prototype is annotated with `data-wp-pattern`. The live WordPress implementation reuses this design via Global Styles (palette + serif typeset), Additional CSS (ported from this stylesheet), and a WPCode footer snippet (calendar, chatbot, sticky mobile bar).
