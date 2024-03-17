<div class="contenedor reestablecer">
   <?php include_once __DIR__ . "/../templates/nombre-sitio.php";  ?>
    <div class="contenedor-sm">
            <?php include_once __DIR__ . "/../templates/alertas.php";  ?>
            <?php if($mostrar) : ?>
        <p class="descripcion-pagina">Colocá tu nueva contraseña</p>
        <form class="formulario" method="POST">
            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Tu Nueva Contraseña" name="password" value="">
            </div>
            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input type="password" id="password2" placeholder="Repetí tu Contraseña" name="password2" value="">
            </div>
                <input type="submit" class="boton" value="Reestablecer Contraseña">
        </form>
        <?php endif; ?>
            <div class="acciones">
                <a href="/">¿Ya tenés cuenta? Iniciar Sesión</a>
                <a href="/crear">¿No tenés cuenta? Creála</a>
            </div>
    </div> <!--.contenedor-sm -->
</div>