# Product Requirements Document: RM Group Strategies AI Agent

## 1. Overview

RM Group Strategies will build a web-based AI agent that helps users upload, analyze, summarize, and generate business outputs from source documents such as PDFs, client materials, strategy notes, proposals, reports, and intake forms.

The planned technology stack is PHP, MySQL, Tailwind CSS, HTML5, CSS3, custom mail, and Namecheap hosting.

## 2. Current repository state

As of this PRD, the repository contains the Apache License 2.0 file and this initial product document. No PDF files are currently present in the repository, so document-specific requirements below are based on the intended product workflow rather than extracted PDF content.

## 3. Goals

- Provide a secure AI-assisted document analysis workflow for RM Group Strategies.
- Allow users to upload PDFs and ask questions about their contents.
- Generate structured business deliverables such as summaries, action plans, strategy briefs, and product requirements documents.
- Support a practical PHP/MySQL hosting model suitable for Namecheap shared or business hosting environments.
- Provide a simple, responsive interface using Tailwind CSS, HTML5, and CSS3.

## 4. Non-goals

- The MVP will not include full CRM synchronization.
- The MVP will not send autonomous outreach emails without user review.
- The MVP will not require a custom-trained model.
- The MVP will not depend on a heavy JavaScript single-page application framework.
- The MVP will not store documents across unrelated client workspaces without access controls.

## 5. Target users

### 5.1 Internal strategist

An RM Group Strategies team member who needs to review client documents, extract key findings, and prepare recommendations quickly.

### 5.2 Client-facing consultant

A consultant who needs client-ready summaries, meeting briefs, follow-up emails, and strategy memos generated from uploaded documents.

### 5.3 Administrator

A user responsible for managing users, uploaded documents, AI usage, mail settings, and retention controls.

## 6. Core user stories

- As a user, I want to upload a PDF so that the AI agent can analyze its contents.
- As a user, I want to ask questions about uploaded documents so that I can find answers quickly.
- As a user, I want the AI agent to cite the source document and page where possible so that I can verify answers.
- As a user, I want to generate an executive summary so that I can brief stakeholders quickly.
- As a user, I want to generate a PRD from source materials so that implementation teams have structured requirements.
- As a user, I want to export generated outputs so that I can share them with stakeholders.
- As an administrator, I want to manage users and documents so that client data remains organized and secure.

## 7. MVP features

### 7.1 User authentication

- Email and password login.
- Password reset through custom mail.
- Role support for admin and standard user.
- Session management using secure PHP sessions.

### 7.2 Workspace and document management

- Create a workspace or project for each client or initiative.
- Upload one or more PDFs to a workspace.
- Store original file metadata in MySQL.
- Store uploaded files on the server filesystem or Namecheap-compatible storage path.
- Allow users to delete documents from a workspace.

### 7.3 PDF processing

- Extract text from machine-readable PDFs.
- Detect PDFs that require OCR and show a user-facing warning if OCR is unavailable in the hosting environment.
- Split extracted text into chunks suitable for AI retrieval.
- Store chunk metadata, including document ID, chunk order, and page reference when available.

### 7.4 AI chat over documents

- Provide a chat interface scoped to a selected workspace.
- Retrieve relevant document chunks before generating an answer.
- Require answers to be grounded in uploaded content when the user asks document-specific questions.
- Display source references when available.
- Warn the user when an answer is based on general reasoning rather than uploaded documents.

### 7.5 Structured AI outputs

The MVP should include prompt templates for:

- Executive summary
- Key findings
- Risk assessment
- Opportunity analysis
- Action plan
- Meeting brief
- Follow-up email draft
- Product requirements document

### 7.6 Custom mail

- Send password reset messages.
- Send document-processing completion notices when processing is asynchronous.
- Send generated report links to approved recipients.
- Use SMTP settings compatible with Namecheap email hosting or a configured SMTP provider.

### 7.7 Export

- Export generated outputs as Markdown or HTML in the MVP.
- Consider PDF export as a post-MVP enhancement if server dependencies are supported by the hosting plan.

## 8. Recommended information architecture

- Dashboard
- Workspaces
- Workspace detail
- Documents
- AI chat
- Generated outputs
- Settings
- Admin panel

## 9. Data model

### 9.1 users

- id
- name
- email
- password_hash
- role
- created_at
- updated_at

### 9.2 workspaces

- id
- name
- description
- owner_user_id
- created_at
- updated_at

### 9.3 documents

- id
- workspace_id
- original_filename
- stored_filename
- mime_type
- file_size
- status
- extracted_text_path
- uploaded_by_user_id
- created_at
- updated_at

### 9.4 document_chunks

- id
- document_id
- chunk_index
- page_number
- content
- embedding_reference
- created_at

### 9.5 conversations

- id
- workspace_id
- user_id
- title
- created_at
- updated_at

### 9.6 messages

- id
- conversation_id
- role
- content
- citations_json
- created_at

### 9.7 generated_outputs

- id
- workspace_id
- user_id
- output_type
- title
- content
- created_at
- updated_at

## 10. Technical requirements

### 10.1 Backend

- PHP 8.2 or newer where supported by the hosting plan.
- MySQL 8 or compatible Namecheap-provided MySQL version.
- PDO for database access.
- Composer for dependency management if the hosting plan allows it.
- Environment-based configuration for database, SMTP, and AI provider credentials.

### 10.2 Frontend

- HTML5 semantic markup.
- Tailwind CSS for layout and components.
- CSS3 for custom brand styling.
- Progressive enhancement with minimal JavaScript.
- Responsive design for desktop, tablet, and mobile.

### 10.3 Hosting

- Namecheap hosting deployment target.
- Public web root should expose only front-controller or public assets.
- Uploaded documents should not be directly web-accessible unless protected by signed or authenticated routes.
- Cron jobs may be used for background processing if available on the selected plan.

### 10.4 Mail

- SMTP-based custom mail configuration.
- Configurable sender name and sender address.
- Email templates for password reset and notification messages.

### 10.5 AI integration

- Use a server-side AI service integration; do not expose AI API keys to the browser.
- Keep prompts and retrieval logic on the backend.
- Log request metadata needed for debugging, but avoid storing sensitive prompts unnecessarily.
- Add clear fallback messages when AI service calls fail.

## 11. Security and privacy requirements

- Hash passwords using PHP `password_hash`.
- Validate uploaded file types and sizes.
- Restrict uploads to PDF for the MVP.
- Store secrets outside source control.
- Protect against SQL injection using prepared statements.
- Protect against cross-site scripting by escaping output.
- Protect form submissions with CSRF tokens.
- Ensure users can only access documents in authorized workspaces.
- Provide a document deletion flow for sensitive client material.

## 12. UX requirements

- The dashboard should show recent workspaces, uploaded documents, and generated outputs.
- Upload state should clearly show pending, processing, complete, or failed.
- AI answers should distinguish cited document facts from general recommendations.
- Generated outputs should be editable before export or email.
- Error messages should be human-readable and action-oriented.

## 13. Success metrics

- A user can upload and process a readable PDF successfully.
- A user can ask a question and receive a grounded answer with citations.
- A user can generate a structured executive summary in under two minutes.
- A user can export or copy generated output for client use.
- Administrators can manage users and remove documents.

## 14. Implementation phases

### Phase 1: Foundation

- Set up PHP project structure.
- Add Tailwind CSS build process or CDN fallback.
- Create MySQL schema and migrations.
- Implement authentication and base layout.

### Phase 2: Document workflow

- Add workspace management.
- Add PDF upload and validation.
- Add text extraction pipeline.
- Add document status tracking.

### Phase 3: AI workflow

- Add document chunking.
- Add retrieval and AI response generation.
- Add citations.
- Add prompt templates for MVP outputs.

### Phase 4: Mail and export

- Configure SMTP custom mail.
- Add password reset.
- Add generated output storage.
- Add Markdown or HTML export.

### Phase 5: Hardening and launch

- Add audit logging.
- Add admin controls.
- Add deployment documentation for Namecheap.
- Complete security review and end-to-end testing.

## 15. Open questions

- Which AI provider and model should be used?
- Will Namecheap hosting support required PDF extraction binaries, or should extraction run through an external service?
- Are uploaded documents expected to include scanned PDFs that require OCR?
- What file-size limit should the MVP support?
- Should generated deliverables use RM Group Strategies branding templates?
- Should users be able to invite external clients, or is the MVP internal-only?
