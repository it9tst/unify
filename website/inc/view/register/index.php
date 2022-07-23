<div id="first_box_register">
    <a href="/" class="logo_register"></a>
</div>
<hr id="logo_hr_register">
<div class="form_register">
    <h3 id="title_register">Crea il tuo account Unify</h3>
    <form>
        <div class="form-group">
            <label for="validationServerUsername">Username</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroupPrepend3">@</span>
                </div>
                <input type="text" class="form-control" id="validationServerUsername" placeholder="Username" aria-describedby="inputGroupPrepend3" required>
                <div class="invalid-feedback" visibility="hidden">
                    Per favore scegli un username.
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="validationServerEmail">E-mail</label>
            <input type="email" class="form-control" id="validationServerEmail" placeholder="Email" value="" required>
            <div class="invalid-feedback">
                Per favore scegli un indirizzo email.
            </div>
        </div>
        <div class="form-group">
            <label for="validationServerPass">Password</label>
            <input type="password" id="validationServerPass" class="form-control" aria-describedby="passwordHelpBlock" placeholder="Password">
            <small id="passwordHelpBlock" class="form-text invalid-feedback">
                La password deve contenere da 8 a 20 caratteri, tra cui lettere e numeri, e non deve contenere caratteri speciali, spazi o emoji.
            </small>
        </div>
        <div class="form-group">
            <label for="validationServerConfPass">Conferma Password</label>
            <input type="password" class="form-control" id="validationServerConfPass" placeholder="Conferma Password" value="" required>
            <div class="invalid-feedback">
                Per favore scegli una password.
            </div>
        </div>
        <div class="form-group data_nascita_register_box">
            <label for="validationServerDay">Data di nascita</label>
            <div class="data_nascita_register">
                <div class="giorno_register">
                    <input type="number" class="form-control" id="validationServerDay" placeholder="Giorno" value="" required min="1" max="31">

                </div>
                <div class="mese_register">
                    <select class="form-control" required>
                        <option value="">Mese</option>
                        <option value="1">Gennaio</option>
                        <option value="2">Febbraio</option>
                        <option value="3">Marzo</option>
                        <option value="4">Aprile</option>
                        <option value="5">Maggio</option>
                        <option value="6">Giugno</option>
                        <option value="7">Luglio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Settembre</option>
                        <option value="10">Ottobre</option>
                        <option value="11">Novembre</option>
                        <option value="12">Dicembre</option>
                    </select>
                </div>

                <div class="anno_register">
                    <input type="number" class="form-control" id="validationServerYear" placeholder="Anno" value="" required min="<?php echo intval(date("Y"))-108;?>" max="<?php echo intval(date("Y"))-16;?>">

                </div>
            </div>
			<div class="invalid-feedback">
                        Per registrarti devi aver compiuto 16 anni in base al Regolamento Europeo in materia di Protezione dei Dati Personali (GDPR).
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline1" name="customRadioInline1" class="form-control custom-control-input">
                <label class="custom-control-label" for="customRadioInline1">Uomo</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" id="customRadioInline2" name="customRadioInline1" class="form-control custom-control-input">
                <label class="custom-control-label" for="customRadioInline2">Donna</label>
            </div>
			<div class="invalid-feedback">
                Campo obbligatorio
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Inviatemi i messaggi di marketing di Unify</label>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="marketingCheck">
                <label class="custom-control-label" for="marketingCheck">Condividi i dettagli della mia registrazione con i fornitori di contenuti Unify per scopi di marketing. Nota: i tuoi dati potrebbero essere trasferiti in un paese al di fuori dello SEE (Spazio economico europeo) secondo quanto descritto nella nostra informativa sulla privacy.</label>
            </div>
        </div>
        <div class="form-group">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="shareCheck" required>
                <label class="form-check-label" for="shareCheck">
                    Agree to terms and conditions
                </label>
                <div class="invalid-feedback">
                    You must agree before submitting.
                </div>
            </div>
        </div>
        <button class="btn btn_rounded button_registrati" type="button" onclick="registratiClick();">REGISTRATI</button>
    </form>
</div>
