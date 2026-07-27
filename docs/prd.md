# Product Requirements Document: RM Group Strategies Website

## 1. Overview

RM Group Strategies will build a professional marketing website that presents the company, communicates its services, captures qualified leads, and provides a reliable way for visitors to contact the business.

The website will use PHP, MySQL, Tailwind CSS, HTML5, CSS3, custom mail, and Namecheap hosting.

## 2. Current repository state

As of this PRD update, the repository contains the Apache License 2.0 file and this product requirements document. No source PDFs are currently present in the repository, so this PRD focuses on the website requirements provided by the project owner rather than PDF-derived content.

## 3. Product goals

- Create a polished public website for RM Group Strategies.
- Clearly communicate the company's value proposition, services, and credibility.
- Convert visitors into leads through contact forms and calls to action.
- Provide an easy-to-maintain PHP/MySQL foundation that works well on Namecheap hosting.
- Use Tailwind CSS, HTML5, and CSS3 to deliver a responsive, modern user experience.
- Support custom email notifications for inquiries and form submissions.

## 4. Non-goals

- This project is not an AI agent product.
- This project is not a document-analysis application.
- This project will not require user-uploaded PDFs in the MVP.
- This project will not require a custom-trained AI model.
- This project will not use a heavy JavaScript single-page application framework for the MVP.
- This project will not include complex CRM automation in the initial launch.

## 5. Target audiences

### 5.1 Prospective clients

Business owners, executives, or decision-makers who want to understand RM Group Strategies' services and request help.

### 5.2 Referral partners

People or organizations who need a clear website to evaluate the company before referring prospects.

### 5.3 Internal administrators

RM Group Strategies team members who need to receive inquiries, review contact submissions, and maintain basic website content.

## 6. Core user stories

- As a visitor, I want to quickly understand what RM Group Strategies does so that I can decide whether the company can help me.
- As a visitor, I want to view services so that I can understand the problems RM Group Strategies solves.
- As a visitor, I want to see proof points, testimonials, or credibility indicators so that I can trust the company.
- As a visitor, I want to submit a contact form so that RM Group Strategies can follow up with me.
- As a visitor, I want to use the website on mobile so that I can contact the company from any device.
- As an administrator, I want contact submissions stored in MySQL so that inquiries are not lost if email delivery fails.
- As an administrator, I want contact notifications sent by custom mail so that the team can respond quickly.

## 7. MVP website pages

### 7.1 Home page

The home page should include:

- Hero section with headline, subheadline, and primary call to action.
- Short explanation of RM Group Strategies' value proposition.
- Service overview cards.
- Trust-building section such as results, process, client types, or testimonials.
- Secondary call to action.
- Footer with navigation and contact details.

### 7.2 About page

The about page should include:

- Company overview.
- Mission or positioning statement.
- Team or founder section if content is available.
- Differentiators that explain why visitors should choose RM Group Strategies.

### 7.3 Services page

The services page should include:

- List of core services.
- Description of each service.
- Problems each service solves.
- Suggested next step for visitors who are interested.

### 7.4 Contact page

The contact page should include:

- Contact form.
- Business contact information.
- Optional scheduling or consultation call-to-action.
- Confirmation message after submission.

### 7.5 Privacy policy page

The privacy policy page should explain:

- What information is collected through forms.
- How submitted information is used.
- How users can request removal or correction of their information.

## 8. MVP features

### 8.1 Responsive public website

- Build mobile-first pages using HTML5 and Tailwind CSS.
- Use CSS3 for custom brand refinements.
- Support common screen sizes from mobile to desktop.
- Keep the interface fast, readable, and professional.

### 8.2 Contact form

- Capture name, email, phone number, company, selected service, and message.
- Validate required fields server-side in PHP.
- Sanitize submitted data before storage and display.
- Store submissions in MySQL.
- Send a custom email notification to the RM Group Strategies team.
- Show a success or error message after submission.

### 8.3 Lead management admin view

- Provide a protected admin login.
- List contact submissions from MySQL.
- View inquiry details.
- Mark submissions as new, contacted, qualified, closed, or spam.
- Add internal notes to submissions.

### 8.4 Content structure

- Store configurable site content in PHP templates or MySQL, depending on implementation complexity.
- Keep reusable layout sections for header, footer, navigation, and calls to action.
- Make future page additions straightforward.

### 8.5 Custom mail

- Send inquiry notifications through SMTP.
- Support configurable sender name, sender email, and recipient email.
- Use email templates for consistent branding.
- Log email delivery attempts for troubleshooting.

## 9. Recommended information architecture

- Home
- About
- Services
- Contact
- Privacy Policy
- Admin Login
- Admin Dashboard
- Lead Detail

## 10. Data model

### 10.1 users

- id
- name
- email
- password_hash
- role
- created_at
- updated_at

### 10.2 contact_submissions

- id
- name
- email
- phone
- company
- service_interest
- message
- status
- source_page
- ip_address
- user_agent
- created_at
- updated_at

### 10.3 contact_notes

- id
- contact_submission_id
- user_id
- note
- created_at
- updated_at

### 10.4 email_logs

- id
- contact_submission_id
- recipient_email
- subject
- status
- error_message
- created_at

### 10.5 site_settings

- id
- setting_key
- setting_value
- updated_at

## 11. Technical requirements

### 11.1 Backend

- Use PHP 8.2 or the newest stable PHP version supported by the selected Namecheap plan.
- Use MySQL through PDO prepared statements.
- Use secure PHP sessions for admin authentication.
- Keep configuration values outside committed source files when possible.
- Structure the app so the public web root exposes only public assets and entry files.

### 11.2 Frontend

- Use semantic HTML5.
- Use Tailwind CSS for responsive layouts and components.
- Use CSS3 for custom styling, transitions, and brand details.
- Avoid unnecessary JavaScript for the MVP.
- Ensure forms are usable on mobile devices.

### 11.3 Hosting

- Deploy to Namecheap hosting.
- Confirm PHP and MySQL versions available on the selected plan before implementation.
- Use Namecheap cPanel or deployment process for database setup, file upload, SSL, and email configuration.
- Keep uploaded or private files outside public access if future file features are added.

### 11.4 Mail

- Use SMTP-compatible custom mail.
- Store SMTP credentials securely outside public files.
- Support form-submission notifications.
- Consider autoresponder emails after the core contact workflow is stable.

## 12. Security and privacy requirements

- Hash admin passwords using PHP `password_hash`.
- Use prepared statements for all database queries.
- Validate and sanitize all form submissions.
- Escape output to protect against cross-site scripting.
- Use CSRF protection on forms.
- Rate-limit or add spam prevention to the contact form.
- Do not commit passwords, API keys, SMTP credentials, or database credentials.
- Use HTTPS in production.
- Provide a privacy policy for visitor-submitted contact information.

## 13. UX requirements

- The site should feel professional, credible, and easy to navigate.
- Primary calls to action should be visible on the home, services, and contact pages.
- Contact form errors should be specific and easy to fix.
- The success message should tell the visitor what happens next.
- Page loading should be fast on mobile connections.
- Navigation should remain simple and consistent across pages.

## 14. Success metrics

- Visitors can understand RM Group Strategies' services within the first screen of the home page.
- Visitors can submit the contact form successfully from desktop and mobile devices.
- Form submissions are stored in MySQL.
- Custom email notifications are delivered to the configured recipient.
- Administrators can review and update lead statuses.
- The website can be deployed successfully to Namecheap hosting.

## 15. Implementation phases

### Phase 1: Project foundation

- Set up PHP project structure.
- Add reusable layout partials for header, footer, and navigation.
- Configure Tailwind CSS or a CDN fallback.
- Create initial database schema.

### Phase 2: Public website

- Build home page.
- Build about page.
- Build services page.
- Build contact page.
- Build privacy policy page.

### Phase 3: Contact workflow

- Implement contact form validation.
- Store submissions in MySQL.
- Send custom mail notifications.
- Add success and error handling.

### Phase 4: Admin workflow

- Implement admin login.
- Add admin dashboard.
- Add lead detail view.
- Add status updates and internal notes.

### Phase 5: Deployment and launch

- Prepare Namecheap deployment instructions.
- Configure production database and mail settings.
- Enable HTTPS.
- Run final responsive, form, security, and mail checks.

## 16. Open questions

- What exact services should be listed on the services page?
- What brand colors, typography, and logo assets should be used?
- Should the site include testimonials, case studies, or client logos?
- What email address should receive contact form notifications?
- Does the selected Namecheap plan support the desired PHP version and SMTP configuration?
- Should there be a blog or resources section after the MVP launch?
