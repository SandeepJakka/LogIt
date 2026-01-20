# 🚀 LogIt — Developer Productivity & Progress Tracker

LogIt is a **developer-first productivity and progress tracking platform** designed to help engineers systematically log their work, learning, and milestones with long-term visibility.

Unlike traditional task managers, LogIt focuses on **engineering intent and evidence of progress**, combining projects, activity logs, learning records, goals, and analytics into a single structured system.

---

## 🧠 Problem Statement

Developers often:
- Work on multiple projects simultaneously
- Learn continuously without structured tracking
- Lose visibility into long-term growth and outcomes
- Use fragmented tools for tasks, notes, and goals

**LogIt solves this by acting as a single source of truth for developer progress.**

---

## 🔗 Live Prototype (UX Validation)

A live interactive prototype of LogIt is available here:

👉 https://mvpmodel.netlify.app

This prototype was built to:
- Validate user flows and navigation
- Finalize dashboard layout and feature hierarchy
- Guide MVP backend implementation

The prototype represents the **complete product vision**, while the repository contains the **implemented MVP backend and dashboard logic**.


## 🎯 What Makes LogIt Different

- Tracks **execution + learning**, not just tasks
- Emphasizes **progress over time**, not only sprint completion
- Designed specifically for **engineers and technical workflows**
- Built with scalability, analytics, and extensibility in mind

---

## ✨ Core Product Modules (Design Vision)

### 🗂 Projects
- Create, update, archive, and restore projects
- Project lifecycle: **Idea → Build → Deploy → Done**
- Attach repository links, notes, and evidence
- Project-level summaries for tasks and learning

---

### ✅ Tasks Management
- Priority-based tasks: **Critical, High, Medium, Low, Backlog**
- Due dates and completion tracking
- Task velocity and completion insights
- Active, completed, and archived task views

---

### 📝 Activity & Learning Logs
- Log development work, learning sessions, and milestones
- Timestamped entries with tags and descriptions
- Link logs directly to projects
- Central **Activity Stream** showing recent progress

---

### 🎯 Goals & Milestones
- Define long-term goals with categories and priorities
- Break goals into milestones
- Track completion percentages and timelines
- Goal-based progress analytics

---

### 📅 Calendar View
- Monthly, weekly, and daily activity views
- Visual distribution of work and learning
- Daily focus and velocity indicators

---

### 📊 Dashboards & Analytics
- Productivity dashboard showing:
  - Total and active projects
  - Learning activity
  - Recent progress
- Visual indicators for trends and consistency

---

### 👤 Developer Profile
- Developer profile with:
  - Skills and tech stack
  - Project highlights
  - Learning streaks
  - Achievements and milestones

---

## 🧱 System Design Overview

LogIt is designed as a **modular, scalable system** with clear separation of concerns:

- Authentication & User Profiles  
- Projects & Tasks  
- Activity & Learning Logs  
- Goals & Analytics  

The architecture supports future expansion such as:
- AI-powered insights
- Team collaboration
- Advanced reporting and integrations

---

## 🛠 Tech Stack (MVP Implementation)

The MVP implementation focuses on **clean architecture and real-world WordPress engineering practices**.

- **Platform:** WordPress
- **Backend:** Custom plugin architecture
- **Language:** PHP
- **Database:** MySQL
- **Data Modeling:** Custom Post Types & Metadata
- **Dashboard:** Custom WordPress Admin UI
- **Queries:** WP_Query, core WordPress APIs
- **Version Control:** Git & GitHub

---

## 🧩 Implementation Status

### ✅ Implemented (MVP)
- Custom WordPress plugin architecture
- Projects module with status tracking
- Learning Logs module
- Admin dashboard with:
  - Total Projects
  - Active Projects
  - Learning Logs count
  - Recent Activity feed with timestamps
- SaaS-style dashboard layout
- Clean Git commit history

---

### 🟡 Partially Implemented
- Activity stream (basic recent activity)

---

### ❌ Planned (Design Complete)
- Tasks management
- Goals & milestones tracking
- Calendar-based activity view
- Analytics & insights
- Developer profile enhancements
- Team collaboration

> These features are intentionally **designed but not implemented** to demonstrate system planning, scope control, and extensibility.

---

## 🎨 Product Design

The **complete product design** for LogIt is available in the `/design` folder.

The design includes:
- Authentication flow
- Dashboard
- Projects & archives
- Tasks management
- Activity stream
- Calendar view
- Goals & milestones
- Developer profile & achievements

The design serves as:
- A long-term product vision
- A guide for incremental development
- Proof of product and system design thinking

---

## 🎯 Purpose of This Project

LogIt was built to demonstrate:
- Product thinking beyond CRUD applications
- Ability to design scalable systems
- Clean WordPress plugin architecture
- Developer-centric UX and workflows
- Structured tracking of engineering progress

This project reflects **how I think as an engineer**, not just what I can code.

---

## 🔮 Future Enhancements

- Team collaboration & roles
- AI-generated activity summaries
- Smart productivity insights
- GitHub & CI/CD integrations
- Advanced reporting and exports
- Public developer profiles

---

## 📄 License

This project is maintained as a **personal portfolio and product design project**.

---

## 🙌 Author

**Sandeep Jakka**  
Developer | Product Thinker | Systems Builder
