# SRGIM — Sistema de Registro y Gesti\u00f3n de Inventario para Tienda Milagritos

**SRGIM** es una aplicaci\u00f3n web *mobile-first* de gesti\u00f3n de inventario construida con **Laravel 13** y **CSS personalizado**. Est\u00e1 dise\u00f1ada para una bodega minorista peruana que opera bajo el r\u00e9gimen **Nuevo RUS** (boleta simple, precios con IGV incluido). Est\u00e1 pensada para ser usada exclusivamente desde un smartphone Android por personas sin conocimientos t\u00e9cnicos.

---

## \u00cdndice

1. [Requisitos del Sistema](#requisitos-del-sistema)
2. [Instalaci\u00f3n](#instalaci\u00f3n)
3. [Arquitectura General](#arquitectura-general)
4. [Base de Datos](#base-de-datos)
   - [Diagrama de Relaciones](#diagrama-de-relaciones)
   - [Estructura de Tablas](#estructura-de-tablas)
5. [Modelos (Eloquent ORM)](#modelos)
6. [Controladores](#controladores)
7. [Rutas](#rutas)
8. [Vistas (Blade)](#vistas)
   - [Layouts](#layouts)
   - [Partial: Bottom Nav](#partial-bottom-nav)
   - [Pantallas del Sistema](#pantallas-del-sistema)
9. [Sistema de Roles](#sistema-de-roles)
10. [Estilos CSS](#estilos-css)
    - [Paleta de Colores](#paleta-de-colores)
    - [Sistema de Sombras y Bordes](#sistema-de-sombras-y-bordes)
    - [Tipograf\u00eda](#tipograf\u00eda)
    - [Clases Principales](#clases-principales)
11. [Flujo de Ingreso de Mercader\u00eda](#flujo-de-ingreso)
12. [Flujo de Venta (FIFO)](#flujo-de-venta)
13. [Alertas de Vencimiento](#alertas-de-vencimiento)
14. [Cierre de Caja](#cierre-de-caja)
15. [Auditor\u00eda (Historial)](#auditor\u00eda)
16. [Manejo de Undefined Variable (Bug Fix)](#bug-fix-alertacontroller)
17. [Configuraci\u00f3n del Proyecto](#configuraci\u00f3n-del-proyecto)
18. [Licencia](#licencia)

---

## Requisitos del Sistema

| Componente | Versi\u00f3n M\u00ednima |
|---|---|
| PHP | ^8.3 |
| Laravel | ^13.8 |
| Composer | 2.x |
| Node.js | 20+ |
| NPM | 10+ |
| SQLite | 3.x |
| Base de Datos | SQLite (por defecto) / MySQL 8+ / MariaDB 10+ |

---

## Instalaci\u00f3n

```bash
# 1. Clonar el repositorio
git clone <url-del-repo> srgim
cd srgim

# 2. Instalar dependencias PHP
composer install

# 3. Copiar y configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar base de datos en .env (por defecto SQLite)
#    DB_CONNECTION=sqlite

# 5. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 6. Crear enlace de almacenamiento  (para fotos de productos)
php artisan storage:link

# 7. Instalar dependencias frontend y compilar
npm install && npm run build

# 8. Iniciar servidor de desarrollo
php artisan serve
```

### Credenciales por Defecto

| Usuario | Rol | Email | Contrase\u00f1a |
|---|---|---|---|
| `admin` | Administrador | `admin@tiendamilagritos.com` | `admin123` |
| `Aurea Huam\u00e1n` | Colaborador | `aurea@tiendamilagritos.com` | `aurea123` |

> **Nota:** Solo existe UN administrador. Todos los usuarios creados desde el panel son autom\u00e1ticamente colaboradores.

---

## Arquitectura General

SRGIM sigue el patr\u00f3n **MVC** de Laravel con una estructura de 3 layouts:

```
app/
  Http/
    Controllers/
      AuthController.php          # Login/Logout personalizado
      DashboardController.php     # P\u00e1gina principal (dashboard)
      AdminController.php         # Panel de administraci\u00f3n
      ProductoController.php      # CRUD de productos (cat\u00e1logo)
      IngresoController.php       # Registro de compras/ingresos
      VentaController.php         # Registro de ventas
      AlertaController.php        # Alertas de vencimiento
      CajaController.php          # Cierre diario / gr\u00e1fico de ventas
      HistorialController.php     # Auditor\u00eda de movimientos
      CategoriaController.php     # API interna para categor\u00edas
      Admin/
        UsuarioController.php     # CRUD de usuarios (solo admin)
    Middleware/
      AdminMiddleware.php          # Filtro de acceso para administradores
  Models/
    User.php
    Categoria.php
    Producto.php
    Ingreso.php
    DetalleIngreso.php
    Venta.php
    DetalleVenta.php
    MovimientoInventario.php
resources/
  views/
    layouts/
      auth.blade.php               # Layout para login (sin navegaci\u00f3n)
      app.blade.php                 # Layout principal (con bottom-nav)
      admin.blade.php               # Layout para panel admin (sin bottom-nav)
    partials/
      bottom-nav.blade.php          # Barra de navegaci\u00f3n inferior fija
    auth/
      login.blade.php               # Pantalla de inicio de sesi\u00f3n
    dashboard/
      index.blade.php               # Dashboard principal
    catalogos/
      index.blade.php               # Lista de productos (cat\u00e1logo)
      create.blade.php              # Crear producto
      edit.blade.php                # Editar producto
    ingresos/
      index.blade.php               # Historial de ingresos
      create.blade.php              # Nuevo ingreso (f\u00f3rmula paquetes)
      show.blade.php                # Detalle de ingreso
    ventas/
      index.blade.php               # Ventas del d\u00eda
      create.blade.php              # Nueva venta (FIFO)
    alertas/
      index.blade.php               # Productos caducados y por vencer
    caja/
      index.blade.php               # Gr\u00e1fico de cierre diario
    historial/
      index.blade.php               # Auditor\u00eda de movimientos
    admin/
      index.blade.php               # Men\u00fa de administraci\u00f3n
      usuarios/
        index.blade.php             # Lista de usuarios
        create.blade.php            # Crear usuario
        edit.blade.php              # Editar usuario
  css/
    app.css                         # \u00danico archivo CSS (800 l\u00edneas)
routes/
  web.php                           # 30 rutas definidas
```

---

## Base de Datos

### Diagrama de Relaciones

```
users
  ├── id (PK)
  ├── name
  ├── email (UNIQUE)
  ├── password
  ├── role (enum: admin, colaborador) → default: colaborador
  ├── remember_token
  └── timestamps
        │
        ├──< ingresos (FK: user_id)
        ├──< ventas (FK: user_id)
        └──< movimientos_inventarios (FK: user_id)

categorias
  ├── id (PK)
  ├── nombre
  └── timestamps
        │
        └──< productos (FK: categoria_id)

productos
  ├── id (PK)
  ├── categoria_id (FK → categorias)
  ├── nombre
  ├── foto_ruta (nullable)
  ├── unidad_medida (VARCHAR 10) → UN, KG, LT, MTS
  ├── stock_total (DECIMAL 8,3)
  ├── deleted_at (softDeletes)
  └── timestamps
        │
        ├──< detalle_ingresos (FK: producto_id)
        ├──< detalle_ventas (FK: producto_id)
        └──< movimientos_inventarios (FK: producto_id)

ingresos
  ├── id (PK)
  ├── user_id (FK → users)
  ├── total_ingreso (DECIMAL 10,2)
  └── timestamps (created_at = fecha_ingreso)
        │
        └──< detalle_ingresos (FK: ingreso_id, ON DELETE CASCADE)

detalle_ingresos
  ├── id (PK)
  ├── ingreso_id (FK → ingresos, CASCADE)
  ├── producto_id (FK → productos)
  ├── cantidad_paquetes (DECIMAL 8,2)      ← paquetes comprados
  ├── unidades_por_paquete (DECIMAL 8,3)   ← UMP
  ├── cantidad_inicial (DECIMAL 8,3)       ← = paquetes × UMP
  ├── stock_restante (DECIMAL 8,3)         ← decrementa con ventas
  ├── precio_paquete (DECIMAL 10,2)        ← precio de compra por paquete
  ├── precio_costo_unitario (DECIMAL 10,2) ← = precio_paquete / UMP
  ├── fecha_vencimiento (DATE)
  └── timestamps

ventas
  ├── id (PK)
  ├── user_id (FK → users)
  ├── total_venta (DECIMAL 10,2)
  └── timestamps (created_at = fecha_venta)
        │
        └──< detalle_ventas (FK: venta_id, ON DELETE CASCADE)

detalle_ventas
  ├── id (PK)
  ├── venta_id (FK → ventas, CASCADE)
  ├── producto_id (FK → productos)
  ├── cantidad (DECIMAL 8,3)
  ├── precio_venta_unitario (DECIMAL 10,2)
  ├── subtotal (DECIMAL 10,2)
  └── timestamps

movimientos_inventarios
  ├── id (PK)
  ├── producto_id (FK → productos)
  ├── user_id (FK → users)
  ├── tipo (enum: ingreso, venta, anulacion)
  ├── cantidad (DECIMAL 8,3)               ← signo: +ingreso, -venta
  └── timestamps (created_at = fecha_movimiento)
```

### Estructura de Tablas (DDL)

#### `users` (0001_01_01_000000_create_users_table.php)

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->enum('role', ['admin', 'colaborador'])->default('colaborador');
    $table->rememberToken();
    $table->timestamps();
});
```

#### `categorias` (2026_07_06_014345_create_categorias_table.php)

```php
Schema::create('categorias', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->timestamps();
});
```

#### `productos` (2026_07_06_014345_create_productos_table.php)

```php
Schema::create('productos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('categoria_id')->constrained('categorias');
    $table->string('nombre');
    $table->string('foto_ruta')->nullable();
    $table->string('unidad_medida', 10)->default('UN');
    $table->decimal('stock_total', 8, 3)->default(0);
    $table->softDeletes();
    $table->timestamps();
});
```

#### `ingresos` (2026_07_06_014345_create_ingresos_table.php)

```php
Schema::create('ingresos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->decimal('total_ingreso', 10, 2)->default(0);
    $table->timestamps();
});
```

#### `detalle_ingresos` (2026_07_06_014345_create_detalle_ingresos_table.php)

```php
Schema::create('detalle_ingresos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ingreso_id')->constrained('ingresos')->onDelete('cascade');
    $table->foreignId('producto_id')->constrained('productos');
    $table->decimal('cantidad_paquetes', 8, 2);
    $table->decimal('unidades_por_paquete', 8, 3);
    $table->decimal('cantidad_inicial', 8, 3);
    $table->decimal('stock_restante', 8, 3);
    $table->decimal('precio_paquete', 10, 2);
    $table->decimal('precio_costo_unitario', 10, 2);
    $table->date('fecha_vencimiento');
    $table->timestamps();
});
```

#### `ventas` (2026_07_06_014345_create_ventas_table.php)

```php
Schema::create('ventas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users');
    $table->decimal('total_venta', 10, 2);
    $table->timestamps();
});
```

#### `detalle_ventas` (2026_07_06_014345_create_detalle_ventas_table.php)

```php
Schema::create('detalle_ventas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
    $table->foreignId('producto_id')->constrained('productos');
    $table->decimal('cantidad', 8, 3);
    $table->decimal('precio_venta_unitario', 10, 2);
    $table->decimal('subtotal', 10, 2);
    $table->timestamps();
});
```

#### `movimientos_inventarios` (2026_07_06_014346_create_movimiento_inventarios_table.php)

```php
Schema::create('movimientos_inventarios', function (Blueprint $table) {
    $table->id();
    $table->foreignId('producto_id')->constrained('productos');
    $table->foreignId('user_id')->constrained('users');
    $table->enum('tipo', ['ingreso', 'venta', 'anulacion']);
    $table->decimal('cantidad', 8, 3);
    $table->timestamps();
});
```

### Decisiones de Dise\u00f1o de la Base de Datos

1. **DECIMAL(8,3) para cantidades**: Permite manejar fracciones como 0.500 KG (medio kilo) o 0.250 KG (cuarto de queso). Los productos con unidad `UN` ocultan los decimales en la interfaz mediante un accessor.
2. **`cantidad_paquetes` × `unidades_por_paquete`**: Soluciona el problema pr\u00e1ctico de "1 saco de 50 KG de arroz" — el usuario ingresa 1 paquete × 50 KG/paquete = 50 KG de stock.
3. **`precio_costo_unitario`** = `precio_paquete` / `unidades_por_paquete`: Calcula autom\u00e1ticamente el costo unitario para generar precios de venta.
4. **`stock_restante` en `detalle_ingresos`**: Permite FIFO real — cada lote de ingreso mantiene su propio stock y fecha de vencimiento.
5. **`softDeletes()` en `productos`**: Los productos eliminados conservan su integridad referencial en el historial de movimientos (HU-04).
6. **`enum('admin','colaborador')`**: Garantiza integridad a nivel BD — no se puede insertar un rol inv\u00e1lido por error de tipeo en el c\u00f3digo.
7. **No hay columnas IGV**: La tienda opera bajo Nuevo RUS (Per\u00fa); el precio incluye impuestos. No aplica separaci\u00f3n.

---

## Modelos (Eloquent ORM)

### `User` (`app/Models/User.php`)

```php
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ingresos(): HasMany     { /* $this->hasMany(Ingreso::class) */ }
    public function ventas(): HasMany       { /* $this->hasMany(Venta::class) */ }
    public function isAdmin(): bool         { return $this->role === 'admin'; }
}
```

- `isAdmin()`: M\u00e9todo helper usado por el `AdminMiddleware` y las vistas Blade para verificar si el usuario tiene permisos de administrador.

### `Categoria` (`app/Models/Categoria.php`)

```php
class Categoria extends Model
{
    protected $fillable = ['nombre'];
    public function productos(): HasMany    { /* $this->hasMany(Producto::class) */ }
}
```

### `Producto` (`app/Models/Producto.php`)

```php
class Producto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categoria_id', 'nombre', 'foto_ruta', 'unidad_medida', 'stock_total',
    ];

    protected function casts(): array
    {
        return ['stock_total' => 'decimal:3'];
    }

    // Accessor: UN → entero (sin decimales), otros → 3 decimales
    protected function stockFormateado(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->unidad_medida === 'UN'
                ? (string) round($this->stock_total)
                : number_format($this->stock_total, 3, '.', '')
        );
    }

    public function categoria(): BelongsTo               { /* belongsTo(Categoria::class) */ }
    public function detallesIngreso(): HasMany            { /* hasMany(DetalleIngreso::class) */ }
    public function detallesVenta(): HasMany              { /* hasMany(DetalleVenta::class) */ }
    public function movimientos(): HasMany                { /* hasMany(MovimientoInventario::class) */ }
}
```

### `Ingreso` (`app/Models/Ingreso.php`)

```php
class Ingreso extends Model
{
    protected $fillable = ['user_id', 'total_ingreso'];
    public function user(): BelongsTo        { /* belongsTo(User::class) */ }
    public function detalles(): HasMany      { /* hasMany(DetalleIngreso::class, 'ingreso_id') */ }
}
```

### `DetalleIngreso` (`app/Models/DetalleIngreso.php`)

```php
class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'ingreso_id', 'producto_id',
        'cantidad_paquetes', 'unidades_por_paquete',
        'cantidad_inicial', 'stock_restante',
        'precio_paquete', 'precio_costo_unitario',
        'fecha_vencimiento',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_paquetes' => 'decimal:2',
            'unidades_por_paquete' => 'decimal:3',
            'cantidad_inicial' => 'decimal:3',
            'stock_restante' => 'decimal:3',
            'precio_paquete' => 'decimal:2',
            'precio_costo_unitario' => 'decimal:2',
            'fecha_vencimiento' => 'date',
        ];
    }

    public function ingreso(): BelongsTo     { /* belongsTo(Ingreso::class) */ }
    public function producto(): BelongsTo    { /* belongsTo(Producto::class) */ }
}
```

### `Venta` (`app/Models/Venta.php`)

```php
class Venta extends Model
{
    protected $fillable = ['user_id', 'total_venta'];
    public function user(): BelongsTo        { /* belongsTo(User::class) */ }
    public function detalles(): HasMany      { /* hasMany(DetalleVenta::class, 'venta_id') */ }
}
```

### `DetalleVenta` (`app/Models/DetalleVenta.php`)

```php
class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio_venta_unitario', 'subtotal'];

    protected function casts(): array
    {
        return [
            'cantidad' => 'decimal:3',
            'precio_venta_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function venta(): BelongsTo       { /* belongsTo(Venta::class) */ }
    public function producto(): BelongsTo    { /* belongsTo(Producto::class) */ }
}
```

### `MovimientoInventario` (`app/Models/MovimientoInventario.php`)

```php
class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventarios';
    protected $fillable = ['producto_id', 'user_id', 'tipo', 'cantidad'];

    protected function casts(): array
    {
        return ['cantidad' => 'decimal:3'];
    }

    public function producto(): BelongsTo    { /* belongsTo(Producto::class) */ }
    public function user(): BelongsTo        { /* belongsTo(User::class) */ }
}
```

---

## Controladores

### `AuthController` — Login/Logout Personalizado

- **`showLoginForm()`**: Retorna la vista `auth.login`.
- **`login(Request)`**: Valida credenciales (email o nombre de usuario), inicia sesi\u00f3n y redirige al dashboard. No hay verificaci\u00f3n de rol en el login — cualquier usuario autenticado accede, pero las rutas de administraci\u00f3n est\u00e1n protegidas por middleware.
- **`logout(Request)`**: Cierra sesi\u00f3n, invalida la sesi\u00f3n y redirige a `/`.

### `DashboardController` — P\u00e1gina Principal

- **`index(Request)`**: Lista todos los productos (con filtro de b\u00fasqueda) y cuenta cu\u00e1ntos lotes de producto est\u00e1n por vencer en los pr\u00f3ximos 7 d\u00edas. Retorna la vista `dashboard.index`.

### `ProductoController` — CRUD de Cat\u00e1logo

| M\u00e9todo | Ruta | Descripci\u00f3n |
|---|---|---|
| `index(Request)` | `GET /catalogos` | Lista productos con b\u00fasqueda |
| `create()` | `GET /catalogos/create` | Formulario de nuevo producto |
| `store(Request)` | `POST /catalogos` | Guarda nuevo producto (foto opcional) |
| `edit(Producto)` | `GET /catalogos/{id}/edit` | Formulario de edici\u00f3n |
| `update(Request, Producto)` | `PUT /catalogos/{id}` | Actualiza producto |
| `destroy(Producto)` | `DELETE /catalogos/{id}` | Eliminaci\u00f3n suave (soft delete) |

### `IngresoController` — Registro de Compras

| M\u00e9todo | Ruta | Descripci\u00f3n |
|---|---|---|
| `index()` | `GET /ingresos` | Historial de todos los ingresos |
| `create(Request)` | `GET /ingresos/create` | Formulario de nuevo ingreso (acepta `?producto_id=X` para preseleccionar) |
| `store(Request)` | `POST /ingresos` | Procesa el ingreso con la f\u00f3rmula de paquetes |
| `show(Ingreso)` | `GET /ingresos/{id}` | Detalle completo del ingreso |

**F\u00f3rmula de paquetes en `store()`:**

```php
$cantidadInicial = $cantidadPaquetes * $unidadesPorPaquete;
$precioUnitario = $precioPaquete / $unidadesPorPaquete;

// Crear detalle del ingreso
DetalleIngreso::create([
    'cantidad_paquetes' => $cantidadPaquetes,
    'unidades_por_paquete' => $unidadesPorPaquete,
    'cantidad_inicial' => $cantidadInicial,
    'stock_restante' => $cantidadInicial,
    'precio_paquete' => $precioPaquete,
    'precio_costo_unitario' => $precioUnitario,
    // ...
]);

// Actualizar stock del producto
$producto->increment('stock_total', $cantidadInicial);

// Registrar movimiento (auditor\u00eda)
MovimientoInventario::create([
    'tipo' => 'ingreso',
    'cantidad' => $cantidadInicial,
    // ...
]);
```

### `VentaController` — Registro de Ventas (FIFO)

| M\u00e9todo | Ruta | Descripci\u00f3n |
|---|---|---|
| `index()` | `GET /ventas` | Ventas del d\u00eda de hoy + total |
| `create()` | `GET /ventas/create` | Formulario de venta (muestra lotes con stock > 0 y no vencidos) |
| `store(Request)` | `POST /ventas` | Procesa la venta, descuenta FIFO del detalle_ingresos correspondiente |

**Validaci\u00f3n FIFO en `store()`:**

```php
// Verificar stock suficiente en cada lote
$detalleIngreso = DetalleIngreso::findOrFail($item['detalle_ingreso_id']);
if ($cantidad > $detalleIngreso->stock_restante) {
    return back()->with('error', "Stock insuficiente");
}

// Descontar del lote FIFO seleccionado
$detalleIngreso->decrement('stock_restante', $cantidad);

// Descontar del stock total del producto
$producto->decrement('stock_total', $cantidad);

// Registrar movimiento (auditor\u00eda)
MovimientoInventario::create(['tipo' => 'venta', 'cantidad' => -$cantidad, ...]);
```

### `AlertaController` — Alertas de Vencimiento

- **`index()`**: Consulta `detalle_ingresos` con `stock_restante > 0` y separa en dos grupos:
  - **Caducados**: `fecha_vencimiento < hoy`
  - **Por vencer**: `fecha_vencimiento` entre hoy y hoy + 7 d\u00edas
  Retorna la vista `alertas.index`.

### `CajaController` — Cierre Diario

- **`index()`**: Genera datos para un gr\u00e1fico SVG de los \u00faltimos 15 d\u00edas. Por cada d\u00eda calcula el total de ventas y la venta m\u00e1xima para escalar el gr\u00e1fico. Retorna la vista `caja.index`.

### `HistorialController` — Auditor\u00eda

- **`index()`**: Lista los \u00faltimos 100 movimientos de inventario ordenados por fecha descendente. Retorna la vista `historial.index`.

### `AdminController` — Panel de Administraci\u00f3n

- **`index()`**: Retorna la vista `admin.index` con enlaces a gesti\u00f3n de usuarios.

### `Admin\UsuarioController` — CRUD de Usuarios (solo admin)

| M\u00e9todo | Ruta | Descripci\u00f3n |
|---|---|---|
| `index()` | `GET /admin/usuarios` | Lista todos los usuarios |
| `create()` | `GET /admin/usuarios/create` | Formulario de creaci\u00f3n |
| `store(Request)` | `POST /admin/usuarios` | Crea usuario (no pasa `role` — la BD asigna `colaborador` por defecto) |
| `edit(User)` | `GET /admin/usuarios/{id}/edit` | Formulario de edici\u00f3n |
| `update(Request, User)` | `PUT /admin/usuarios/{id}` | Actualiza usuario (el rol nunca se modifica) |
| `destroy(User)` | `DELETE /admin/usuarios/{id}` | Elimina usuario (no permite autoeliminarse) |

### `CategoriaController` — API Interna

- **`index()`**: Retorna todas las categor\u00edas como JSON (usada internamente para poblar selects).
- **`store(Request)`**: Crea una nueva categor\u00eda.

---

## Rutas (`routes/web.php`)

Todas las rutas est\u00e1n en `web.php`, agrupadas por middleware:

```php
// Visitantes (no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/', [AuthController::class, 'login']);
});

// Usuarios autenticados (admin + colaboradores)
Route::middleware('auth')->group(function () {
    Route::post('/logout', ...)->name('logout');
    Route::get('/dashboard', ...)->name('dashboard');
    Route::resource('/catalogos', ProductoController::class)->names('catalogos.*');
    Route::resource('/ingresos', IngresoController::class)->names('ingresos.*');
    Route::resource('/ventas', VentaController::class)->names('ventas.*');
    Route::get('/alertas', ...)->name('alertas.index');
    Route::get('/caja', ...)->name('caja.index');
    Route::get('/historial', ...)->name('historial.index');
});

// Solo administradores
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', ...)->name('admin.index');
    Route::resource('/admin/usuarios', UsuarioController::class)->names('admin.usuarios.*');
});
```

Total: **30 rutas** (guest: 2 GET/POST + auth: 9 rutas + admin: 7 rutas + logout).

---

## Vistas (Blade)

### Layouts

#### `layouts/auth.blade.php`

Layout m\u00ednimo para la pantalla de inicio de sesi\u00f3n. No tiene barra de navegaci\u00f3n inferior. El body usa la clase `no-nav` para evitar el padding inferior del bottom-nav. Incluye el header con el logo de SRGIM y el nombre de la aplicaci\u00f3n. El contenido se renderiza dentro de un `<main class="login-wrapper">` centrado vertical y horizontalmente.

**Usado por:** `auth/login.blade.php`

#### `layouts/app.blade.php`

Layout principal del sistema. Incluye:
- **Header fijo** (`app-header`): Logo SRGIM a la izquierda, nombre del usuario autenticado, enlace "Administrador" (visible solo si el usuario es admin gracias a `@if(Auth::user()->isAdmin())`), y bot\u00f3n de cerrar sesi\u00f3n (POST a `route('logout')`).
- **Contenido principal** (`main.contenido`): Renderiza `@yield('content')` y muestra mensajes flash (`success` y `error`).
- **Bottom Nav** (`@include('partials.bottom-nav')`): Barra de navegaci\u00f3n inferior fija.
- El body tiene `padding-bottom: calc(var(--nav-alto) + 24px)` para evitar que el contenido quede detr\u00e1s del bottom-nav.

**Usado por:** dashboard, catalogos, ingresos, ventas, alertas, caja, historial.

#### `layouts/admin.blade.php`

Layout para las p\u00e1ginas de administraci\u00f3n. Similar a `app.blade.php` pero:
- El body usa la clase `no-nav` (sin padding inferior).
- No incluye el bottom-nav.
- Muestra el texto "Administrador" como etiqueta est\u00e1tica en el header.
- Incluye el bot\u00f3n de cerrar sesi\u00f3n.

**Usado por:** `admin/index.blade.php`, `admin/usuarios/{index,create,edit}.blade.php`

### Partial: Bottom Nav (`partials/bottom-nav.blade.php`)

Barra de navegaci\u00f3n inferior fija con 5 \u00edconos SVG:

| \u00cdcono | Ruta | Texto |
|---|---|---|
| 📁 (libro) | `route('dashboard')` | Cat\u00e1logo |
| 🛒 (carrito) | `route('ventas.index')` | Ventas |
| 🔔 (campana) | `route('alertas.index')` | Alertas |
| 💰 (caja registradora) | `route('caja.index')` | Caja |
| ⏰ (reloj) | `route('historial.index')` | Historial |

Cada enlace tiene la clase `activo` cuando la ruta actual coincide con el patr\u00f3n de la ruta (usando `request()->routeIs()`).

### Pantallas del Sistema

#### `auth/login.blade.php` — Inicio de Sesi\u00f3n

Formulario simple con campo de "Usuario o Correo" y "Contrase\u00f1a". El login acepta tanto email como nombre de usuario (`FILTER_VALIDATE_EMAIL` en el controlador). Dise\u00f1o centrado con sombra suave (`box-shadow: 0 15px 35px rgba(0,0,0,.10)`).

#### `dashboard/index.blade.php` — Dashboard Principal

Panel superior con dos informaciones:
- N\u00famero de productos por vencer en los pr\u00f3ximos 7 d\u00edas.
- Enlace r\u00e1pido "Agregar nuevo producto".

Barra de b\u00fasqueda para filtrar productos por nombre, y lista de productos con:
- Nombre, stock formateado seg\u00fan unidad de medida, y bot\u00f3n "+ Stock" que enlaza a `ingresos.create?producto_id=X`.
- Bot\u00f3n inferior "Agregar Stock / Registrar Compra".

#### `catalogos/index.blade.php` — Cat\u00e1logo de Productos

Lista completa de productos con:
- Barra de t\u00edtulo con bot\u00f3n "Nuevo".
- B\u00fasqueda por nombre.
- Cada producto: icono, nombre (click para editar), stock formateado, categor\u00eda, y bot\u00f3n "+ Stock".

#### `catalogos/create.blade.php` — Nuevo Producto

Formulario dentro de un `form-card` con:
- Nombre del producto.
- Categor\u00eda (select poblado desde BD).
- Foto (file upload).
- Unidad de medida (select: UN, KG, LT, MTS).
- Botones Guardar / Cancelar.

#### `catalogos/edit.blade.php` — Editar Producto

Mismo formulario que create pero con datos precargados. Agrega un recuadro que muestra el stock actual del producto con un bot\u00f3n "Agregar Stock" que enlaza a `ingresos.create?producto_id=X`.

#### `ingresos/index.blade.php` — Historial de Ingresos

Lista de todos los ingresos registrados, cada uno mostrando:
- ID del ingreso, fecha, total, nombre del usuario que registr\u00f3, y bot\u00f3n "Ver" que enlaza al detalle.

#### `ingresos/create.blade.php` — Nuevo Ingreso

Formulario de ingreso con la f\u00f3rmula de paquetes:
- Selector de producto (preseleccionado si viene `?producto_id=X`).
- Campo "Cant. paquetes" × "Unid. x paquete" → c\u00e1lculo en vivo del stock resultante.
- Campo "S/ x paquete" → c\u00e1lculo en vivo del precio unitario.
- Fecha de vencimiento.
- JS: `calcFila(i)` actualiza en tiempo real `= total (UM)` y `S/. unitario c/UM`.
- Bot\u00f3n "Agregar otro producto" duplica la fila con `agregarFila()`.
- El bot\u00f3n Cancelar regresa a `catalogos.index` si viene de un producto, o a `dashboard` si es ingreso directo.

#### `ingresos/show.blade.php` — Detalle de Ingreso

Resumen: fecha, total, usuario que registr\u00f3. Luego lista cada producto del ingreso con:
- Paquetes × UMP, cantidad total, stock restante, precio por paquete, precio unitario, fecha de vencimiento.
- Bot\u00f3n "Volver al inicio".

#### `ventas/index.blade.php` — Ventas del D\u00eda

Resumen del d\u00eda con:
- Fecha formateada en espa\u00f1ol (`now()->locale('es')->isoFormat('dddd D [de] MMMM')`).
- Total de ventas del d\u00eda en S/.
- Conteo de ventas.
- Lista de ventas del d\u00eda con producto, cantidad, precio unitario, precio total.
- Bot\u00f3n "Nueva Venta".

#### `ventas/create.blade.php` — Nueva Venta (FIFO)

Formulario con filas din\u00e1micas:
- Selector de lote de producto (producto, unidad, stock disponible, fecha de vencimiento).
- Cantidad a vender (step 0.001 para fracciones).
- Precio unitario de venta.
- JS: `agregarFila()` clona la fila, incrementa el \u00edndice, y agrega bot\u00f3n eliminar.
- Validaci\u00f3n server-side: verifica stock suficiente en el lote seleccionado antes de procesar.

#### `alertas/index.blade.php` — Alertas de Vencimiento

Dos secciones:
1. **Caducados**: Productos con `fecha_vencimiento < hoy` y stock restante > 0. Badge rojo "Caducado".
2. **Por vencer**: Productos con vencimiento en los pr\u00f3ximos 7 d\u00edas. Badge amarillo con n\u00famero de d\u00edas restantes.

#### `caja/index.blade.php` — Cierre Diario

Gr\u00e1fico SVG de l\u00ednea (scatter plot) con los \u00faltimos 15 d\u00edas:
- Eje Y: monto de ventas (escalado al m\u00e1ximo).
- Eje X: fechas con formato "d M" (ej: "5 Jul").
- Cada punto es un c\u00edrculo relleno (`<circle class="dot">`) con radio 6.

#### `historial/index.blade.php` — Auditor\u00eda

Lista de los \u00faltimos 100 movimientos de inventario con:
- Nombre del producto, fecha/hora, tipo (Ingreso/Venta/Anulaci\u00f3n) con badge de color correspondiente.

#### `admin/index.blade.php` — Panel de Administraci\u00f3n

Men\u00fa con dos opciones:
- "Agregar Nuevo Usuario" → `admin.usuarios.create`
- "Ver Lista de Usuarios" → `admin.usuarios.index`
- Bot\u00f3n "Volver al inicio" → `dashboard`

#### `admin/usuarios/index.blade.php` — Lista de Usuarios

Lista de todos los usuarios con:
- Avatar con icono de persona.
- Nombre y email.
- Botones "Editar" y "Eliminar" (con confirmaci\u00f3n).
- Bot\u00f3n inferior "Agregar Nuevo Usuario".

#### `admin/usuarios/create.blade.php` — Crear Usuario

Formulario dentro de un `form-card` con:
- Nombre de usuario, contrase\u00f1a (con confirmaci\u00f3n), correo electr\u00f3nico.
- Cada campo tiene un icono SVG decorativo a la izquierda.
- El rol se asigna autom\u00e1ticamente como `colaborador` (no hay selector de rol).

#### `admin/usuarios/edit.blade.php` — Editar Usuario

Mismo formulario que create pero con datos precargados. La contrase\u00f1a es opcional (dejar vac\u00edo para mantener la actual). El rol del usuario nunca se modifica desde este formulario.

---

## Sistema de Roles

SRGIM tiene dos roles definidos a nivel de base de datos con un `enum`:

```sql
role ENUM('admin', 'colaborador') DEFAULT 'colaborador'
```

### Admin (1 \u00fanico usuario)

- `admin@tiendamilagritos.com` (usuario: `admin`)
- Acceso completo al sistema: cat\u00e1logo, ventas, alertas, caja, historial + panel de administraci\u00f3n.
- Puede crear, editar y eliminar usuarios.
- Puede ver el enlace "Administrador" en el header.

### Colaborador (todos los dem\u00e1s usuarios)

- Todos los usuarios creados desde el panel son colaboradores por defecto (la BD asigna `colaborador` autom\u00e1ticamente).
- Acceso a: cat\u00e1logo, ventas, alertas, caja, historial.
- **No** pueden acceder a ninguna ruta `/admin/*` (redirigidos al dashboard con mensaje de error).
- **No** ven el enlace "Administrador" en el header.
- **No** ven el layout de administraci\u00f3n.

### Protecci\u00f3n por Middleware

El middleware `AdminMiddleware` (`app/Http/Middleware/AdminMiddleware.php`) verifica:

```php
if (!Auth::check() || !Auth::user()->isAdmin()) {
    return redirect()->route('dashboard')
        ->with('error', 'No tienes permisos de administrador.');
}
```

Registrado como alias `'admin'` en `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ]);
})
```

Aplicado a todas las rutas de administraci\u00f3n en `routes/web.php`:

```php
Route::middleware(['auth', 'admin'])->group(function () { ... });
```

### Vistas condicionales

En `layouts/app.blade.php`, el enlace "Administrador" se muestra solo si:

```blade
@if(Auth::user()->isAdmin())
    <a href="{{ route('admin.index') }}" class="header-role-link">
        <svg ...> <span>Administrador</span>
    </a>
@endif
```

---

## Estilos CSS (`resources/css/app.css`)

SRGIM utiliza **CSS personalizado sin ning\u00fan framework** (no Tailwind, no Bootstrap). El \u00fanico archivo CSS tiene aproximadamente 800 l\u00edneas.

### Paleta de Colores (variables CSS)

| Variable | Valor | Uso |
|---|---|---|
| `--verde` | `#D4E8D0` | Header, bot\u00f3n guardar/agregar |
| `--verde-dark` | `#C0DBBB` | Hover de botones verdes |
| `--crema` | `#FDF6DD` | Bottom nav, paneles, form-card, login |
| `--crema-dark` | `#F8EBB4` | Hover de botones crema |
| `--borde` | `#9AA3B5` | Bordes de todos los componentes (3px) |
| `--texto` | `#1A1A1A` | Color de texto principal |
| `--texto-gris` | `#788294` | Texto secundario, placeholders |
| `--blanco` | `#FFFFFF` | Fondo de body y cards |
| `--rosa` | `#F8CDD2` | Bot\u00f3n eliminar, badge caducado |
| `--rosa-dark` | `#F5B0B7` | Hover de botones rosa |
| `--azul` | `#C4D1E7` | Hover de opciones admin, focus inputs |

### Sistema de Sombras y Bordes

- **Bordes**: Todos los componentes tienen `border: 3px solid var(--borde)`.
- **Sombras**: `--shadow-sm: 0 2px 4px rgba(154,163,181,0.15)`, `--shadow-md: 0 4px 10px rgba(154,163,181,0.25)`.
- **Hard shadows**: Los botones tienen `box-shadow: 2px 2px 0px var(--borde)` que se agranda a `3px 3px` en hover.
- **Form-card pseudo-element**: El `::after` del `.form-card` crea un efecto de capa superpuesta (offset -8px, -8px) que simula una sombra gruesa.
- **Resumen-dia**: Mismo efecto con `::after` offset -6px, -6px.

### Tipograf\u00eda

Dos fuentes de Google Fonts:

```css
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');
```

- **`Plus Jakarta Sans`**: Texto general, inputs, botones (`font-family` base).
- **`Outfit`**: T\u00edtulos, header logo, bottom-nav links, tablas th (usos con `font-weight: 700` y `800`).

### Clases Principales

#### Header
- `.app-header`: Fijo arriba, fondo verde, borde inferior 3px.
- `.header-logo`: Logo con Outfit bold, icono SVG + texto.
- `.header-role`: Etiqueta de rol (admin) estilizada con fondo transl\u00facido.
- `.header-role-link`: Link/bot\u00f3n con estilo similar a `.header-role` pero interactivo.
- `.texto-oculto-movil`: Oculta texto en pantallas < 601px, visible desde 601px.

#### Bottom Nav
- `.bottom-nav`: Fijo abajo, fondo crema, borde superior 3px, flex con `justify-content: space-around`.
- `.bottom-nav a`: Enlaces con 20% de ancho, columna flex, transici\u00f3n.
- `.bottom-nav a.activo`: Borde superior 3px negro, fontWeight 800.

#### Cards y Tarjetas
- `.card`: Flex horizontal, borde 3px, padding 10-14px.
- `.tarjeta`: Similar a card pero con gap, usado en dashboard.
- `.venta-card`: Card de venta/ingreso/producto con hover que eleva (translateY -2px).
- `.usuario-card`: Card de usuario con avatar, nombre, acciones.

#### Botones
- `.btn`: Base con borde 3px, shadow 2px, transici\u00f3n.
- `.btn-guardar`: Verde.
- `.btn-editar`: Crema.
- `.btn-eliminar`: Rosa.
- `.btn-atras`: Crema.
- `.btn-agregar`: Verde con shadow 3px.
- `.btn-full`: Ancho completo.
- `.btn-cancelar`: Link sin borde, color gris.

#### Formularios
- `.form-card`: Card con fondo crema, borde 3px, border-radius 16px, padding, pseudo-element superpuesto.
- `.input-wrapper`: Contenedor relativo para input con icono.
- `.input-icon`: Icono posicionado absoluto a la izquierda del input.
- Inputs: Borde 3px, padding 14px, font-size 0.95rem.

#### Login
- `.login-wrapper`: Flex centrado (vertical + horizontal).
- `.login-card`: Card login con border-radius 20px, box-shadow suave.
- `.campo`: Margen inferior 20px.

#### Ventas
- `.resumen-dia`: Card de resumen con fecha, monto, conteo.
- `.subtitulo`: T\u00edtulo de secci\u00f3n con borde izquierdo 4px.

#### Responsive
- **max-width 900px**: Panel flex column, tarjeta font-size reducido.
- **max-width 768px**: Login card padding reducido.
- **max-width 600px**: M\u00faltiples ajustes: producto-card flex column, precio width 100%, barra-titulo flex column, usuario-card flex column, botones full width.
- **max-width 480px**: Font sizes reducidos, padding m\u00ednimo.
- **min-width 1024px**: Padding ampliado, max-width de form-card 480px.

---

## Flujo de Ingreso de Mercader\u00eda

1. El usuario selecciona "Agregar Stock" desde el dashboard, cat\u00e1logo o edici\u00f3n de producto.
2. Se muestra el formulario `ingresos.create` con la f\u00f3rmula **paquetes × UMP**.
3. El usuario completa:
   - Producto (preseleccionado si viene de un producto espec\u00edfico).
   - Cantidad de paquetes (ej: 1).
   - Unidades por paquete (ej: 50 para un saco de 50 KG).
   - Precio por paquete (ej: 180 soles).
   - Fecha de vencimiento.
4. El JavaScript muestra en vivo: `= 50 KG` y `S/. 3.60 c/KG`.
5. Al enviar, el controlador:
   - Calcula `cantidad_inicial = 1 × 50 = 50`.
   - Calcula `precio_costo_unitario = 180 / 50 = 3.60`.
   - Crea el `Ingreso` con el total (`180 × 1 = 180`).
   - Crea el `DetalleIngreso` con todos los campos.
   - Incrementa `producto.stock_total` en 50.
   - Registra un `MovimientoInventario` de tipo `ingreso` con cantidad +50.

---

## Flujo de Venta (FIFO)

1. El usuario selecciona "Nueva Venta" desde la pantalla de Ventas.
2. Se muestra el formulario `ventas.create` con todos los lotes (`detalle_ingresos`) que:
   - Tienen `stock_restante > 0`.
   - No est\u00e1n vencidos (`fecha_vencimiento >= today()`).
   - Ordenados por fecha de vencimiento ascendente (los m\u00e1s antiguos primero).
3. El usuario selecciona un lote (producto + stock disponible + vencimiento), ingresa cantidad y precio de venta.
4. Puede agregar m\u00faltiples productos a la misma venta.
5. Al enviar, el controlador:
   - Verifica stock suficiente en cada lote.
   - Calcula el total de la venta.
   - Crea la `Venta`.
   - Por cada producto: crea `DetalleVenta`, decrementa `detalle_ingreso.stock_restante`, decrementa `producto.stock_total`, registra `MovimientoInventario` con tipo `venta` y cantidad negativa.

---

## Alertas de Vencimiento

El controlador `AlertaController` ejecuta dos consultas:

1. **Caducados**:
   ```sql
   WHERE stock_restante > 0 AND fecha_vencimiento < CURDATE()
   ```
   Muestra el producto con badge rojo "Caducado".

2. **Por vencer (7 d\u00edas)**:
   ```sql
   WHERE stock_restante > 0 AND fecha_vencimiento BETWEEN CURDATE() AND CURDATE() + 7
   ```
   Muestra el producto con badge amarillo y n\u00famero de d\u00edas restantes.

El dashboard tambi\u00e9n muestra un contador de productos por vencer en los pr\u00f3ximos 7 d\u00edas.

---

## Cierre de Caja

El controlador `CajaController` genera datos para los \u00faltimos 15 d\u00edas:

```php
for ($i = $dias - 1; $i >= 0; $i--) {
    $fecha = Carbon::today()->subDays($i);
    $total = Venta::whereDate('created_at', $fecha)->sum('total_venta');
    $datosGrafico[] = ['fecha' => $fecha->format('Y-m-d'), 'total' => (float) $total];
    if ($total > $maxVenta) $maxVenta = $total;
}
```

La vista renderiza un SVG con c\u00edrculos (`<circle class="dot">`) posicionados seg\u00fan la escala `cy = 200 - (total / maxVenta * 150)`. Las etiquetas de fecha se muestran con formato "d M" debajo de cada punto.

---

## Auditor\u00eda (Historial)

Cada operaci\u00f3n de ingreso o venta crea un registro en `movimientos_inventarios`:

| Campo | Ingreso | Venta |
|---|---|---|
| `tipo` | `'ingreso'` | `'venta'` |
| `cantidad` | + valor positivo | - valor negativo |
| `producto_id` | Producto afectado | Producto afectado |
| `user_id` | Usuario que registra | Usuario que registra |

El `HistorialController` muestra los \u00faltimos 100 movimientos ordenados por fecha descendente, con badges de color:
- **Ingreso** → badge verde (`badge-ingreso`).
- **Venta** → badge amarillo (`badge-venta`).
- **Anulaci\u00f3n** → badge rosa (`badge-caducado`).

---

## Bug Fix: AlertaController (`use ($hoy)`)

El controlador de alertas ten\u00eda un error **"Undefined variable $hoy"** en el primer `map()`:

```php
$caducados = DetalleIngreso::with('producto')
    ->where('stock_restante', '>', 0)
    ->where('fecha_vencimiento', '<', $hoy)
    ->get()
    ->map(function ($d) use ($hoy) {  // ← Faltaba use ($hoy)
        $d->dias_restantes = $hoy->diffInDays($d->fecha_vencimiento, false);
        return $d;
    });
```

La variable `$hoy` (definida como `Carbon::today()`) no era accesible dentro del closure de `map()` sin pasar por `use ($hoy)`. La correcci\u00f3n fue agregar `use ($hoy)` en ambos closures (`caducados` y `porVencer`).

---

## Configuraci\u00f3n del Proyecto

### `composer.json`

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^13.8",
        "laravel/tinker": "^3.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.2.5",
        "laravel/pint": "^1.27",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^12.5.12"
    }
}
```

### `package.json`

```json
{
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^3.1",
        "tailwindcss": "^4.0.0",
        "vite": "^8.0.0"
    }
}
```

> **Nota:** Aunque TailwindCSS est\u00e1 en `package.json` como dependencia, **no se usa**. El plugin de Tailwind fue eliminado de `vite.config.js`. La instalaci\u00f3n de Tailwind es un remanente de la instalaci\u00f3n original de Laravel y se puede eliminar sin afectar el funcionamiento.

### `vite.config.js`

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### Sesiones

- **Driver**: `database` (configurado en `.env` como `SESSION_DRIVER=database`).
- Las sesiones se almacenan en la tabla `sessions` creada en la migraci\u00f3n de usuarios.

### Almacenamiento de Archivos

- **Disk**: `public` (configurado por defecto en Laravel).
- **Storage link**: `php artisan storage:link` crea `public/storage → storage/app/public`.
- Las fotos de productos se almacenan en `storage/app/public/productos/`.

---

## Licencia

Este proyecto es de c\u00f3digo abierto bajo la licencia MIT.
