# 📋 Backend Implementation Status - DairyFlow (MamaBoba)

**Fecha:** 2026-02-11  
**Backend URL:** `http://100.101.21.82:8000/api`  
**Estado:** En desarrollo

---

## 📊 Resumen General

Este documento identifica las funcionalidades del backend que faltan por implementar para el proyecto DairyFlow. El frontend está desarrollado en React + TypeScript + Vite, y consume una API REST desde la URL configurada en `.env`.

### Estado Actual

- ✅ **Frontend completo** con diseño e interfaces
- ⚠️ **Backend parcialmente implementado**
- ⚠️ **Algunas páginas aún usan mocks** en lugar de datos reales

---

## 🔍 Análisis de Endpoints por Módulo

### 1. **Animales (Animals)** 🐄

#### Endpoints Implementados (Frontend los consume)

| Método | Endpoint           | Hook              | Estado          |
| ------ | ------------------ | ----------------- | --------------- |
| GET    | `/api/animals`     | `useAnimals`      | ✅ Implementado |
| POST   | `/api/animals`     | `useCreateAnimal` | ✅ Implementado |
| PUT    | `/api/animals/:id` | `useUpdateAnimal` | ✅ Implementado |
| DELETE | `/api/animals/:id` | `useDeleteAnimal` | ✅ Implementado |

#### Estructura de Datos Esperada

**Respuesta GET `/api/animals`:**

```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "tag": "string | null",
      "breed": "string | null",
      "age": "number | null",
      "weight": "number | null",
      "healthStatus": "healthy | sick | attention",
      "lastMilking": "ISO 8601 string | null",
      "totalProduction": "string (numeric)",
      "averageDaily": "string (numeric)",
      "image": "string URL | null"
    }
  ]
}
```

**Payload POST/PUT `/api/animals`:**

```json
{
  "name": "string",
  "tag": "string",
  "breed": "string",
  "age": "number",
  "weight": "number (opcional)",
  "health_status": "healthy | attention | sick",
  "image": "string URL | null (opcional)"
}
```

#### ⚠️ Puntos a Verificar

- [ ] Validar que el campo `totalProduction` se calcula correctamente desde sesiones de ordeño
- [ ] Validar que el campo `averageDaily` se actualiza en tiempo real
- [ ] Verificar manejo de imágenes (URLs válidas o null)
- [ ] Implementar actualización automática de `lastMilking` al crear sesiones

---

### 2. **Sesiones de Ordeño (Milking Sessions)** 🥛

#### Endpoints Implementados

| Método | Endpoint                                      | Hook                      | Estado          |
| ------ | --------------------------------------------- | ------------------------- | --------------- |
| GET    | `/api/milking-sessions?per_page=20&sort=desc` | `useMilkingSessions`      | ✅ Implementado |
| POST   | `/api/milking-sessions`                       | `useCreateMilkingSession` | ✅ Implementado |

#### Estructura de Datos Esperada

**Respuesta GET `/api/milking-sessions`:**

```json
{
  "data": [
    {
      "id": "string",
      "animalId": "string",
      "animalName": "string",
      "animalTag": "string",
      "date": "YYYY-MM-DD",
      "startTime": "HH:MM",
      "endTime": "HH:MM",
      "milk_yield": "number",
      "quality": "excellent | good | fair | poor",
      "temperature": "number",
      "notes": "string | null"
    }
  ]
}
```

**Payload POST `/api/milking-sessions`:**

```json
{
  "animal_id": "string",
  "date": "YYYY-MM-DD",
  "start_time": "HH:MM",
  "end_time": "HH:MM",
  "milk_yield": "number",
  "quality": "excellent | good | fair | poor",
  "temperature": "number",
  "notes": "string | null (opcional)"
}
```

#### ⚠️ Funcionalidades Faltantes

- [ ] **Endpoint UPDATE** - No existe hook para editar sesiones existentes
- [ ] **Endpoint DELETE** - No existe hook para eliminar sesiones
- [ ] Parámetros de paginación (`per_page`, `sort`) deben funcionar correctamente
- [ ] Validar que `animalName` y `animalTag` se obtienen por JOIN con tabla `animals`
- [ ] Cálculo de duración (actualmente se hace en frontend)
- [ ] Validación de rangos de tiempo (end_time > start_time)

---

### 3. **Alertas (Alerts)** 🔔

#### Endpoints Implementados

| Método | Endpoint      | Hook        | Estado          |
| ------ | ------------- | ----------- | --------------- |
| GET    | `/api/alerts` | `useAlerts` | ✅ Implementado |

#### Estructura de Datos Esperada

**Respuesta GET `/api/alerts`:**

```json
{
  "data": [
    {
      "id": "string",
      "type": "health | schedule | production | maintenance",
      "severity": "low | medium | high",
      "message": "string",
      "animal_id": "string | null",
      "date": "ISO 8601 string",
      "resolved": "boolean"
    }
  ]
}
```

#### ⚠️ Funcionalidades Faltantes

- [ ] **Endpoint POST** - Crear alertas manualmente
- [ ] **Endpoint PATCH** - Marcar alertas como resueltas (`resolved: true`)
- [ ] **Endpoint DELETE** - Eliminar alertas
- [ ] **Sistema automático de alertas** basado en reglas:
  - Detección de baja producción
  - Recordatorios de sesiones programadas
  - Alertas de salud (animales con estado `attention` o `sick`)
  - Mantenimiento de equipos
- [ ] Implementar `refetchInterval` en backend (polling cada 30s según hook)

---

### 4. **Reportes (Reports)** 📊

#### Endpoints Implementados

| Método | Endpoint       | Hook         | Estado          |
| ------ | -------------- | ------------ | --------------- |
| GET    | `/api/reports` | `useReports` | ✅ Implementado |

#### Estructura de Datos Esperada

**Respuesta GET `/api/reports`:**

```json
{
  "dailyProduction": [
    {
      "date": "YYYY-MM-DD",
      "totalYield": "number",
      "sessionCount": "number",
      "averageYield": "number",
      "qualityDistribution": {
        "excellent": "number",
        "good": "number",
        "fair": "number",
        "poor": "number"
      }
    }
  ],
  "qualityStats": {
    "excellent": "number",
    "good": "number",
    "fair": "number",
    "poor": "number"
  },
  "animals": [
    /* Array de animales completo */
  ],
  "sessions": [
    /* Array de sesiones completo */
  ],
  "sessionsTotal": "number"
}
```

#### ⚠️ Funcionalidades Faltantes

- [ ] **Filtros por rango de fechas** - El endpoint no acepta parámetros `start_date` y `end_date`
- [ ] **Exportación a PDF** - Debe implementarse en backend
- [ ] **Exportación a CSV** - Debe implementarse en backend
- [ ] Optimización de consultas (el endpoint devuelve TODOS los animales y sesiones)
- [ ] Agregar métricas adicionales:
  - Mejor animal productor del período
  - Tendencias de producción (week-over-week, month-over-month)
  - Estadísticas de salud del ganado

---

### 5. **Usuarios (Users)** 👥

#### Estado Actual

> ⚠️ **CRÍTICO:** La página de usuarios **TODAVÍA USA MOCK DATA** en lugar de consumir la API.

**Evidencia:**

```typescript
// src/pages/Users.tsx línea 7
import { mockUsers, mockPermissions, mockUserActivity } from "../data/mockData";
```

#### Endpoints que DEBEN Implementarse

| Método | Endpoint                | Hook Existente                      | Estado           |
| ------ | ----------------------- | ----------------------------------- | ---------------- |
| GET    | `/api/users`            | `useUsers`                          | ❌ **NO SE USA** |
| POST   | `/api/users`            | `useUserMutations.createUser`       | ❌ **NO SE USA** |
| PUT    | `/api/users/:id`        | `useUserMutations.updateUser`       | ❌ **NO SE USA** |
| DELETE | `/api/users/:id`        | `useUserMutations.deleteUser`       | ❌ **NO SE USA** |
| PATCH  | `/api/users/:id/status` | `useUserMutations.toggleUserStatus` | ❌ **NO SE USA** |

#### Estructura de Datos Esperada

**Respuesta GET `/api/users`:**

```json
{
  "data": [
    {
      "id": "string",
      "name": "string",
      "email": "string",
      "phone": "string | null",
      "role": "admin | manager | operator | viewer",
      "status": "active | inactive | pending",
      "department": "string",
      "joinDate": "ISO 8601 string",
      "lastAccess": "ISO 8601 string | null"
    }
  ]
}
```

**Payload POST/PUT `/api/users`:**

```json
{
  "name": "string",
  "email": "string",
  "phone": "string (opcional)",
  "role": "admin | manager | operator | viewer",
  "status": "active | inactive | pending",
  "department": "string",
  "permissions": [
    /* Array de Permission IDs */
  ],
  "joinDate": "ISO 8601 string",
  "lastAccess": "ISO 8601 string (opcional)"
}
```

#### ⚠️ Tareas Críticas para Usuarios

- [ ] **Migrar Users.tsx de mocks a API real**
- [ ] Implementar autenticación real (actualmente es mock en `App.tsx`)
- [ ] Sistema de permisos:
  - [ ] Endpoint GET `/api/permissions` para listar permisos disponibles
  - [ ] Relación users-permissions en base de datos
  - [ ] Validación de permisos en cada endpoint del backend
- [ ] Registro de actividad:
  - [ ] Endpoint GET `/api/user-activity` para logs de actividad
  - [ ] Sistema de auditoría automático en backend
- [ ] Validaciones:
  - [ ] No permitir eliminar el último administrador
  - [ ] No permitir desactivar el último administrador activo
  - [ ] Validar email único
  - [ ] Validar roles permitidos

---

### 6. **Autenticación (Auth)** 🔐

#### Estado Actual

> ⚠️ **CRÍTICO:** La autenticación es completamente MOCK.

**Evidencia:**

```typescript
// src/App.tsx líneas 28-42
const handleLogin = async (
  email: string,
  password: string,
): Promise<boolean> => {
  await new Promise((resolve) => setTimeout(resolve, 1500));
  const user = mockUsers.find((u) => u.email === email);
  if (user && password.length >= 6) {
    setCurrentUser(user);
    setIsAuthenticated(true);
    return true;
  }
  return false;
};
```

#### Endpoints que DEBEN Implementarse

| Método | Endpoint             | Descripción                             | Estado      |
| ------ | -------------------- | --------------------------------------- | ----------- |
| POST   | `/api/auth/login`    | Inicio de sesión con email y contraseña | ❌ Faltante |
| POST   | `/api/auth/logout`   | Cerrar sesión (invalidar token)         | ❌ Faltante |
| POST   | `/api/auth/register` | Registro de nuevos usuarios             | ❌ Faltante |
| GET    | `/api/auth/me`       | Obtener usuario autenticado actual      | ❌ Faltante |
| POST   | `/api/auth/refresh`  | Renovar token de acceso                 | ❌ Faltante |

#### Estructura de Autenticación Recomendada

**POST `/api/auth/login`:**

```json
// Request
{
  "email": "string",
  "password": "string"
}

// Response
{
  "user": {
    "id": "string",
    "name": "string",
    "email": "string",
    "role": "admin | manager | operator | viewer",
    "permissions": [ /* Array de Permission objects */ ]
  },
  "token": "JWT token string",
  "refreshToken": "string"
}
```

#### ⚠️ Tareas Críticas de Autenticación

- [ ] Implementar JWT (JSON Web Tokens) o sesiones seguras
- [ ] Hash de contraseñas (bcrypt, argon2)
- [ ] Middleware de autenticación en TODOS los endpoints protegidos
- [ ] Actualizar `api.ts` para incluir token en headers:
  ```typescript
  api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
  });
  ```
- [ ] Manejo de tokens expirados (refresh automático)
- [ ] Protección contra CSRF
- [ ] Rate limiting para evitar ataques de fuerza bruta

---

## 🗄️ Base de Datos - Esquema Requerido

### Tablas Principales

#### 1. `animals`

```sql
CREATE TABLE animals (
  id VARCHAR(36) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  tag VARCHAR(100) UNIQUE,
  breed VARCHAR(100),
  age INT,
  weight DECIMAL(8,2),
  health_status ENUM('healthy', 'attention', 'sick') DEFAULT 'healthy',
  image VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

#### 2. `milking_sessions`

```sql
CREATE TABLE milking_sessions (
  id VARCHAR(36) PRIMARY KEY,
  animal_id VARCHAR(36) NOT NULL,
  date DATE NOT NULL,
  start_time TIME NOT NULL,
  end_time TIME NOT NULL,
  milk_yield DECIMAL(8,2) NOT NULL,
  quality ENUM('excellent', 'good', 'fair', 'poor') NOT NULL,
  temperature DECIMAL(4,1) NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE CASCADE
);
```

#### 3. `alerts`

```sql
CREATE TABLE alerts (
  id VARCHAR(36) PRIMARY KEY,
  type ENUM('health', 'schedule', 'production', 'maintenance') NOT NULL,
  severity ENUM('low', 'medium', 'high') NOT NULL,
  message TEXT NOT NULL,
  animal_id VARCHAR(36),
  resolved BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  resolved_at TIMESTAMP NULL,
  FOREIGN KEY (animal_id) REFERENCES animals(id) ON DELETE SET NULL
);
```

#### 4. `users`

```sql
CREATE TABLE users (
  id VARCHAR(36) PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('admin', 'manager', 'operator', 'viewer') NOT NULL,
  status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
  department VARCHAR(100) NOT NULL,
  avatar VARCHAR(500),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_login TIMESTAMP NULL
);
```

#### 5. `permissions`

```sql
CREATE TABLE permissions (
  id VARCHAR(36) PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL,
  description TEXT,
  category ENUM('animals', 'milking', 'reports', 'users', 'system') NOT NULL
);
```

#### 6. `user_permissions`

```sql
CREATE TABLE user_permissions (
  user_id VARCHAR(36) NOT NULL,
  permission_id VARCHAR(36) NOT NULL,
  PRIMARY KEY (user_id, permission_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);
```

#### 7. `user_activity`

```sql
CREATE TABLE user_activity (
  id VARCHAR(36) PRIMARY KEY,
  user_id VARCHAR(36) NOT NULL,
  action VARCHAR(100) NOT NULL,
  description TEXT,
  ip_address VARCHAR(45),
  user_agent TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Vistas para Optimización

#### Vista para totalProduction y averageDaily

```sql
CREATE VIEW animal_production_stats AS
SELECT
  a.id,
  a.name,
  a.tag,
  COALESCE(SUM(ms.milk_yield), 0) AS total_production,
  COALESCE(AVG(ms.milk_yield), 0) AS average_daily,
  MAX(ms.date) AS last_milking_date
FROM animals a
LEFT JOIN milking_sessions ms ON a.id = ms.animal_id
GROUP BY a.id, a.name, a.tag;
```

---

## 🧪 Testing y Validación

### Endpoints a Probar

#### Prioridad ALTA (No Funcionan)

1. **Autenticación completa** (`/api/auth/*`)
2. **Módulo de Usuarios** - Migrar de mocks a API real
3. **Permisos de usuario** (`/api/permissions`, relaciones)

#### Prioridad MEDIA (Verificar Funcionamiento)

1. Alertas automáticas
2. Edición/eliminación de sesiones de ordeño
3. Filtros en reportes
4. Exportación de reportes (PDF/CSV)

#### Prioridad BAJA (Mejoras)

1. Paginación en endpoints
2. Búsqueda y filtros avanzados
3. Websockets para actualizaciones en tiempo real

---

## 📝 Checklist de Implementación

### Backend API

#### Módulo Animals ✅

- [x] GET `/api/animals`
- [x] POST `/api/animals`
- [x] PUT `/api/animals/:id`
- [x] DELETE `/api/animals/:id`
- [ ] Cálculo automático de producción total/promedio

#### Módulo Milking Sessions ⚠️

- [x] GET `/api/milking-sessions`
- [x] POST `/api/milking-sessions`
- [ ] PUT `/api/milking-sessions/:id`
- [ ] DELETE `/api/milking-sessions/:id`
- [ ] Validación de tiempos (start < end)
- [ ] Actualizar `lastMilking` en tabla animals

#### Módulo Alerts ⚠️

- [x] GET `/api/alerts`
- [ ] POST `/api/alerts`
- [ ] PATCH `/api/alerts/:id/resolve`
- [ ] DELETE `/api/alerts/:id`
- [ ] Sistema automático de generación de alertas

#### Módulo Reports ⚠️

- [x] GET `/api/reports`
- [ ] GET `/api/reports?start_date=X&end_date=Y`
- [ ] GET `/api/reports/export/pdf`
- [ ] GET `/api/reports/export/csv`

#### Módulo Users ❌

- [ ] GET `/api/users`
- [ ] POST `/api/users`
- [ ] PUT `/api/users/:id`
- [ ] DELETE `/api/users/:id`
- [ ] PATCH `/api/users/:id/status`
- [ ] GET `/api/permissions`
- [ ] GET `/api/user-activity`

#### Autenticación ❌

- [ ] POST `/api/auth/login`
- [ ] POST `/api/auth/logout`
- [ ] POST `/api/auth/register`
- [ ] GET `/api/auth/me`
- [ ] POST `/api/auth/refresh`
- [ ] Middleware de autenticación
- [ ] Sistema de permisos

### Frontend Integration

- [ ] Migrar `Users.tsx` de mocks a API
- [ ] Migrar `App.tsx` autenticación de mock a API
- [ ] Actualizar `api.ts` para manejo de tokens
- [ ] Implementar manejo de errores 401 (no autorizado)
- [ ] Implementar refresh automático de tokens

---

## 🚀 Próximos Pasos Recomendados

1. **Priorizar autenticación y usuarios** (actualmente todo es mock)
2. **Completar endpoints faltantes de sesiones de ordeño** (UPDATE, DELETE)
3. **Implementar sistema de alertas automáticas**
4. **Optimizar endpoint de reportes** con filtros y paginación
5. **Agregar validaciones y manejo de errores robusto**
6. **Implementar logging y auditoría** en backend
7. **Agregar tests unitarios e integración** para endpoints críticos

---

## 📞 Notas Adicionales

- **Servidor Backend:** `http://100.101.21.82:8000`
- **Tecnologías Frontend:** React 18, TypeScript, TanStack Query, Axios
- **Estado del Proyecto:** Frontend funcional con mocks, backend parcialmente implementado
- **Riesgo Principal:** Autenticación y gestión de usuarios completamente mock - NO seguro para producción

**Última actualización:** 2026-02-11
