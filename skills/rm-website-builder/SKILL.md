---
name: rm-website-builder
description: Build, refine, or review the RM Group Strategies LLC public website. Use when Codex is asked to implement website pages, UI/UX, HCI improvements, Tailwind CSS layouts, PHP/MySQL lead forms, government contracting content, capability statement sections, vendor/subcontractor forms, or design-law-based reviews for this site.
---

# RM Website Builder

## Core workflow

1. Read `docs/prd.md` first when working inside the RM Group Strategies repository.
2. Read `references/website-brief.md` for the approved pages, content, brand palette, contact details, and lead forms.
3. Read `references/ux-hci-checklist.md` before designing, implementing, or reviewing UI.
4. Build the website as a public corporate/government-contractor site, not an AI agent or internal dashboard.
5. Use the approved stack: PHP, MySQL, Tailwind CSS, HTML5, CSS3, custom SMTP mail, and Namecheap hosting.
6. Keep MVP scope focused on public pages and lead capture; do not add an admin dashboard unless the user explicitly asks for it.
7. After changes, run available syntax, formatting, and validation checks. For PHP files, run `php -l` on changed PHP files when PHP is available.

## Implementation principles

- Use semantic HTML5 landmarks: `header`, `nav`, `main`, `section`, `article`, `aside`, and `footer`.
- Use Tailwind utility classes for layout, spacing, typography, color, and responsive behavior.
- Use CSS3 only for custom brand effects, reusable tokens, or styling Tailwind cannot express cleanly.
- Use progressive enhancement and minimal JavaScript.
- Keep forms server-rendered and validated in PHP.
- Use MySQL with PDO prepared statements for lead storage.
- Send lead notifications through server-side SMTP/custom mail only.
- Store secrets outside committed files.

## UI/UX and HCI rules

Apply these rules to prevent cognitive overload:

- Make one primary action obvious per section.
- Group related content into clear sections with descriptive headings.
- Keep navigation predictable and shallow.
- Use repeated section patterns for service/division pages.
- Keep government contracting content scannable with cards, bullets, and short paragraphs.
- Use generous whitespace and avoid dense text blocks.
- Use plain language for public visitors and procurement terms only where useful.
- Make forms feel short by grouping fields logically and using clear helper text.
- Show clear success, error, loading, and validation states.
- Maintain accessible color contrast for black/gold/white combinations.

## Design laws to apply

- **Hick's Law:** Reduce decision complexity by limiting competing CTAs; use one primary CTA and one secondary CTA per major section.
- **Fitts's Law:** Make important buttons large, easy to tap, and placed near related content.
- **Jakob's Law:** Use familiar website patterns for navigation, hero sections, cards, forms, and footers.
- **Miller's Law:** Break long lists into small groups of 5-7 items when possible.
- **Gestalt proximity:** Place related headings, text, icons, and CTAs close together.
- **Gestalt similarity:** Use consistent card styles for divisions, services, and government capabilities.
- **Serial position effect:** Put the most important service and contact information first and last in long sections.
- **Peak-end rule:** End pages with a strong, clear CTA and confidence-building contact information.
- **Tesler's Law:** Keep complex procurement details available, but organize them so casual visitors are not overwhelmed.
- **Aesthetic-usability effect:** Use polished spacing, hierarchy, and brand styling to increase perceived credibility.

## Required review checklist

Before finalizing website changes, confirm:

- The project is presented as RM Group Strategies LLC, not an AI agent.
- The black-and-gold brand direction is respected.
- The Home page includes the required hero copy and CTAs.
- Government contracting, capability statement, and division content match the website brief.
- Contact details are correct: `702-504-8128`, `contracts@rmgroupstrategies.com`, `Las Vegas, Nevada`.
- Forms validate server-side, sanitize inputs, store leads in MySQL, and send custom mail.
- No admin dashboard or login was added unless explicitly requested.
- UI avoids cognitive overload and follows the design laws above.
