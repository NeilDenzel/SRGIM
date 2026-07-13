# Product Backlog Completo — SRGIM App

**Sistema de Registro y Gestión de Inventario para Tienda Milagritos**

> **Leyenda:** ✅ Completado | 📋 Pendiente | 🔄 En progreso | 🐛 Bug

---

## Épica EP-01: Gestión de Productos e Inventario (34 pts) — Sprint 1 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-01 | **Gestión de Productos e Inventario** | Alta | 34 | 1 | Épica | Joseph Inga | Épica fundacional. Cubre el catálogo base y entradas de mercadería. Bloqueante de todas las demás épicas. |
| HU-01 | Como propietaria, quiero registrar un nuevo producto con nombre, categoría, foto opcional y unidad de medida, para mantener mi catálogo actualizado. | Alta | 8 | 1 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Ruta `GET/POST /catalogos`. Validación de campos obligatorios. Foto almacenada en `storage/app/public/productos/`. |
| HU-02 | Como propietaria, quiero registrar el ingreso de un lote de productos indicando cantidad de paquetes, unidades por paquete, precio por paquete y fecha de vencimiento, para actualizar el stock automáticamente. | Alta | 8 | 1 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Fórmula: `stock_inicial = paquetes × unidades_por_paquete`. `precio_unitario = precio_paquete / unidades_por_paquete`. Crea `MovimientoInventario` tipo `ingreso`. |
| HU-03 | Como propietaria, quiero visualizar el stock actual de cada producto en tiempo real, para saber qué mercadería tengo disponible. | Alta | 5 | 1 | Historia de Usuario | Neil Quispe | ✅ Implementado. Vista `dashboard/index` lista todos los productos con stock formateado (enteros para UN, 3 decimales para KG/LT/MTS). |
| HU-04 | Como propietaria, quiero editar o desactivar un producto del catálogo, para mantener la información precisa ante cambios de proveedor o descontinuaciones. | Media | 5 | 1 | Historia de Usuario | Pablo Román | ✅ Implementado. Soft-delete para no perder historial. Edición con reemplazo de foto. |
| HU-05 | Como propietaria, quiero buscar un producto por nombre o categoría, para encontrarlo rápidamente en el celular. | Media | 3 | 1 | Historia de Usuario | Neil Quispe | ✅ Implementado. Búsqueda por nombre vía `?buscar=` en dashboard y catálogo. Filtro en tiempo real. |
| HU-06 | Como propietaria, quiero recibir una confirmación visual antes de guardar cualquier dato crítico (nuevo ingreso o eliminación), para evitar errores de registro. | Alta | 5 | 1 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Confirmación JS `confirm()` en eliminaciones. Mensajes flash de éxito/error después de cada operación. |

---

## Épica EP-02: Registro de Ventas (24 pts) — Sprint 2 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-02 | **Registro de Ventas** | Alta | 24 | 2 | Épica | Joseph Inga | Permite controlar los egresos de stock y los ingresos de dinero diarios. Depende de EP-01. |
| HU-07 | Como propietaria, quiero registrar la venta de uno o varios productos seleccionando el lote específico (FIFO) e indicando la cantidad vendida, para actualizar el stock y calcular el ingreso de la operación. | Alta | 8 | 2 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Selección por lote con stock disponible y fecha de vencimiento. Descuenta de `detalle_ingresos.stock_restante` y `productos.stock_total`. Crea `MovimientoInventario` tipo `venta`. |
| HU-08 | Como propietaria, quiero que el sistema calcule automáticamente el precio de venta unitario a partir del precio de compra por paquete, para no tener que calcularlo manualmente. | Alta | 5 | 2 | Historia de Usuario | Pablo Román | ✅ Implementado. El `precio_costo_unitario` se calcula al crear el ingreso. En ventas, el precio de venta se carga automáticamente desde el formulario y es editable. |
| HU-09 | Como propietaria, quiero ver un resumen de las ventas realizadas durante el día en curso, para tener visibilidad del movimiento diario. | Alta | 5 | 2 | Historia de Usuario | Neil Quispe | ✅ Implementado. Vista `ventas/index` con: fecha en español, total de ventas del día, conteo, lista de ventas con producto/cantidad/precio. |
| HU-10 | Como propietaria, quiero anular una venta registrada por error durante el mismo día, para corregir registros incorrectos sin afectar el historial definitivo. | Media | 3 | 2 | Historia de Usuario | Pablo Román | ✅ Implementado. Botón "Anular" con confirmación. Restaura stock en `detalle_ingresos` y `productos`. Crea `MovimientoInventario` tipo `anulacion`. Solo ventas del mismo día. Ventas anuladas se muestran aparte con badge rojo. |

---

## Épica EP-03: Control de Vencimientos (13 pts) — Sprint 3 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-03 | **Control de Vencimientos** | Alta | 13 | 3 | Épica | Joseph Inga | Directamente ligada al KPI #1 de éxito: 0% de mercadería vencida en estantes. |
| HU-11 | Como propietaria, quiero recibir una alerta visual destacada cuando un producto esté a 7 días o menos de su fecha de vencimiento, para tomar acciones preventivas a tiempo. | Alta | 5 | 3 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Dashboard muestra contador `porVencer`. Vista `alertas/index` con secciones "Caducados" (rojo) y "Por vencer" (amarillo). |
| HU-12 | Como propietaria, quiero ver una lista de todos los productos próximos a vencer ordenados por urgencia, para priorizar su venta o retiro. | Alta | 5 | 3 | Historia de Usuario | Neil Quispe | ✅ Implementado. `AlertaController` ordena por `fecha_vencimiento`. Muestra días restantes. Vista con tarjetas por lote. |
| HU-13 | Como propietaria, quiero que los productos vencidos sean marcados automáticamente como 'No disponibles' en el inventario, para evitar que se registren ventas de productos caducados. | Media | 3 | 3 | Historia de Usuario | Pablo Román | ✅ Implementado. `VentaController@create` excluye lotes con `fecha_vencimiento < hoy`. No es posible vender productos vencidos. |

---

## Épica EP-04: Reportes y Control Financiero (21 pts) — Sprint 4 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-04 | **Reportes y Control Financiero** | Alta | 21 | 4 | Épica | Joseph Inga | Corresponde al KPI #3: cuadre de caja en menos de 5 minutos. |
| HU-14 | Como propietaria, quiero realizar el cierre diario de caja con un solo botón, y obtener un reporte con las ventas totales, el costo de lo vendido y la ganancia neta del día. | Alta | 8 | 4 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Vista `caja/index` muestra: Ventas totales, Costo de lo vendido (JOIN con `detalle_ingresos.precio_costo_unitario`), Ganancia neta. Botón "Imprimir Reporte". |
| HU-15 | Como propietaria, quiero visualizar un reporte quincenal y mensual de ganancias con los productos más vendidos, para entender las tendencias de mi negocio. | Alta | 8 | 4 | Historia de Usuario | Neil Quispe | ✅ Implementado. Pestañas "Últimos 15 días" / "Últimos 30 días" en `caja/index`. Sección "Productos más vendidos" con ranking por cantidad, ingreso, costo y ganancia por producto. Gráfico SVG de ventas diarias. |
| HU-16 | Como propietaria, quiero exportar o compartir el reporte de cierre diario (por ejemplo, como imagen o PDF simple), para guardarlo o enviarlo a un familiar. | Baja | 5 | 4 | Historia de Usuario | Pablo Román | ✅ Implementado. Botón "Imprimir Reporte" con `window.print()`. CSS `@media print` optimizado: oculta navegación, headers y botones. Muestra solo reporte + gráfico. |

---

## Épica EP-05: Historial de Movimientos (13 pts) — Sprint 4 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-05 | **Historial de Movimientos** | Media | 13 | 4 | Épica | Joseph Inga | Provee trazabilidad completa. Necesaria para auditoría interna. |
| HU-17 | Como propietaria, quiero consultar el historial completo de entradas (compras) y salidas (ventas) de un producto específico, para verificar cualquier movimiento pasado. | Media | 5 | 4 | Historia de Usuario | Pablo Román | ✅ Implementado. `HistorialController` lista últimos 100 movimientos con producto, tipo (ingreso/venta/anulación) y fecha. |
| HU-18 | Como propietaria, quiero ver el historial de todos los movimientos del día o de una semana específica, para resolver dudas sobre operaciones pasadas. | Media | 5 | 4 | Historia de Usuario | Neil Quispe | ✅ Implementado. Tabla `movimientos_inventarios` con timestamps. Vista cronológica descendente con badges de colores por tipo. |
| HU-19 | Como propietaria, quiero que el historial registre automáticamente quién realizó cada operación (si hay más de un usuario), para tener control sobre los registros del equipo familiar. | Baja | 3 | 4 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. `MovimientoInventario` incluye `user_id` FK. Cada creación de ingreso/venta/anulación guarda `auth()->id()`. |

---

## Épica EP-06: Usabilidad y Acceso al Sistema (18 pts) — Sprint 1 ✅

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-06 | **Usabilidad y Acceso al Sistema** | Alta | 18 | 1 | Épica | Joseph Inga | Transversal a todos los sprints. Sistema validado en cada Sprint Review. |
| HU-20 | Como usuaria sin conocimientos técnicos, quiero que la app funcione correctamente desde mi celular con datos móviles, sin necesidad de instalarla, para usarla en cualquier momento en la tienda. | Alta | 5 | 1 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Diseño mobile-first responsive. Breakpoints: 480px, 600px, 768px, 900px, 1024px+. Bottom nav fija. Sin frameworks JS. |
| HU-21 | Como usuaria, quiero que todos los textos, botones y mensajes estén en español simple y sin tecnicismos, para entender la app sin ayuda. | Alta | 3 | 1 | Historia de Usuario | Todo el equipo | ✅ Implementado. 100% textos en español (`es_PE`). Carbon fechas en español. Sin inglés técnico en UI. |
| HU-22 | Como usuaria, quiero navegar entre las secciones principales de la app con no más de 2 toques desde la pantalla principal, para operar rápido durante la atención al cliente. | Alta | 5 | 2 | Historia de Usuario | Neil Quispe / Pablo Román | ✅ Implementado. Bottom nav con 5 íconos: Catálogo, Ventas, Alertas, Caja, Historial. Un toque para cambiar de sección. |
| HU-23 | Como usuaria, quiero que la app me indique claramente cuando hay un error en mis datos (campo vacío, formato incorrecto), para saber exactamente qué corregir. | Media | 5 | 2 | Historia de Usuario | Pablo Román | ✅ Implementado. Validación con mensajes en lenguaje llano. Mensajes flash success/error con colores distintivos. |

---

## Épica EP-07: Calidad y Deuda Técnica (21 pts) — Sprint 5 📋

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-07 | **Calidad y Deuda Técnica** | Alta | 21 | 5 | Épica | Joseph Inga | Deuda técnica acumulada. Bloqueante para escalabilidad y confiabilidad del sistema. |
| HU-24 | Como desarrollador, quiero tener tests automatizados para los módulos críticos (ventas, ingresos, alertas), para prevenir regresiones al hacer cambios. | Alta | 8 | 5 | Historia de Usuario | Neil Quispe / Pablo Román | Test unitarios para modelos y feature tests para controladores. Priorizar: VentaController@store, IngresoController@store, AlertaController@index. |
| HU-25 | Como desarrollador, quiero que las listas del sistema (catálogo, ingresos, ventas, historial) usen paginación, para evitar tiempos de carga lentos con muchos registros. | Media | 5 | 5 | Historia de Usuario | Pablo Román | Implementar `->paginate(20)` en todos los controllers que usan `->get()`. Agregar `links()` en vistas. |
| SC-01 | Solicitud de cambio: Agregar validación de `unidad_medida` en el controlador de productos (`ProductoController@store` y `@update`). Actualmente el formulario envía el campo pero el controlador no lo valida. | Alta | 2 | 5 | Solicitud de Cambio | Neil Quispe | 🐛 Bug detectado en auditoría. `ProductoController@store` no incluye `unidad_medida` en `$request->validate()`, aunque el formulario tiene el campo `<select>`. Afecta integridad del stock. |
| SC-02 | Solicitud de cambio: Agregar CRUD completo de categorías desde la interfaz de usuario (rutas web + vistas Blade). Actualmente `CategoriaController` existe pero solo tiene endpoint JSON sin rutas web. | Media | 5 | 5 | Solicitud de Cambio | Pablo Román | Crear rutas `GET/POST /categorias`, `PUT /categorias/{id}`, `DELETE /categorias/{id}` con vistas para gestión desde el panel admin. |
| SC-03 | Solicitud de cambio: Agregar vista de detalle de venta individual (`GET /ventas/{venta}`) con todos los productos, cantidades, subtotales y usuario que registró. | Media | 3 | 5 | Solicitud de Cambio | Neil Quispe | Actualmente `ventas/show.blade.php` no existe. La vista `ventas/index` solo muestra el primer producto de cada venta. |
| SC-04 | Solicitud de cambio: Agregar respaldo automático de la base de datos SQLite vía Artisan command programado con cron. | Alta | 2 | 5 | Solicitud de Cambio | Rodolfo Durán (SM) | Crear `php artisan backup:database` que copie `database/database.sqlite` a `storage/backups/` con timestamp. |

---

## Épica EP-08: Experiencia de Usuario y Funcionalidades Complementarias (21 pts) — Sprint 5–6 📋

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-08 | **Experiencia de Usuario y Funcionalidades Complementarias** | Media | 21 | 5–6 | Épica | Joseph Inga | Mejoras que aumentan el valor percibido sin cambiar la arquitectura base. |
| HU-26 | Como propietaria, quiero ver estadísticas clave en el dashboard al abrir la app (ventas del día, productos bajos en stock, alertas activas), para tener un panorama rápido del negocio. | Alta | 5 | 5 | Historia de Usuario | Neil Quispe | Dashboard actual solo muestra lista de productos y contador de próximos a vencer. Agregar tarjetas con: ventas de hoy, total productos, stock bajo (<5 unidades), alertas activas. |
| HU-27 | Como propietaria, quiero filtrar y buscar en el historial de ingresos, ventas y movimientos por fecha, producto o rango, para encontrar operaciones específicas rápidamente. | Media | 8 | 6 | Historia de Usuario | Pablo Román | Implementar formulario de filtros combinados en `IngresoController@index`, `VentaController@index` (histórico, no solo hoy) e `HistorialController@index`. |
| HU-28 | Como propietaria, quiero ver el detalle de un producto específico con su historial completo de movimientos (ingresos y ventas), para entender su rotación. | Baja | 5 | 6 | Historia de Usuario | Neil Quispe | Nueva ruta `GET /productos/{producto}` con vista que muestre datos del producto + tabla de movimientos asociados. |
| HU-29 | Como propietaria, quiero que la aplicación tenga un modo claro/oscuro que se adapte a la iluminación de la tienda, para reducir la fatiga visual durante la jornada laboral. | Baja | 3 | 6 | Historia de Usuario | Pablo Román | Implementar con CSS custom properties y un toggle. Persistir preferencia en localStorage. |

---

## Épica EP-09: Funcionalidades Avanzadas (21 pts) — Sprint 6+ 📋

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| EP-09 | **Funcionalidades Avanzadas** | Baja | 21 | 6+ | Épica | Joseph Inga | Requieren análisis técnico adicional. Posibles candidatos para release 2.0. |
| HU-30 | Como propietaria, quiero que la app funcione sin conexión a Internet (modo offline), para usarla cuando falle el internet en el local. | Baja | 8 | 6 | Historia de Usuario | Neil Quispe / Pablo Román | Evaluar Service Worker + cache de assets (PWA). Sincronización al reconectar. IndexedDB para datos offline. Hereda mitigación de RG-02. |
| HU-31 | Como propietaria, quiero recibir una notificación en el celular cuando un producto esté por vencer, para tomar acción sin tener que abrir la app. | Baja | 5 | 6 | Historia de Usuario | Pablo Román | Implementar notificaciones push vía Service Worker o integración con WhatsApp API. Alternativa: recordatorio por correo usando Laravel Notifications. |
| HU-32 | Como administradora, quiero gestionar múltiples sucursales o puntos de venta desde la misma app, para expandir el negocio si lo necesito. | Baja | 8 | Futuro | Historia de Usuario | Joseph Inga | Requiere rediseño de esquema: agregar tabla `sucursales`, FK en `ventas` e `ingresos`. Evaluar viabilidad y alcance. |

---

## Riesgos

| Código | Épicas / Historias de Usuario / Solicitudes de Cambio / Riesgos | Prioridad | Estimación (Puntos de Historia) | Sprint Comprometido | Tipo | Responsable | Observaciones |
|--------|------------------------------------------------------------------|-----------|---------------------------------|---------------------|------|-------------|---------------|
| RG-01 | Resistencia al cambio tecnológico por parte de la propietaria o familiares al adoptar la herramienta digital. | Alta | 3 | 1 | Riesgo | Rodolfo Durán (SM) | ✅ Mitigado. Sprint Reviews presenciales con demo en vivo. Capacitación guiada. Interfaz ultra-simple (EP-06). |
| RG-02 | Inestabilidad o falta de conexión a Internet en el local comercial, afectando la disponibilidad del aplicativo web. | Alta | 3 | 1 | Riesgo | Rodolfo Durán (SM) | ✅ Mitigado parcialmente. App funciona con datos móviles. Plan B: Wi-Fi local. Pendiente: modo offline real (HU-30). |
| RG-03 | Registro incompleto o incorrecto de datos (error humano), afectando la confiabilidad de reportes financieros. | Media | 2 | 1 | Riesgo | Rodolfo Durán (SM) | ✅ Mitigado. Confirmaciones visuales (HU-06). Validaciones de campo (HU-23). Anulación de ventas (HU-10). |
| RG-04 | Aumento de costos de infraestructura en la nube si el alcance técnico se expande sin control. | Media | 1 | 1 | Riesgo | Joseph Inga (PO) | ✅ Mitigado. Budget fijo de S/ 200 para hosting. PO controla solicitudes de cambio. |
| RG-05 | Dependencia del hosting/túnel actual (sharedwithexpose.com) para desarrollo. Riesgo de discontinuidad del servicio gratuito. | Media | 1 | 5 | Riesgo | Joseph Inga (PO) | 📋 Pendiente. Mitigación: Documentar deploy en hosting propio. Evaluar Railway/Netlify para producción. |
| RG-06 | Sin respaldo automatizado de la base de datos SQLite. Riesgo de pérdida de datos ante fallo del servidor o corrupción de archivo. | Alta | 2 | 5 | Riesgo | Rodolfo Durán (SM) | 📋 Pendiente. Mitigación: Crear Artisan command `backup:database` y programar cron diario. |

---

## Resumen de Capacidad por Sprint

| Sprint | Puntos totales | Épicas | Items |
|--------|---------------|--------|-------|
| **Sprint 1** | 52 pts | EP-01 (34) + EP-06 (18) | HU-01 a HU-06 + HU-20 a HU-23 |
| **Sprint 2** | 29 pts | EP-02 (24) + complementos EP-06 (5) | HU-07 a HU-10 + HU-22, HU-23 |
| **Sprint 3** | 13 pts | EP-03 (13) | HU-11 a HU-13 |
| **Sprint 4** | 34 pts | EP-04 (21) + EP-05 (13) | HU-14 a HU-19 |
| **Sprint 5** | ~26 pts | EP-07 (15) + EP-08 (8) + RG-05, RG-06 (3) | HU-24, HU-25, HU-26 + SC-01, SC-02, SC-03, SC-04 |
| **Sprint 6** | ~21 pts | EP-08 (13) + EP-09 (8) | HU-27, HU-28, HU-29, HU-30, HU-31 |
| **Futuro** | ~8 pts | EP-09 (8) | HU-32 |

---

## Estadísticas del Proyecto

| Métrica | Valor |
|---------|-------|
| Total de épicas | 9 |
| HU completadas | 23 de 32 |
| HU pendientes | 9 |
| Solicitudes de cambio | 4 |
| Riesgos mitigados | 4 de 6 |
| Puntos de historia completados | ~125 |
| Puntos de historia pendientes | ~47 |
| Sprints ejecutados | 4 |
| Velocity promedio | ~31 pts/sprint |
| Tech stack | Laravel 13, Blade, CSS custom, SQLite, Vite |
| Líneas de código (CSS) | ~833 |
| Controladores | 12 |
| Vistas Blade | 21 |
| Modelos | 8 |
| Migraciones | 10 |
| Tests | 2 (básicos, por defecto de Laravel) |
