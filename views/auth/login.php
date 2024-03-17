<div class="contenedor login">
<?php include_once __DIR__ . "/../templates/nombre-sitio.php";  ?>
    <div class="contenedor-sm">
            <?php include_once __DIR__ . "/../templates/alertas.php";  ?>
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <form action="/" class="formulario" method="POST" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" value="">
            </div>
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu Contraseña" name="password" value="">
            </div>
                <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
            <div class="acciones">
                <a href="/crear">¿No tenés cuenta? Creála</a>
                <a href="/olvide">¿Olvidaste tu contraseña?</a>
            </div>
    </div> <!--.contenedor-sm -->
</div>