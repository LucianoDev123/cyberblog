<h2>Iniciar sesión</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/login"
    method="POST"
>

    <p>
        <label for="email">Correo electrónico</label>
    </p>

    <input
        type="email"
        id="email"
        name="email"
        required
    >

    <p>
        <label for="password">Contraseña</label>
    </p>

    <input
        type="password"
        id="password"
        name="password"
        required
    >

    <br><br>

    <button type="submit">
        Iniciar sesión
    </button>

</form>