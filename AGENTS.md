# Repository Guidelines

## Project Structure & Module Organization
Laravel domain code sits inside `app/` (HTTP controllers, Jobs, Policies, console commands) while shared configuration lives in `config/`. Routes are defined in `routes/web.php` for browser flows, `routes/api.php` for JSON endpoints, and `routes/channels.php` for broadcasting. Front-end assets and Blade templates live in `resources/` (`resources/views` for pages, `resources/js` for Alpine/Vite modules, `resources/css` for Tailwind sources) and compile into `public/build` through Vite. Persistence artifacts, including migrations, seeders, and factories, are under `database/`, and their generated data is stored in `storage/` during local runs. Automated tests reside in `tests/Feature` and `tests/Unit`, reusing scaffolding from `tests/TestCase.php`.

## Build, Test, and Development Commands
- `composer setup` — installs PHP dependencies, copies `.env`, generates the app key, runs migrations, and performs an initial `npm run build`; run once on a fresh clone.
- `composer dev` — starts `php artisan serve`, the queue worker (`php artisan queue:listen --tries=1`), and `npm run dev` via `npx concurrently` for a full local stack.
- `npm run dev` — launches Vite’s hot module server if you prefer to run the PHP server separately (e.g., via Valet or Sail).
- `npm run build` — produces hashed production assets inside `public/build` for deployment artifacts.
- `composer test` — clears cached config and executes `php artisan test` (Pest-powered) to exercise unit and feature suites.

## Coding Style & Naming Conventions
Follow PSR-12 with 4-space indentation and typed properties where practical. Keep controllers ending in `Controller`, event/listener pairs mirroring their domain (e.g., `ScholarshipAwarded`), and Blade templates grouped by feature (`resources/views/dashboard/index.blade.php`). Run `./vendor/bin/pint` before opening a PR to format PHP, and rely on Tailwind utility classes inside Blade or `resources/css/app.css`. JavaScript modules should stay in ES Module syntax, using kebab-case filenames for Alpine components and PascalCase for reusable classes.

## Testing Guidelines
Prefer Pest test cases (`tests/Feature/*.php`, `tests/Unit/*.php`) and name them after the behavior, such as `tests/Feature/UserCanSubmitApplicationTest.php`. Use factories from `database/factories/` plus the `RefreshDatabase` trait to isolate state. Run `composer test` before pushing; for focused iterations, `php artisan test --filter=ApplicationTest --parallel` keeps the suite fast. Add regression coverage whenever you touch migrations, queued jobs, or authorization logic (Spatie roles/permissions).

## Commit & Pull Request Guidelines
Recent history (`git log --oneline`) shows concise, imperative subjects (“Worked on sidebar”, “Done with dashboard”). Continue that format, optionally prefixing the touched area (`Dashboard: update stats card`) and keeping subjects under ~70 chars. Each commit should bundle one logical change and mention follow-up chores (migrations, queues) in the body. Pull requests must include: context/goal summary, linked issue or ticket, screenshots for UI-facing work, steps to reproduce/verify, and explicit notes about migrations, seeding requirements, or new env keys so reviewers can reproduce locally.

## Security & Configuration Tips
Duplicate `.env.example` into `.env` and keep secrets out of version control. After changing environment config, run `php artisan config:clear` (and `php artisan cache:clear` if caching policies). Queue workers listen via `php artisan queue:listen --tries=1`, so document any new jobs or supervisor settings in the PR. Before deployments, ensure `npm run build`, `php artisan migrate --force`, and `php artisan config:cache` have executed so production mirrors the repository state.
