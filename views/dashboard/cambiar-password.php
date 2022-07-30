<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver a Perfil</a>

    <form class="formulario" method="POST" action="/cambiar-password">

        <div class="campo">
            <label for="password">Password Actual</label>
            <input 
            type="password"
            name="password_actual"
            placeholder="Tu Password Actual"
            />
        </div>

        <div class="campo">
            <label for="password">Password Nuevo</label>
            <input 
            type="password"
            name="password_nuevo"
            placeholder="Tu Nuevo password"
            />
        </div>

        <div class="campo">
            <label for="password">Repetir Password Nuevo</label>
            <input 
            type="password"
            name="password_nuevo2"
            placeholder="Repite Tu Nuevo password"
            />
        </div>
        
        <input type="submit" class="btn" value="Guardar Cambios">
    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>