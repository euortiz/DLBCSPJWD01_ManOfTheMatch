# 🏆 Man of the Match Live

> **Real-time fan voting application for football match performance rating**

| Field | Details |
|---|---|
| **Student Name** | Gustavo Sousa Ortiz |
| **Student ID** | 9217457 |
| **Course** | DLBCSPJWD01 – Project: JavaScript Web Development |
| **Submission** | University Portfolio Project |

---

## 1. Project Description

**Man of the Match Live** is a full-stack web application that enables football fans to vote in real time for the standout player of a match. The application presents a roster of players on a dynamic, responsive interface, allows each user to cast one vote per player per hour, and reflects updated vote distributions immediately across all clients via automatic polling — simulating a live broadcast experience.

The project demonstrates the practical integration of a modern RESTful API backend with a reactive single-page frontend, containerised infrastructure, and data integrity mechanisms such as IP-based rate limiting and database-level uniqueness constraints.

---

## 2. Technical Stack

| Layer | Technology | Version |
|---|---|---|
| **Backend Framework** | Laravel | 13.x (Laravel 11 LTS API) |
| **PHP Runtime** | PHP | ^8.3 |
| **Frontend Framework** | Nuxt | 3.x |
| **UI Styling** | Tailwind CSS | v4 (via Vite plugin) |
| **Data Visualisation** | Chart.js + vue-chartjs | Latest |
| **Database** | MySQL | 8.4 |
| **Containerisation** | Docker + Laravel Sail | Latest |
| **API Standard** | REST (JSON) | — |
| **State / Language** | TypeScript (frontend) | — |

---

## 3. Architecture Overview

The project follows a **Decoupled Client-Server Architecture**. The backend and frontend are completely independent services that communicate exclusively through a well-defined REST API.

```
┌─────────────────────────────────────┐      HTTP / JSON        ┌────────────────────────────────────┐
│           FRONTEND (Nuxt 3)         │ ◄──────────────────── ► │         BACKEND (Laravel 13)       │
│                                     │                         │                                    │
│  app.vue          (root page)       │   GET  /api/players     │  PlayerController@index            │
│  PlayerCard.vue   (player UI card)  │   POST /api/players/    │  PlayerController@vote             │
│  VoteChart.vue    (Chart.js donut)  │        {id}/vote        │                                    │
│                                     │                         │  ┌──────────────────────────────┐  │
│  polls every 5 s  → useFetch()      │                         │  │  MySQL 8.4 (via Docker)      │  │
│  localStorage vote state            │                         │  │  players  |  votes           │  │
└─────────────────────────────────────┘                         │  └──────────────────────────────┘  │
         localhost:3000                                         │       localhost:80 (Sail)          │
                                                                └────────────────────────────────────┘
```

The two services are started independently. There is no server-side rendering dependency between them; Nuxt runs in SPA-compatible mode and calls the Laravel API at `http://localhost/api`.

---

## 4. Key Features

| Feature | Description |
|---|---|
| **Real-Time Vote Updates** | The frontend polls `GET /api/players` every 5 seconds and updates both the player cards and the doughnut chart without a full page reload. |
| **IP-Based Anti-Spam Protection** | The backend records each vote with the voter's IP address. A second vote from the same IP for the same player within 60 minutes is rejected with HTTP `429 Too Many Requests`. |
| **Database-Level Integrity** | A composite `UNIQUE` constraint on `(player_id, ip_address)` in the `votes` table provides a database-level safeguard independent of application logic. |
| **Client-Side Vote Persistence** | The voted player's ID is stored in `localStorage`, preventing a user from clicking again across page refreshes within the same browser session. |
| **Live Doughnut Chart** | `VoteChart.vue` uses Chart.js to render vote share as an animated doughnut chart, which reacts to the incoming polling data in real time. |
| **API Resource Layer** | `PlayerResource` on the backend ensures a stable, consistent JSON contract between the API and the frontend, decoupling the database schema from the API response shape. |
| **Responsive Layout** | The player grid adapts from a single-column mobile layout to a three-column grid on `lg` breakpoints, ensuring usability across all device sizes. |
| **Optimistic UI States** | Loading skeletons, voting spinners, and post-vote locked states are rendered declaratively in Vue, providing immediate visual feedback throughout the voting flow. |

---

## 5. Installation Guide

### Prerequisites

Ensure the following tools are installed on your system before proceeding:

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (with Docker Compose)
- [Node.js](https://nodejs.org/) (v18+) and npm
- [Git](https://git-scm.com/)

---

### Step 1 — Clone the Repository

```bash
git clone https://github.com/<your-username>/man-of-the-match-live.git
cd man-of-the-match-live
```

---

### Step 2 — Backend Setup (Laravel API)

#### 2.1 — Configure Environment

```bash
cd backend
cp .env.example .env
```

> The default `.env` values are pre-configured for the Sail/Docker environment and require no changes for a local setup.

#### 2.2 — Start Docker Containers

```bash
docker compose up -d
```

This command starts two containers:
- `backend-laravel.test-1` — the PHP/Laravel application server on **port 80**
- `backend-mysql-1` — the MySQL 8.4 database on **port 3306**

#### 2.3 — Install PHP Dependencies

```bash
docker exec backend-laravel.test-1 composer install
```

#### 2.4 — Run Migrations and Seed the Database

```bash
docker exec backend-laravel.test-1 php artisan migrate --seed
```

This will:
1. Create all database tables (`players`, `votes`, plus Laravel defaults)
2. Seed the `players` table with six players (Messi, Ronaldo, Mbappé, Haaland, Vinicius Jr., Lamine Yamal)

> **Verify:** The API should now be accessible at `http://localhost/api/players`

---

### Step 3 — Frontend Setup (Nuxt 3)

Open a new terminal window/tab.

#### 3.1 — Install Node Dependencies

```bash
cd frontend
npm install
```

#### 3.2 — Configure Environment

The frontend `.env` is already committed with the correct default:

```
NUXT_PUBLIC_API_BASE=http://localhost/api
```

No changes are required for a standard local setup.

#### 3.3 — Start the Development Server

```bash
npm run dev
```

The application will be available at **`http://localhost:3000`**

---

### Step 4 — Verify the Application

| Service | URL | Expected Response |
|---|---|---|
| Laravel API (Players) | `http://localhost/api/players` | JSON array of 6 players |
| Nuxt Frontend | `http://localhost:3000` | Voting interface with player cards and live chart |

---

## 6. Project Structure

```
man-of-the-match-live/
├── backend/                        # Laravel 13 API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   └── PlayerController.php   # GET /players, POST /vote
│   │   │   └── Resources/
│   │   │       └── PlayerResource.php     # API response transformer
│   │   └── Models/
│   │       ├── Player.php
│   │       └── Vote.php
│   ├── database/
│   │   ├── migrations/                    # players & votes table schemas
│   │   └── seeders/
│   │       └── PlayersSeeder.php          # 6 seeded players
│   ├── routes/
│   │   └── api.php                        # REST route definitions
│   └── compose.yaml                       # Docker Sail configuration
│
└── frontend/                       # Nuxt 3 SPA
    ├── app/
    │   ├── app.vue                        # Root page, polling logic, vote handler
    │   └── components/
    │       ├── PlayerCard.vue             # Individual player card with vote button
    │       └── VoteChart.vue             # Chart.js doughnut chart
    ├── public/
    │   └── players/                       # Local player images (.webp / .png / .jpg)
    ├── assets/css/
    │   └── main.css                       # Global Tailwind CSS styles
    └── nuxt.config.ts                     # Nuxt configuration
```

---

## 7. Implementation Reflection

### Clean Code and Software Quality

Throughout this project, deliberate effort has been made to adhere to **Clean Code** principles as defined by Robert C. Martin:

- **Single Responsibility**: Each class and component has one clear purpose. `PlayerController` handles only player-related API actions; `PlayerCard.vue` and `VoteChart.vue` are focused, reusable components.
- **Meaningful Naming**: Variables, routes, and database columns use self-descriptive names (`vote_count`, `ip_address`, `castVote`, `hasVoted`) that eliminate the need for explanatory comments.
- **Layered Architecture**: The `PlayerResource` API transformer decouples the internal data model from the external API contract, allowing the database schema to evolve without breaking the frontend.
- **Data Integrity at Multiple Layers**: Anti-spam logic is enforced both at the application layer (IP + time window check in `PlayerController@vote`) and at the database layer (composite `UNIQUE` constraint on the `votes` table), following the principle of defence in depth.

### Iterative Development

The project was developed iteratively:

1. **Sprint 1 — Infrastructure**: Docker/Sail configuration, database schema design, migrations and seeding.
2. **Sprint 2 — Backend API**: REST endpoints, vote recording, IP-based rate limiting, API Resource transformer.
3. **Sprint 3 — Frontend**: Nuxt 3 project setup, `PlayerCard` component, `useFetch` polling, `localStorage` vote state.
4. **Sprint 4 — Visualisation & Polish**: Chart.js doughnut chart integration, responsive CSS refinements, glassmorphism UI design, local player image assets.

This iterative approach ensured each layer was independently testable before integration, reducing debugging complexity and reflecting industry-standard Agile delivery practices.

---

## 8. API Reference

### `GET /api/players`

Returns all players ordered by vote count (descending).

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Lionel Messi",
      "position": "Forward",
      "photo_url": "/players/messi.webp",
      "vote_count": 42
    }
  ]
}
```

---

### `POST /api/players/{id}/vote`

Registers a vote for the specified player from the requesting IP address.

**Response `200 OK`:**
```json
{
  "message": "Vote recorded successfully.",
  "player": { "id": 1, "name": "Lionel Messi", "vote_count": 43 }
}
```

**Response `429 Too Many Requests`** *(if already voted within 60 minutes):*
```json
{
  "message": "You have already voted for this player in the last hour."
}
```

---

## 9. License

This project is submitted as an academic portfolio piece for **DLBCSPJWD01** at IU International University of Applied Sciences. All rights reserved by the author.

---

*Man of the Match Live — Built with Laravel 13 & Nuxt 3 by Gustavo Sousa Ortiz (9217457)*
