<div class="contenedor crear">
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php';  ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

      <?php include_once __DIR__ . '/../templates/alertas.php';  ?>  
      <form class="formulario" method="POST" action="/crear">
       <div class="campo">
              <label for="nombre">Nombre:</label>
              <input 
                type="text"
                id="nombre"
                placeholder="Tu Nombre"
                name="nombre"
                value="<?php echo $usuario->nombre; ?>"
            />
         </div> 

         <div class="campo">
              <label for="email">Email:</label>
              <input 
                type="email"
                id="email"
                placeholder="Tu Email"
                name="email"
                value="<?php echo $usuario->email; ?>"
            />
         </div> 

         <div class="campo">
              <label for="password">Password:</label>
              <input 
                type="password"
                id="password"
                placeholder="Tu Password"
                name="password"
            />
         </div> 
         
         <div class="campo">
              <label for="password2">Repetir Password:</label>
              <input 
                type="password"
                id="password2"
                placeholder="Repite Tu Password"
                name="password2"
            />
         </div> 

         <input type="submit" class="boton" value="Crear Cuenta">
      </form>

      <div class="acciones">
        <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
        <a href="/olvide">¿Olvidaste tu Password?</a>
      </div>
    </div> <!--:contenedor-sm -->
</div>

<?php include_once __DIR__ . '/../templates/footer.php';  ?>