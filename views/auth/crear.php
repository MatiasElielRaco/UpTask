<div class="contenedor crear">
   <?php include_once __DIR__ . "/../templates/nombre-sitio.php";  ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear tu cuenta en UpTask</p>
            <?php include_once __DIR__ . "/../templates/alertas.php";  ?>
        <form action="/crear" class="formulario" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu Contraseña" name="password" value="">
            </div>
            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" id="password2" placeholder="Repetí tu Contraseña" name="password2" value="">
            </div>
                <input type="submit" class="boton" value="Crear Cuenta">
        </form>
            <div class="acciones">
                <a href="/">¿Ya tenés cuenta? Iniciar Sesión</a>
                <a href="/olvide">¿Olvidaste tu contraseña?</a>
            </div>
    </div> <!--.contenedor-sm -->
</div>