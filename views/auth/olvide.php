<div class="contenedor olvide">
<?php include_once __DIR__ . "/../templates/nombre-sitio.php";  ?>
    <div class="contenedor-sm">
            <?php include_once __DIR__ . "/../templates/alertas.php";  ?>
        <p class="descripcion-pagina">Recuperá tu Contraseña</p>
        <form action="/olvide" class="formulario" method="POST" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="Tu Email" name="email" value="">
            </div>
                <input type="submit" class="boton" value="Recuperar Contraseña">
        </form>
            <div class="acciones">
                <a href="/">¿Ya tenés cuenta? Iniciar Sesión</a>
                <a href="/crear">¿No tenés cuenta? Creála</a>
            </div>
    </div> <!--.contenedor-sm -->
</div>