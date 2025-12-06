<!-- Copilot/AI agent instructions for the arsipsurat PHP project -->
# Project snapshot

+ Tiny custom MVC in PHP (no framework): front controller is `public/index.php` -> `app/init.php` -> `app/core/App.php`.
+ Controllers: `app/controllers/*.php` — class name is the controller name (capitalized), file name matches (e.g. `Surat.php` => class `Surat`).
+ Models: `app/models/*_model.php` — instantiate via controller helper: `$this->model('X_model')`.
+ Views/templates: `templates/` (Controller::view uses `APPROOT . '/templates/' . $view . '.php'`).
+ DB: `app/core/Database.php` is a PDO wrapper (use `query()`, `bind()`, `execute()`, `resultSet()`, `single()`, `lastInsertId()`).

# Key files to reference

+ `app/config/config.php` — set `APPROOT`, `BASE_URL`, DB credentials.
+ `app/init.php` — bootstraps session, config, core classes and helpers (`Flasher`, `Functions`).
+ `app/core/App.php` — URL parsing and controller/method dispatching.
+ `app/core/Controller.php` — `view()` and `model()` helpers (loads models from `app/models`).
+ `templates/surat/export/surat_keterangan.php` — example export template that `Surat::generate()` includes.

# Routing & naming conventions (explicit)

+ URL form: `http(s)://HOST/arsipsurat/public/?url=Controller/method/param1/param2` or with mod_rewrite remove `?url=` if your server configured similarly.
+ Controller file: `app/controllers/Foo.php` with class `Foo`.
+ Method access: App checks `$url[1]` and `method_exists()` before invocation.
+ View paths: call `$this->view('folder/name', $data)` -> loads `templates/folder/name.php`.
+ Model paths: call `$this->model('X_model')` -> loads `app/models/X_model.php` and returns `new X_model`.

# Common patterns and idioms in this repo

+ Session-based auth: controllers enforce login by checking `$_SESSION['login']` in `__construct()` (see `Surat::__construct`).
+ Flash messages: use `Flasher::setFlash($title, $message, $type)` and templates read from that helper.
+ File storage:
   + Archives: `uploads/arsip/` (managed by `Arsip_model`).
   + Generated letters: `public/uploads/surat_dihasilkan/` (used by `Surat::generate`).
+ Template rendering for export: `Surat::generate()` includes `templates/surat/export/surat_{kode}.php` inside an output buffer; the included template expects variables like `$pejabat`, `$logoUrl`, and other mapped form variables.
+ External libs via Composer: `vendor/` contains `mpdf` and `phpoffice/phpword` used for PDF/DOCX generation. Ensure `vendor/autoload.php` is required (done in `public/index.php`).

# DB / schema notes

+ Default DB name used in `app/config/config.php` is `db_arsipsurat` — sample DB dump at project root: `db_arsipsurat.sql`.
+ Models use prepared statements + binding convention: `$this->db->query($sql); $this->db->bind('name', $val); $this->db->execute();`.

# Developer workflow (how to run & debug locally)

+ 1) Install dependencies: run `composer install` in project root to get mPDF / PhpWord.
+ 2) Import DB: import `db_arsipsurat.sql` into your local MySQL (Laragon recommended on Windows).
+ 3) Configure `app/config/config.php`: set `DB_*` and `BASE_URL` (example value: `http://localhost/arsipsurat/public`).
+ 4) Access app via your webserver: `http://localhost/arsipsurat/public` (Laragon/Apache recommended).
+ 5) If debugging, check PHP error display settings or add temporary `ini_set('display_errors',1); error_reporting(E_ALL);` near `public/index.php` during local dev.

# How to add a controller + model (minimal example)

+ Controller: create `app/controllers/Report.php` with class `Report extends Controller` and methods for actions.
+ Model: create `app/models/Report_model.php` with class `Report_model` that constructs `new Database;` in `__construct()`.
+ Use `$this->view('report/list', $data)` to render `templates/report/list.php`.
+ Use `$this->model('Report_model')->someMethod()` to call the model.

# Integration points & gotchas agents should know

+ Many controllers rely on `$_SESSION` values set at login (`user_id`, `nama_lengkap`, `role`); avoid changing session shape.
+ Paths stored in DB are relative (e.g. `uploads/surat_dihasilkan/FILE.pdf`) — when removing files use `__DIR__` + relative path or `APPROOT` + `public/`.
+ Templates included for export expect sanitized and pre-mapped variables; `Surat::generate()` prepares `$html` via output buffering then feeds it to mPDF/PhpWord.
+ Naming differences: model classes are `Something_model` not `SomethingModel`.

# Merge guidance (if this file already exists)

+ Preserve any project-specific notes already present. Prefer concrete file references and examples above generic suggestions.

# If something's unclear

+ Ask for the specific controller, model, or template to inspect. Provide a sample URL and desired change and I can generate a patch.

---
Please review if you want more examples (e.g. add a new model skeleton, or list all templates under `templates/surat`).
