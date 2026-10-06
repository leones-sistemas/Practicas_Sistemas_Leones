<main>
    <img src="/assets/img/bg1.png" alt="">
    <nav>
        <figure>
            <img src="/assets/img/form.png" alt="">
        </figure>
        <ul>
            <li class="active">
                <a href="/login/">Iniciar Sesíon</a>
            </li>
            <li>
                <a href="/"><i class="fa-solid fa-circle-arrow-left"></i> Regresar </a>
            </li>
        </ul>
    </nav>
    <section>
        <figure>
            <img src="/assets/img/log.png" alt="">
        </figure>
        <form id="form-login">
            <img src="/assets/img/form.png" alt="">
            <p>Iniciar Sesión</p>
            <div class="input">
                <i class="fa-solid fa-user-tie icon_1"></i>
                <input type="text" placeholder="Usuario" id="user">
            </div>
            <div class="input">
                <i class="fa-solid fa-lock icon_1"></i>
                <input type="password" placeholder="Contraseña" id="password">
                <i class="fa-regular fa-eye-slash icon_2" id="change"></i>
            </div>
            <div class="container-switch">
                <strong>Recuerdame</strong>
                <div class="switch-container">
                    <input type="checkbox" id="toggle" class="switch-input">
                    <label for="toggle" class="switch"></label>
                </div>
            </div>

            <span>¿Olvidaste tu contraseña?</span>
            <div class="cf-turnstile"
                data-sitekey="0x4AAAAAAEpVlO-ulLDn3t6y">
            </div>
            <button id="login" type="submit"> Iniciar sesion <i class="fa-solid fa-arrow-right-to-bracket"></i> </button>
        </form>
    </section>
</main>
